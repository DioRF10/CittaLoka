<?php

namespace App\Http\Controllers;

use App\Models\HostFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow host via AJAX.
     * Route: POST /hosts/follow-toggle
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'host_id' => 'required|integer|exists:host,id',
        ]);

        $userId = Auth::id();
        $hostId = $request->input('host_id');

        $existing = HostFollow::where('user_id', $userId)
            ->where('host_id', $hostId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success'    => true,
                'following'  => false,
                'message'    => 'Berhenti mengikuti',
            ]);
        }

        HostFollow::create([
            'user_id' => $userId,
            'host_id' => $hostId,
        ]);

        return response()->json([
            'success'    => true,
            'following'  => true,
            'message'    => 'Mulai mengikuti',
        ]);
    }
}