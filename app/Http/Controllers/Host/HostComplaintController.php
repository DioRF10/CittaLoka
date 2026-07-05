<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Complaint;
use App\Models\ComplaintPhoto;
use App\Models\User;
use App\Notifications\ComplaintFiledAdminNotification;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostComplaintController extends Controller
{
    private function getHost()
    {
        return Auth::user()->host;
    }

    // ── Form Ajukan Complaint ────────────────────────────────────────────

    public function create(string $kode)
    {
        $host = $this->getHost();

        $booking = Booking::with(['experience', 'user'])
            ->where('kode_booking', $kode)
            ->where('host_id', $host->id)
            ->where('status', 'completed')
            ->whereDoesntHave('complaints', function ($q) {
                $q->where('filed_by_user_id', Auth::id());
            })
            ->firstOrFail();

        if (!Complaint::canFileFor($booking)) {
            return redirect()->route('host.bookings.index')
                ->with('error', 'Batas waktu pengajuan complaint untuk booking ini sudah lewat (' . Complaint::WINDOW_HOURS . ' jam setelah experience selesai).');
        }

        return view('host.complaint-create', compact('booking'));
    }

    // ── Simpan Complaint ─────────────────────────────────────────────────

    public function store(Request $request, string $kode)
    {
        $host = $this->getHost();

        $booking = Booking::where('kode_booking', $kode)
            ->where('host_id', $host->id)
            ->where('status', 'completed')
            ->whereDoesntHave('complaints', function ($q) {
                $q->where('filed_by_user_id', Auth::id());
            })
            ->firstOrFail();

        if (!Complaint::canFileFor($booking)) {
            return redirect()->route('host.bookings.index')
                ->with('error', 'Batas waktu pengajuan complaint untuk booking ini sudah lewat (' . Complaint::WINDOW_HOURS . ' jam setelah experience selesai).');
        }

        $request->validate([
            'category' => 'required|in:no_show,not_as_described,safety_concern,payment_issue,inappropriate_behavior,other',
            'description' => 'required|string|max:2000',
            'photos' => 'nullable|array|max:6',
            'photos.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $complaint = Complaint::create([
            'booking_id' => $booking->id,
            'filed_by_user_id' => Auth::id(),
            'filed_by_role' => 'host',
            'category' => $request->category,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        if ($request->hasFile('photos')) {
            $cloudinary = new CloudinaryService();

            foreach ($request->file('photos') as $index => $photo) {
                $uploaded = $cloudinary->upload($photo, 'cittaloka/complaints/' . $complaint->id);

                ComplaintPhoto::create([
                    'complaint_id' => $complaint->id,
                    'url' => $uploaded['url'],
                    'sort_order' => $index,
                ]);
            }
        }

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ComplaintFiledAdminNotification($complaint));
        }

        return redirect()->route('host.bookings.index')
            ->with('success', 'Complaint kamu sudah terkirim dan akan ditinjau tim CittaLoka secepatnya.');
    }
}