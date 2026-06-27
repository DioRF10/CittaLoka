<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\DisbursementSentNotification;
use App\Notifications\NewBookingReceivedNotification;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    /**
     * Handle webhook dari Xendit untuk event Invoice.
     * Route: POST /webhooks/xendit/invoice
     */
    public function handleInvoice(Request $request)
    {
        $xendit = app(XenditService::class);

        $token = $request->header('x-callback-token');
        if (!$xendit->verifyWebhookToken($token)) {
            Log::warning('Xendit webhook: invalid token', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $payload = $request->all();
        Log::info('Xendit invoice webhook received', ['payload' => $payload]);

        $externalId = $payload['external_id'] ?? null;
        $status     = $payload['status'] ?? null;

        if (!$externalId) {
            return response()->json(['message' => 'Missing external_id'], 400);
        }

        $booking = Booking::with(['user', 'host.user'])
            ->where('kode_booking', $externalId)
            ->first();

        if (!$booking) {
            Log::warning('Xendit webhook: booking not found', ['external_id' => $externalId]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        switch ($status) {
            case 'PAID':
            case 'SETTLED':
                $booking->update([
                    'status'                 => 'confirmed',
                    'payment_status'         => 'paid',
                    'xendit_payment_method'  => $payload['payment_method'] ?? $payload['payment_channel'] ?? null,
                    'paid_at'                => now(),
                ]);

                // ── Notifikasi ke traveler & host ──
                $booking->user?->notify(new BookingConfirmedNotification($booking));
                $booking->host?->user?->notify(new NewBookingReceivedNotification($booking));

                Log::info('Booking confirmed via Xendit', ['kode_booking' => $booking->kode_booking]);
                break;

            case 'EXPIRED':
                $booking->update([
                    'status'         => 'expired',
                    'payment_status' => 'expired',
                ]);

                if ($booking->availability) {
                    $booking->availability->decrement('booked_slot', $booking->jumlah_peserta);
                }

                Log::info('Booking expired via Xendit', ['kode_booking' => $booking->kode_booking]);
                break;

            case 'FAILED':
                $booking->update([
                    'payment_status' => 'failed',
                ]);

                if ($booking->availability) {
                    $booking->availability->decrement('booked_slot', $booking->jumlah_peserta);
                }

                Log::info('Booking payment failed via Xendit', ['kode_booking' => $booking->kode_booking]);
                break;

            default:
                Log::info('Xendit webhook: unhandled status', ['status' => $status]);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Handle webhook dari Xendit untuk event Disbursement.
     * Route: POST /webhooks/xendit/disbursement
     */
    public function handleDisbursement(Request $request)
    {
        $xendit = app(XenditService::class);

        $token = $request->header('x-callback-token');
        if (!$xendit->verifyWebhookToken($token)) {
            Log::warning('Xendit disbursement webhook: invalid token', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $payload = $request->all();
        Log::info('Xendit disbursement webhook received', ['payload' => $payload]);

        $externalId = $payload['external_id'] ?? null;
        $status     = $payload['status'] ?? null;

        if (!$externalId || !str_starts_with($externalId, 'disb-')) {
            return response()->json(['message' => 'Invalid external_id'], 400);
        }

        $bookingId = (int) str_replace('disb-', '', $externalId);
        $booking   = Booking::with('host.user')->find($bookingId);

        if (!$booking) {
            Log::warning('Xendit disbursement webhook: booking not found', ['booking_id' => $bookingId]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        switch ($status) {
            case 'COMPLETED':
                $booking->update([
                    'disbursement_status' => 'success',
                    'disbursed_at'         => now(),
                ]);

                $booking->host?->user?->notify(new DisbursementSentNotification($booking));

                Log::info('Disbursement completed', ['booking_id' => $bookingId]);
                break;

            case 'FAILED':
                $booking->update([
                    'disbursement_status'          => 'failed',
                    'disbursement_failure_reason'  => $payload['failure_code'] ?? 'Unknown error',
                ]);

                // TODO: alert ke admin (bisa via Filament notification atau email khusus admin)

                Log::warning('Disbursement failed', [
                    'booking_id' => $bookingId,
                    'reason'     => $payload['failure_code'] ?? null,
                ]);
                break;

            default:
                Log::info('Xendit disbursement webhook: unhandled status', ['status' => $status]);
        }

        return response()->json(['message' => 'OK']);
    }
}