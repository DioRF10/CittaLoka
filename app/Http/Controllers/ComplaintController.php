<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\ComplaintPhoto;
use App\Models\User;
use App\Notifications\ComplaintFiledAdminNotification;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // ── Form Ajukan Complaint ────────────────────────────────────────────

    public function create(string $kode)
    {
        $booking = Booking::with(['experience.photos', 'experience.host.user'])
            ->where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereDoesntHave('complaints', function ($q) {
                $q->where('filed_by_user_id', Auth::id());
            })
            ->firstOrFail();

        return view('pages.complaint-create', compact('booking'));
    }

    // ── Simpan Complaint ─────────────────────────────────────────────────

    public function store(Request $request, string $kode)
    {
        $booking = Booking::where('kode_booking', $kode)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereDoesntHave('complaints', function ($q) {
                $q->where('filed_by_user_id', Auth::id());
            })
            ->firstOrFail();

        $request->validate([
            'category' => 'required|in:no_show,not_as_described,safety_concern,payment_issue,inappropriate_behavior,other',
            'description' => 'required|string|max:2000',
            'photos' => 'nullable|array|max:6',
            'photos.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $complaint = Complaint::create([
            'booking_id' => $booking->id,
            'filed_by_user_id' => Auth::id(),
            'filed_by_role' => 'traveler',
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

        return redirect()->route('bookings.show', $booking->kode_booking)
            ->with('success', 'Complaint kamu sudah terkirim dan akan ditinjau tim CittaLoka secepatnya.');
    }
}
