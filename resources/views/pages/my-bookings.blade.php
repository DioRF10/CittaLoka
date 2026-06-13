@extends('layouts.app')

@section('title', 'My Bookings — CittaLoka')

@section('content')

@php $locale = app()->getLocale(); @endphp

<div style="background:#FAFAF8; min-height:100vh; padding-bottom:5rem;">
    <div style="max-width:760px; margin:0 auto; padding:3rem 2rem 0;">

        {{-- Header --}}
        <h1 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:2.5rem; font-weight:500; color:#1E3A2F; margin-bottom:1.5rem;">
            My Bookings
        </h1>

        {{-- Filter Tabs --}}
        <div style="display:flex; gap:0.5rem; margin-bottom:1.75rem; flex-wrap:wrap;">
            @php
                $tabs = [
                    ['value' => 'all',       'label' => 'All'],
                    ['value' => 'upcoming',  'label' => 'Upcoming'],
                    ['value' => 'completed', 'label' => 'Completed'],
                    ['value' => 'cancelled', 'label' => 'Cancelled'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <a href="{{ route('bookings.index', ['filter' => $tab['value']]) }}"
                    style="padding:0.5rem 1.25rem; border-radius:999px; font-size:0.875rem; font-weight:500; text-decoration:none; transition:all 0.2s;
                        {{ $filter === $tab['value']
                            ? 'background:#1E3A2F; color:white; border:1.5px solid #1E3A2F;'
                            : 'background:white; color:#4A4A4A; border:1.5px solid #E2DDD5;' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Success / Error Messages --}}
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

        {{-- Next Experience Banner --}}
        @if($nextBooking && $filter === 'all')
            @php
                $nextJudul = $nextBooking->experience_title_snapshot;
                $nextTanggal = \Carbon\Carbon::parse($nextBooking->tanggal_experience)->locale('en')->isoFormat('dddd, MMM D');
                $nextJam = $nextBooking->jam_experience ? \Carbon\Carbon::parse($nextBooking->jam_experience)->format('H:i') : '';
            @endphp
            <div style="background:#FDF6EE; border:1.5px solid #F0DFC0; border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F; margin-bottom:0.2rem;">
                        📅 Your next experience is
                        @if($daysUntilNext === 0)
                            <span style="color:#C4783A;">today!</span>
                        @elseif($daysUntilNext === 1)
                            <span style="color:#C4783A;">tomorrow!</span>
                        @else
                            in <span style="color:#C4783A;">{{ $daysUntilNext }} days</span>
                        @endif
                    </div>
                    <div style="font-size:0.8rem; color:#7A7A6E;">
                        {{ Str::limit($nextJudul, 30) }} · {{ $nextTanggal }}{{ $nextJam ? ' · ' . $nextJam . ' WITA' : '' }}
                    </div>
                </div>
                <a href="{{ route('bookings.show', $nextBooking->kode_booking) }}"
                    style="font-size:0.82rem; font-weight:500; color:#1E3A2F; text-decoration:none; white-space:nowrap; display:flex; align-items:center; gap:0.3rem;">
                    View Details
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        @endif

        {{-- Booking List --}}
        @if($bookings->isEmpty())
            <div style="text-align:center; padding:4rem 0;">
                <div style="font-size:3rem; margin-bottom:1rem;">🌿</div>
                <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.5rem; color:#1E3A2F; margin-bottom:0.5rem;">
                    No bookings yet
                </h3>
                <p style="color:#9CA3AF; font-size:0.875rem; margin-bottom:1.5rem;">
                    Start exploring Bali's authentic cultural experiences.
                </p>
                <a href="{{ route('experiences.index') }}"
                    style="display:inline-block; padding:0.75rem 1.5rem; background:#1E3A2F; color:white; border-radius:10px; font-size:0.875rem; font-weight:500; text-decoration:none;">
                    Explore Experiences
                </a>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:1rem;">
                @foreach($bookings as $booking)
                    @php
                        $exp = $booking->experience;
                        $locale = app()->getLocale();
                        $cover = $exp?->photos->where('is_cover', true)->first() ?? $exp?->photos->first();
                        $tanggal = \Carbon\Carbon::parse($booking->tanggal_experience)->locale('en')->isoFormat('ddd, MMM D, YYYY');
                        $jam = $booking->jam_experience ? \Carbon\Carbon::parse($booking->jam_experience)->format('H:i') : null;

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
                            'pending_payment' => 'PENDING',
                            'cancelled'       => 'CANCELLED',
                            'expired'         => 'EXPIRED',
                            'refunded'        => 'REFUNDED',
                            default           => strtoupper($booking->status),
                        };
                    @endphp

                    <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; overflow:hidden; transition:box-shadow 0.2s;"
                        onmouseover="this.style.boxShadow='0 4px 20px rgba(30,58,47,0.08)'"
                        onmouseout="this.style.boxShadow='none'">

                        <div style="display:flex; gap:1.25rem; padding:1.25rem; align-items:flex-start;">

                            {{-- Foto --}}
                            <a href="{{ route('bookings.show', $booking->kode_booking) }}"
                                style="flex-shrink:0; width:110px; height:90px; border-radius:10px; overflow:hidden; display:block;">
                                @if($cover)
                                    <img src="{{ $cover->url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    <div style="width:100%; height:100%; background:linear-gradient(135deg,#2D5240,#C4A882);"></div>
                                @endif
                            </a>

                            {{-- Info --}}
                            <div style="flex:1; min-width:0;">

                                {{-- Status + Total --}}
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                                    <span style="font-size:0.68rem; font-weight:700; letter-spacing:0.08em; padding:0.2rem 0.6rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                                        {{ $statusLabel }}
                                    </span>
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        {{-- Rating bintang kalau completed & sudah review --}}
                                        @if($booking->status === 'completed' && $booking->review)
                                            <div style="color:#C4783A; font-size:0.8rem;">
                                                @for($i = 0; $i < $booking->review->rating; $i++)★@endfor
                                            </div>
                                        @endif
                                        {{-- Refunded label --}}
                                        @if($booking->status === 'cancelled' && $booking->payment_status === 'refunded')
                                            <span style="font-size:0.8rem; color:#C4783A; font-weight:500;">Refunded</span>
                                        @endif
                                        <span style="font-size:0.875rem; font-weight:600; color:#1E3A2F;">
                                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Judul --}}
                                <a href="{{ route('bookings.show', $booking->kode_booking) }}" style="text-decoration:none;">
                                    <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.1rem; font-weight:500; color:#1E3A2F; margin-bottom:0.3rem; line-height:1.3;">
                                        {{ $booking->experience_title_snapshot }}
                                    </h3>
                                </a>

                                {{-- Tanggal & Guests --}}
                                <div style="font-size:0.8rem; color:#7A7A6E; margin-bottom:0.85rem;">
                                    {{ $tanggal }}{{ $jam ? ' · ' . $jam . ' WITA' : '' }} · {{ $booking->jumlah_peserta }} {{ $booking->jumlah_peserta === 1 ? 'Guest' : 'Guests' }}
                                </div>

                                {{-- Action Buttons --}}
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">

                                    @if(in_array($booking->status, ['confirmed', 'pending_payment']))
                                        {{-- Upcoming --}}
                                        <a href="{{ route('bookings.show', $booking->kode_booking) }}"
                                            style="padding:0.45rem 1rem; background:#1E3A2F; color:white; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none; transition:all 0.2s;"
                                            onmouseover="this.style.background='#2D4A32'"
                                            onmouseout="this.style.background='#1E3A2F'">
                                            View Details
                                        </a>
                                        <button onclick="confirmCancel('{{ $booking->kode_booking }}')"
                                            style="padding:0.45rem 1rem; background:white; color:#C0392B; border:1.5px solid #FECACA; border-radius:8px; font-size:0.8rem; font-weight:500; cursor:pointer; transition:all 0.2s;"
                                            onmouseover="this.style.borderColor='#C0392B'"
                                            onmouseout="this.style.borderColor='#FECACA'">
                                            Cancel
                                        </button>

                                    @elseif($booking->status === 'completed')
                                        @if(!$booking->review)
                                            {{-- Belum review --}}
                                            <a href="#"
                                                style="padding:0.45rem 1rem; background:#1E3A2F; color:white; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none;">
                                                Write a Review
                                            </a>
                                            <a href="#"
                                                style="padding:0.45rem 1rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none;">
                                                Memory Book
                                            </a>
                                        @else
                                            {{-- Sudah review --}}
                                            <a href="#"
                                                style="padding:0.45rem 1rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none;">
                                                View Memory Book
                                            </a>
                                        @endif

                                    @elseif(in_array($booking->status, ['cancelled', 'expired', 'refunded']))
                                        {{-- Cancelled --}}
                                        @if($exp)
                                            <a href="{{ route('experiences.show', $exp->slug) }}"
                                                style="padding:0.45rem 1rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none;">
                                                Book Again
                                            </a>
                                        @endif
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancelModal" style="display:none; position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.4); backdrop-filter:blur(2px); align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:2rem; max-width:400px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
        <h3 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.4rem; color:#1E3A2F; margin-bottom:0.5rem;">Cancel Booking?</h3>
        <p style="font-size:0.875rem; color:#7A7A6E; margin-bottom:1.5rem; line-height:1.6;">
            Free cancellation is available up to 24 hours before the experience. After that, cancellations are non-refundable.
        </p>
        <form id="cancelForm" method="POST">
            @csrf
            @method('PATCH')
            <div style="display:flex; gap:0.75rem;">
                <button type="button" onclick="closeCancelModal()"
                    style="flex:1; padding:0.75rem; background:white; color:#1E3A2F; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.875rem; font-weight:500; cursor:pointer;">
                    Keep Booking
                </button>
                <button type="submit"
                    style="flex:1; padding:0.75rem; background:#C0392B; color:white; border:none; border-radius:8px; font-size:0.875rem; font-weight:500; cursor:pointer;">
                    Yes, Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmCancel(kode) {
    const modal = document.getElementById('cancelModal');
    const form  = document.getElementById('cancelForm');
    form.action = `/bookings/${kode}/cancel`;
    modal.style.display = 'flex';
}
function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
}
// Tutup modal kalau klik backdrop
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});
</script>
@endpush