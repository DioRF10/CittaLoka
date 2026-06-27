<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * XenditService
 *
 * Service untuk integrasi Xendit:
 * - Invoice (terima pembayaran dari traveler)
 * - Disbursement (transfer ke rekening host)
 * - Bank Account Inquiry (verifikasi rekening host saat onboarding)
 *
 * Menggunakan HTTP client langsung (tanpa SDK) supaya lebih mudah dikontrol
 * dan tidak tergantung versi SDK resmi.
 */
class XenditService
{
    private string $secretKey;
    private string $baseUrl = 'https://api.xendit.co';

    public function __construct()
    {
        $this->secretKey = config('services.xendit.secret_key');
    }

    private function authHeader(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
            'Content-Type' => 'application/json',
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    // INVOICE — Terima pembayaran dari traveler
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Buat invoice pembayaran untuk satu booking.
     *
     * @param  string  $externalId   ID unik kita, biasanya kode_booking
     * @param  int     $amount       Total yang harus dibayar (IDR, tanpa desimal)
     * @param  string  $description  Deskripsi invoice
     * @param  array   $customer     ['given_names' => ..., 'email' => ..., 'mobile_number' => ...]
     * @param  string  $successRedirectUrl
     * @param  string  $failureRedirectUrl
     * @return array   Response dari Xendit (berisi invoice_url, id, dll)
     */
    public function createInvoice(
        string $externalId,
        int $amount,
        string $description,
        array $customer,
        string $successRedirectUrl,
        string $failureRedirectUrl
    ): array {
        $response = Http::withHeaders($this->authHeader())
            ->post("{$this->baseUrl}/v2/invoices", [
                'external_id' => $externalId,
                'amount' => $amount,
                'description' => $description,
                'invoice_duration' => 86400, // 24 jam (dalam detik)
                'customer' => [
                    'given_names' => $customer['given_names'] ?? 'Traveler',
                    'email' => $customer['email'] ?? null,
                    'mobile_number' => $customer['mobile_number'] ?? null,
                ],
                'success_redirect_url' => $successRedirectUrl,
                'failure_redirect_url' => $failureRedirectUrl,
                'currency' => 'IDR',
                'items' => $customer['items'] ?? [],
            ]);

        if ($response->failed()) {
            Log::error('Xendit createInvoice failed', [
                'external_id' => $externalId,
                'response' => $response->body(),
            ]);
            throw new \Exception('Gagal membuat invoice Xendit: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Ambil detail invoice berdasarkan invoice ID Xendit.
     */
    public function getInvoice(string $invoiceId): array
    {
        $response = Http::withHeaders($this->authHeader())
            ->get("{$this->baseUrl}/v2/invoices/{$invoiceId}");

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data invoice Xendit: ' . $response->body());
        }

        return $response->json();
    }

    // ─────────────────────────────────────────────────────────────────────
    // BANK ACCOUNT INQUIRY — Verifikasi rekening host saat onboarding
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Cek nama pemilik rekening berdasarkan nomor rekening + kode bank.
     *
     * @param  string  $bankCode        Contoh: BCA, MANDIRI, BNI, BRI
     * @param  string  $accountNumber
     * @return array   ['account_holder_name' => ..., 'bank_account' => ...]
     */
    public function bankAccountInquiry(string $bankCode, string $accountNumber): array
    {
        $response = Http::withHeaders($this->authHeader())
            ->post("{$this->baseUrl}/bank_account_data_requests", [
                'bank_account_number' => $accountNumber,
                'bank_code' => $bankCode,
            ]);

        if ($response->failed()) {
            Log::error('Xendit bankAccountInquiry failed', [
                'bank_code' => $bankCode,
                'response' => $response->body(),
            ]);
            throw new \Exception('Gagal verifikasi rekening: ' . $response->body());
        }

        return $response->json();
    }
    public function verifyHostBankAccount(string $bankCode, string $accountNumber, string $ktpName): array
    {
        try {
            $result = $this->bankAccountInquiry($bankCode, $accountNumber);

            $accountName = $result['account_holder_name'] ?? $result['bank_account']['account_holder_name'] ?? null;

            if (!$accountName) {
                return [
                    'success' => false,
                    'is_match' => false,
                    'account_name' => null,
                    'error_message' => 'Nama pemilik rekening tidak ditemukan. Periksa kembali nomor rekening.',
                ];
            }

            $isMatch = $this->normalizeNameForComparison($accountName) === $this->normalizeNameForComparison($ktpName);

            return [
                'success' => true,
                'is_match' => $isMatch,
                'account_name' => $accountName,
                'error_message' => null,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'is_match' => false,
                'account_name' => null,
                'error_message' => 'Gagal memverifikasi rekening. Pastikan nomor rekening dan bank sudah benar.',
            ];
        }
    }

    /**
     * Normalisasi nama untuk perbandingan: lowercase, hilangkan spasi ganda,
     * hilangkan gelar/prefix umum (opsional bisa dikembangkan nanti).
     */
    private function normalizeNameForComparison(string $name): string
    {
        $name = strtolower(trim($name));
        $name = preg_replace('/\s+/', ' ', $name); // spasi ganda jadi satu
        return $name;
    }


    // ─────────────────────────────────────────────────────────────────────
    // DISBURSEMENT — Transfer ke rekening host
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Buat disbursement (transfer) ke rekening host.
     *
     * @param  string  $externalId       ID unik kita, biasanya "disb-{booking_id}"
     * @param  string  $bankCode         Contoh: BCA, MANDIRI, BNI, BRI
     * @param  string  $accountNumber
     * @param  string  $accountHolderName
     * @param  int     $amount           Nominal transfer (IDR)
     * @param  string  $description
     * @return array
     */
    public function createDisbursement(
        string $externalId,
        string $bankCode,
        string $accountNumber,
        string $accountHolderName,
        int $amount,
        string $description
    ): array {
        $response = Http::withHeaders($this->authHeader())
            ->post("{$this->baseUrl}/disbursements", [
                'external_id' => $externalId,
                'bank_code' => $bankCode,
                'account_holder_name' => $accountHolderName,
                'account_number' => $accountNumber,
                'description' => $description,
                'amount' => $amount,
            ]);

        if ($response->failed()) {
            Log::error('Xendit createDisbursement failed', [
                'external_id' => $externalId,
                'response' => $response->body(),
            ]);
            throw new \Exception('Gagal membuat disbursement Xendit: ' . $response->body());
        }

        return $response->json();
    }

    public function getBalance(): int
    {
        $response = Http::withHeaders($this->authHeader())
            ->get("{$this->baseUrl}/balance");

        if ($response->failed()) {
            Log::error('Xendit getBalance failed', ['response' => $response->body()]);
            throw new \Exception('Gagal mengambil saldo Xendit: ' . $response->body());
        }

        return (int) ($response->json()['balance'] ?? 0);
    }

    /**
     * Ambil daftar bank yang didukung untuk disbursement.
     */
    public function getAvailableBanks(): array
    {
        $response = Http::withHeaders($this->authHeader())
            ->get("{$this->baseUrl}/available_disbursements_banks");

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil daftar bank: ' . $response->body());
        }

        return $response->json();
    }

    // ─────────────────────────────────────────────────────────────────────
    // WEBHOOK VERIFICATION
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Verifikasi webhook token dari header 'x-callback-token'.
     */
    public function verifyWebhookToken(?string $token): bool
    {
        $expectedToken = config('services.xendit.webhook_token');
        return $token && $expectedToken && hash_equals($expectedToken, $token);
    }
}