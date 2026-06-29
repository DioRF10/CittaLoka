<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Klik notifikasi → tandai sudah dibaca, lalu redirect ke URL terkait.
     * Route: GET /notifications/{id}/click
     */
    public function click(string $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if (! $notification) {
            return redirect()->back();
        }

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        $url = $notification->data['url'] ?? url('/');

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($url);
            $url = ($parsedUrl['path'] ?? '') . (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');
        }

        return redirect($url ?: '/');
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     * Route: POST /notifications/read-all
     */
    public function readAll(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }

    /**
     * Hapus semua notifikasi.
     * Route: DELETE /notifications/delete-all
     */
    public function destroyAll(Request $request)
    {
        Auth::user()->notifications()->delete();

        return back();
    }
}