<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\MemoryBook;
use App\Models\MemoryBookPhoto;
use App\Models\Booking;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemoryBookFillController extends Controller
{
    private function getHost()
    {
        return Auth::user()->host;
    }

    // ── Show form ──────────────────────────────────────────────────────────
    // GET /dashboard/memory-books/{id}/fill

    public function show(int $id)
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $memoryBook = MemoryBook::with(['booking.user', 'booking.experience', 'photos'])
            ->whereHas('booking', fn($q) => $q->where('host_id', $host->id))
            ->findOrFail($id);

        $booking = $memoryBook->booking;

        return view('host.memory-books.fill', compact('memoryBook', 'booking'));
    }

    // ── Save / Send ────────────────────────────────────────────────────────
    // PUT /dashboard/memory-books/{id}/fill

    public function update(Request $request, int $id)
    {
        $host = $this->getHost();
        if (!$host) return redirect()->route('home');

        $memoryBook = MemoryBook::with(['booking', 'photos'])
            ->whereHas('booking', fn($q) => $q->where('host_id', $host->id))
            ->findOrFail($id);

        $request->validate([
            'judul'            => 'required|string|max:255',
            'host_message'     => 'required|string',
            'quote_highlight'  => 'nullable|string|max:255',
            'pesan_penutup'    => 'nullable|string',
            'highlight_items'  => 'nullable|string',
            'cover_photo'      => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'photos.*'         => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $action = $request->input('action', 'draft'); // 'draft' atau 'send'

        $cloudinary = app(CloudinaryService::class);

        // ── Parse highlight items ──
        $highlightItems = null;
        if ($request->filled('highlight_items')) {
            $decoded = json_decode($request->highlight_items, true);
            // Filter item yang kosong
            if (is_array($decoded)) {
                $highlightItems = array_values(array_filter($decoded, function ($item) {
                    return !empty($item['judul']);
                }));
            }
        }

        // ── Upload cover photo (1 foto) ──
        $coverPhotoUrl = $memoryBook->cover_photo_url;
        if ($request->hasFile('cover_photo')) {
            $uploadedCover = $cloudinary->upload($request->file('cover_photo'), 'cittaloka/memory-books/cover');
            $coverPhotoUrl = $uploadedCover['url'];
        }

        // ── Upload foto gallery baru ke Cloudinary (maks 20) ──
        if ($request->hasFile('photos')) {
            $existingCount = $memoryBook->photos()->count();
            $maxAllowed    = 20 - $existingCount;

            foreach (array_slice($request->file('photos'), 0, $maxAllowed) as $index => $file) {
                $uploaded = $cloudinary->upload($file, 'cittaloka/memory-books/gallery');

                MemoryBookPhoto::create([
                    'memory_book_id' => $memoryBook->id,
                    'url'            => $uploaded['url'],
                    'sort_order'     => $existingCount + $index + 1,
                ]);
            }
        }

        // ── Update memory book ──
        $memoryBook->update([
            'judul'           => $request->judul,
            'cover_photo_url' => $coverPhotoUrl,
            'host_message'    => $request->host_message,
            'quote_highlight' => $request->quote_highlight,
            'pesan_penutup'   => $request->pesan_penutup,
            'highlight_items' => $highlightItems,
            'status'          => $action === 'send' ? 'sent' : $memoryBook->status,
            'sent_at'         => $action === 'send' ? now() : $memoryBook->sent_at,
        ]);

        if ($action === 'send') {
            // ── Notifikasi ke traveler ──
            $memoryBook->booking->user?->notify(new \App\Notifications\MemoryBookSentNotification($memoryBook));

            return redirect()
                ->route('host.memory-books.index')
                ->with('success', 'Memory Book berhasil dikirim ke ' . $memoryBook->booking->user->name . '! 🎉');
        }

        return redirect()
            ->route('host.memory-books.fill', $memoryBook->id)
            ->with('success', 'Draft tersimpan.');
    }

    // ── Delete photo ───────────────────────────────────────────────────────
    // DELETE /dashboard/memory-books/photos/{photoId}

    public function deletePhoto(int $photoId)
    {
        $host = $this->getHost();
        if (!$host) return response()->json(['success' => false], 403);

        $photo = MemoryBookPhoto::whereHas('memoryBook.booking', function ($q) use ($host) {
            $q->where('host_id', $host->id);
        })->findOrFail($photoId);

        // Hapus dari Cloudinary juga (opsional)
        // app(CloudinaryService::class)->delete($photo->url);

        $photo->delete();

        return response()->json(['success' => true]);
    }
}