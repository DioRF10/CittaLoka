@extends('layouts.app')

@section('title', 'Booking Detail — ' . $booking->kode_booking)

@section('content')

@php
    $locale = app()->getLocale();
    $exp = $booking->experience;
    $cover = $exp?->photos->where('is_cover', true)->first() ?? $exp?->photos->first();
    $tanggal = \Carbon\Carbon::parse($booking->tanggal_experience)->locale('en')->isoFormat('dddd, MMMM D, YYYY');
    $jam = $booking->jam_experience ? \Carbon\Carbon::parse($booking->jam_experience)->format('H:i') : null;
    $jamSelesai = ($booking->jam_experience && $exp?->durasi_menit)
        ? \Carbon\Carbon::parse($booking->jam_experience)->addMinutes($exp->durasi_menit)->format('H:i')
        : null;

    $statusColor = match($booking->status) {
        'confirmed'       => '#2D5240',
        'completed'       => '#1E3A2F',
        'pending_payment' => '#C4783A',
        'cancelled','expired' => '#C0392B',
        'refunded'        => '#7A7A6E',
        default           => '#7A7A6E',
    };
    $statusBg = match($booking->status) {
        'confirmed'       => '#EBF5EE',
        'completed'       => '#E8E4DC',
        'pending_payment' => '#FDF6EE',
        'cancelled','expired' => '#FEF2F2',
        'refunded'        => '#F3F4F6',
        default           => '#F3F4F6',
    };
    $statusLabel = match($booking->status) {
        'confirmed'       => 'CONFIRMED',
        'completed'       => 'COMPLETED',
        'pending_payment' => 'PENDING PAYMENT',
        'cancelled'       => 'CANCELLED',
        'expired'         => 'EXPIRED',
        'refunded'        => 'REFUNDED',
        default           => strtoupper($booking->status),
    };
@endphp

<div style="background:#FAFAF8; min-height:100vh; padding-bottom:5rem;">

    {{-- Breadcrumb --}}
    <div style="background:#F7F3ED; padding:0.75rem 0; border-bottom:1px solid #EDE7DC;">
        <div style="max-width:900px; margin:0 auto; padding:0 2rem;">
            <nav style="font-size:0.8rem; color:#7A7A6E; display:flex; align-items:center; gap:0.4rem;">
                <a href="{{ route('home') }}" style="color:#7A7A6E; text-decoration:none;">Home</a>
                <span>/</span>
                <a href="{{ route('bookings.index') }}" style="color:#7A7A6E; text-decoration:none;">My Bookings</a>
                <span>/</span>
                <span style="color:#1E3A2F; font-weight:500;">{{ $booking->kode_booking }}</span>
            </nav>
        </div>
    </div>

    <div style="max-width:900px; margin:0 auto; padding:2.5rem 2rem 0;">

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div style="background:#EBF5EE; border:1px solid #B8DFC8; color:#2D5240; padding:0.75rem 1rem; border-radius:8px; font-size:0.875rem; margin-bottom:1.25rem;">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#FEF2F2; border:1px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:8px; font-size:0.875rem; margin-bottom:1.25rem;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
            <div>
                <h1 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:2rem; font-weight:500; color:#1E3A2F; margin-bottom:0.4rem;">
                    Booking Detail
                </h1>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <span style="font-size:0.875rem; font-weight:600; color:#1E3A2F; font-family:'DM Sans',sans-serif;">
                        {{ $booking->kode_booking }}
                    </span>
                    <span style="font-size:0.7rem; font-weight:700; letter-spacing:0.08em; padding:0.25rem 0.75rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                @if(in_array($booking->status, ['confirmed', 'pending_payment']))
                    <button onclick="document.getElementById('cancelModal').style.display='flex'"
                        style="padding:0.6rem 1.25rem; background:white; color:#C0392B; border:1.5px solid #FECACA; border-radius:8px; font-size:0.85rem; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s;"
                        onmouseover="this.style.borderColor='#C0392B'"
                        onmouseout="this.style.borderColor='#FECACA'">
                        Cancel Booking
                    </button>
                @elseif($booking->status === 'completed' && !$booking->review)
                    <a href="#"
                        style="padding:0.6rem 1.25rem; background:#1E3A2F; color:white; border-radius:8px; font-size:0.85rem; font-weight:500; text-decoration:none; font-family:'DM Sans',sans-serif;">
                        Write a Review
                    </a>
                @elseif($booking->status === 'completed' && $booking->review)
                    <a href="#"
                        style="padding:0.6rem 1.25rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.85rem; font-weight:500; text-decoration:none; font-family:'DM Sans',sans-serif;">
                        View Memory Book
                    </a>
                @elseif(in_array($booking->status, ['cancelled', 'expired', 'refunded']))
                    @if($exp)
                        <a href="{{ route('experiences.show', $exp->slug) }}"
                            style="padding:0.6rem 1.25rem; background:#1E3A2F; color:white; border-radius:8px; font-size:0.85rem; font-weight:500; text-decoration:none; font-family:'DM Sans',sans-serif;">
                            Book Again
                        </a>
                    @endif
                @endif
            </div>
        </div>

        {{-- Main Grid --}}
        <div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start;">

            {{-- ══ KIRI ══ --}}
            <div style="display:flex; flex-direction:column; gap:1.25rem;">

                {{-- Experience Card --}}
                <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; overflow:hidden;">
                    {{-- Cover Photo --}}
                    @if($cover)
                        <div style="height:200px; overflow:hidden;">
                            <img src="{{ $cover->url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    @endif

                    <div style="padding:1.5rem;">
                        {{-- Kategori --}}
                        <div style="font-size:0.65rem; font-weight:700; color:#C4783A; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.4rem;">
                            {{ strtoupper($exp?->kategori?->getNama($locale) ?? 'Experience') }}
                        </div>

                        {{-- Judul --}}
                        <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; font-weight:500; color:#1E3A2F; margin-bottom:0.75rem; line-height:1.3;">
                            {{ $booking->experience_title_snapshot }}
                        </h2>

                        {{-- Meta grid --}}
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; padding-top:1rem; border-top:1px solid #EDE7DC;">
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Date</div>
                                <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $tanggal }}</div>
                            </div>
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Time</div>
                                <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">
                                    {{ $jam ?? '-' }}{{ $jamSelesai ? ' – ' . $jamSelesai : '' }} WITA
                                </div>
                            </div>
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Guests</div>
                                <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">
                                    {{ $booking->jumlah_peserta }} {{ $booking->jumlah_peserta === 1 ? 'person' : 'people' }}
                                </div>
                            </div>
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Location</div>
                                <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $booking->location_snapshot }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Meeting Point --}}
                <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.5rem;">
                    <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.2rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">
                        Meeting Point
                    </h3>
                    <div style="display:flex; align-items:flex-start; gap:0.5rem; font-size:0.875rem; color:#4A4A4A; margin-bottom:1rem;">
                        <span>📍</span>
                        <span>{{ $exp?->meeting_point ?? $exp?->alamat_lengkap ?? $booking->location_snapshot }}</span>
                    </div>

                    {{-- Map --}}
                    <div style="border-radius:10px; overflow:hidden; height:180px; background:#EDE7DC;">
                        @if($exp?->lokasi_lat && $exp?->lokasi_lng)
                            <iframe
                                src="https://maps.google.com/maps?q={{ $exp->lokasi_lat }},{{ $exp->lokasi_lng }}&z=15&output=embed"
                                width="100%" height="180" style="border:0;" allowfullscreen loading="lazy">
                            </iframe>
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; color:#7A7A6E; gap:0.5rem;">
                                <div style="font-size:2rem;">🗺</div>
                                <div style="font-size:0.8rem;">Map not available</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Host Card --}}
                @if($exp?->host)
                    <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.5rem;">
                        <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.2rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">
                            Your Host
                        </h3>
                        <div style="display:flex; align-items:center; justify-content:space-between;">
                            <div style="display:flex; align-items:center; gap:1rem;">
                                <img src="{{ $exp->host->user->avatarUrl() }}" alt=""
                                    style="width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid #EDE7DC;">
                                <div>
                                    <div style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.05rem; font-weight:500; color:#1E3A2F;">
                                        {{ $booking->host_name_snapshot }}
                                    </div>
                                    <div style="font-size:0.78rem; color:#7A7A6E;">
                                        {{ $exp->host->village ? $exp->host->village . ' · ' : '' }}
                                        {{ $exp->host->bio ? Str::limit($exp->host->bio, 50) : 'Experienced local host' }}
                                    </div>
                                </div>
                            </div>
                            <a href="/hosts/{{ $exp->host->id }}"
                                style="padding:0.5rem 1rem; border:1.5px solid #1E3A2F; color:#1E3A2F; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none; white-space:nowrap;">
                                View Profile
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Notes for Host --}}
                @if($booking->notes_for_host)
                    <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.5rem;">
                        <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.2rem; font-weight:500; color:#1E3A2F; margin-bottom:0.75rem;">
                            Your Notes
                        </h3>
                        <p style="font-size:0.875rem; color:#4A4A4A; line-height:1.6;">{{ $booking->notes_for_host }}</p>
                    </div>
                @endif

                {{-- Cancellation Info --}}
                @if($booking->status === 'cancelled')
                    <div style="background:#FEF2F2; border-radius:16px; border:1.5px solid #FECACA; padding:1.5rem;">
                        <h3 style="font-size:0.875rem; font-weight:600; color:#C0392B; margin-bottom:0.5rem;">
                            Booking Cancelled
                        </h3>
                        <p style="font-size:0.82rem; color:#C0392B; line-height:1.5;">
                            Cancelled on {{ \Carbon\Carbon::parse($booking->cancelled_at)->format('d M Y, H:i') }} WITA
                            @if($booking->cancel_reason)
                                <br>Reason: {{ $booking->cancel_reason }}
                            @endif
                        </p>
                    </div>
                @endif

            </div>

            {{-- ══ KANAN ══ --}}
            <div style="display:flex; flex-direction:column; gap:1.25rem;">

                {{-- Price Breakdown --}}
                <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.5rem;">
                    <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.2rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">
                        Price Details
                    </h3>

                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#4A4A4A; margin-bottom:0.6rem;">
                        <span>Rp {{ number_format($booking->harga_per_orang_snapshot, 0, ',', '.') }} × {{ $booking->jumlah_peserta }} {{ $booking->jumlah_peserta === 1 ? 'person' : 'people' }}</span>
                        <span>Rp {{ number_format($booking->harga_per_orang_snapshot * $booking->jumlah_peserta, 0, ',', '.') }}</span>
                    </div>

                    @if($booking->discount_amount > 0)
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#2D5240; margin-bottom:0.6rem;">
                            <span>Discount</span>
                            <span>-Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#4A4A4A; margin-bottom:0.75rem;">
                        <span>Platform fee</span>
                        <span>Rp {{ number_format($booking->platform_fee, 0, ',', '.') }}</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; font-size:1rem; font-weight:700; color:#1E3A2F; padding-top:0.75rem; border-top:1px solid #EDE7DC;">
                        <span>Total</span>
                        <span>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>

                    {{-- Payment Status --}}
                    <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid #EDE7DC;">
                        <div style="display:flex; justify-content:space-between; font-size:0.8rem;">
                            <span style="color:#7A7A6E;">Payment Status</span>
                            <span style="font-weight:600; color:{{ $booking->payment_status === 'paid' ? '#2D5240' : '#C4783A' }};">
                                {{ strtoupper($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Booking Info --}}
                <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.5rem;">
                    <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.2rem; font-weight:500; color:#1E3A2F; margin-bottom:1rem;">
                        Booking Info
                    </h3>
                    <div style="display:flex; flex-direction:column; gap:0.75rem;">
                        <div>
                            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.2rem;">Booking Code</div>
                            <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F; font-family:'DM Sans',sans-serif;">{{ $booking->kode_booking }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.2rem;">Booked On</div>
                            <div style="font-size:0.875rem; color:#1E3A2F;">{{ $booking->created_at->format('d M Y, H:i') }} WITA</div>
                        </div>
                        @if($booking->completed_at)
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.2rem;">Completed On</div>
                                <div style="font-size:0.875rem; color:#1E3A2F;">{{ $booking->completed_at->format('d M Y, H:i') }} WITA</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Cancellation Policy --}}
                <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.5rem;" x-data="{ open: false }">
                    <button @click="open = !open" style="width:100%; display:flex; justify-content:space-between; align-items:center; background:none; border:none; cursor:pointer; padding:0;">
                        <span style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.1rem; font-weight:500; color:#1E3A2F;">Cancellation Policy</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7A7A6E" stroke-width="2"
                            :style="open ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s; flex-shrink:0;">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition style="margin-top:0.75rem; font-size:0.82rem; color:#7A7A6E; line-height:1.6;">
                        Free cancellation up to 24 hours before the experience starts.
                        After that, cancellations are non-refundable. Please contact us if you need assistance.
                    </div>
                </div>

                {{-- Need Help --}}
                <div style="background:#F7F3ED; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.25rem; text-align:center;">
                    <div style="font-size:1.25rem; margin-bottom:0.5rem;">💬</div>
                    <div style="font-size:0.85rem; font-weight:500; color:#1E3A2F; margin-bottom:0.25rem;">Need Help?</div>
                    <div style="font-size:0.78rem; color:#7A7A6E; margin-bottom:0.75rem;">Contact our support team for any questions about your booking.</div>
                    <a href="mailto:support@cittaloka.com"
                        style="font-size:0.8rem; font-weight:500; color:#1E3A2F; text-decoration:underline;">
                        support@cittaloka.com
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancelModal" style="display:none; position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.4); backdrop-filter:blur(2px); align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:2rem; max-width:400px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
        <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.4rem; color:#1E3A2F; margin-bottom:0.5rem;">Cancel Booking?</h3>
        <p style="font-size:0.875rem; color:#7A7A6E; margin-bottom:1.5rem; line-height:1.6;">
            Free cancellation is available up to 24 hours before the experience. After that, cancellations are non-refundable.
        </p>
        <form method="POST" action="{{ route('bookings.cancel', $booking->kode_booking) }}">
            @csrf
            @method('PATCH')
            <div style="display:flex; gap:0.75rem;">
                <button type="button" onclick="document.getElementById('cancelModal').style.display='none'"
                    style="flex:1; padding:0.75rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.875rem; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif;">
                    Keep Booking
                </button>
                <button type="submit"
                    style="flex:1; padding:0.75rem; background:#C0392B; color:white; border:none; border-radius:8px; font-size:0.875rem; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif;">
                    Yes, Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection