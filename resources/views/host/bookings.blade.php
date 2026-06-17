@extends('layouts.dashboard')

@section('title', 'Bookings')
@section('page-title', 'Manage Bookings')

@section('content')

<div x-data="{
    showDetailModal: false,
    loading: false,
    booking: null,
    async openDetail(id) {
        this.showDetailModal = true;
        this.loading = true;
        this.booking = null;
        try {
            const res = await fetch(`/dashboard/bookings/${id}/detail`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            this.booking = await res.json();
        } catch(e) {
            this.booking = { error: 'Gagal memuat data booking.' };
        } finally {
            this.loading = false;
        }
    }
}" @keydown.escape.window="showDetailModal = false">

{{-- Filter Tabs --}}
<div style="display:flex; gap:0.4rem; margin-bottom:1.25rem;">
    @foreach([['all','All Bookings'],['upcoming','Upcoming'],['completed','Completed'],['cancelled','Cancelled']] as [$val,$label])
        <a href="{{ route('host.bookings.index', ['filter' => $val]) }}"
            style="padding:0.45rem 1rem; border-radius:8px; font-size:0.82rem; font-weight:500; text-decoration:none; transition:all 0.15s;
                {{ $filter === $val ? 'background:#1E3A2F; color:white;' : 'background:white; color:#4A4A4A; border:1.5px solid #EDE7DC;' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Table --}}
<div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">

    {{-- Header --}}
    <div style="display:grid; grid-template-columns:1fr 1fr 120px 80px 100px 110px 80px; padding:0.75rem 1.5rem; background:#F7F3ED; border-bottom:1px solid #EDE7DC;">
        @foreach(['Guest','Experience','Date','Guests','Status','Total','Action'] as $col)
            <div style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.06em;">{{ $col }}</div>
        @endforeach
    </div>

    @if($bookings->isEmpty())
        <div style="padding:3rem; text-align:center; color:#9CA3AF; font-size:0.875rem;">
            <div style="font-size:2rem; margin-bottom:0.75rem;">📅</div>
            No bookings found
        </div>
    @else
        @foreach($bookings as $booking)
            @php
                $statusColor = match($booking->status) {
                    'confirmed'       => '#2D5240',
                    'completed'       => '#1E3A2F',
                    'pending_payment' => '#C4783A',
                    default           => '#C0392B',
                };
                $statusBg = match($booking->status) {
                    'confirmed'       => '#EBF5EE',
                    'completed'       => '#E8E4DC',
                    'pending_payment' => '#FDF6EE',
                    default           => '#FEF2F2',
                };
                $tanggal = \Carbon\Carbon::parse($booking->tanggal_experience)->format('d M Y');
                $jam = $booking->jam_experience
                    ? \Carbon\Carbon::parse($booking->jam_experience)->format('H:i')
                    : null;
            @endphp
            <div style="display:grid; grid-template-columns:1fr 1fr 120px 80px 100px 110px 80px; padding:1rem 1.5rem; border-bottom:1px solid #F7F3ED; align-items:center;"
                onmouseover="this.style.background='#FAFAF8'"
                onmouseout="this.style.background='white'">

                {{-- Guest --}}
                <div>
                    <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $booking->user->name }}</div>
                    <div style="font-size:0.75rem; color:#9CA3AF;">{{ $booking->kode_booking }}</div>
                </div>

                {{-- Experience --}}
                <div style="padding-right:1rem;">
                    <div style="font-size:0.82rem; color:#1E3A2F;">{{ Str::limit($booking->experience_title_snapshot, 30) }}</div>
                </div>

                {{-- Date --}}
                <div>
                    <div style="font-size:0.82rem; color:#1E3A2F;">{{ $tanggal }}</div>
                    @if($jam)
                        <div style="font-size:0.72rem; color:#9CA3AF;">{{ $jam }} WITA</div>
                    @endif
                </div>

                {{-- Guests --}}
                <div style="font-size:0.875rem; color:#1E3A2F;">{{ $booking->jumlah_peserta }}</div>

                {{-- Status --}}
                <div>
                    <span style="font-size:0.68rem; font-weight:700; letter-spacing:0.06em; padding:0.2rem 0.6rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                        {{ strtoupper($booking->status) }}
                    </span>
                </div>

                {{-- Total --}}
                <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F;">
                    Rp {{ number_format($booking->host_earning, 0, ',', '.') }}
                </div>

                {{-- Action --}}
                <div>
                    <button @click="openDetail({{ $booking->id }})"
                        style="padding:0.35rem 0.75rem; border:1.5px solid #1E3A2F; border-radius:7px; background:white; color:#1E3A2F; font-size:0.75rem; font-weight:600; cursor:pointer; transition:all 0.15s; font-family:'DM Sans',sans-serif;"
                        onmouseover="this.style.background='#1E3A2F'; this.style.color='white'"
                        onmouseout="this.style.background='white'; this.style.color='#1E3A2F'">
                        Detail
                    </button>
                </div>

            </div>
        @endforeach

        {{-- Pagination --}}
        @if($bookings->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #EDE7DC; display:flex; justify-content:space-between; align-items:center;">
                <div style="font-size:0.8rem; color:#7A7A6E;">
                    Showing {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }}
                </div>
                {{ $bookings->links() }}
            </div>
        @endif
    @endif
</div>

{{-- ── Booking Detail Modal ─────────────────────────────────────────────── --}}
<div x-show="showDetailModal"
    style="position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; padding:1rem;"
    x-cloak>

    {{-- Backdrop --}}
    <div style="position:absolute; inset:0; background:rgba(0,0,0,0.45); backdrop-filter:blur(4px);"
        @click="showDetailModal = false"></div>

    {{-- Modal --}}
    <div style="position:relative; background:white; border-radius:18px; width:100%; max-width:520px; max-height:90vh; overflow-y:auto; box-shadow:0 25px 70px rgba(0,0,0,0.2);"
        x-show="showDetailModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4">

        {{-- Loading state --}}
        <div x-show="loading" style="padding:3rem; text-align:center;">
            <div style="width:36px; height:36px; border:3px solid #EDE7DC; border-top-color:#1E3A2F; border-radius:50%; animation:spin 0.7s linear infinite; margin:0 auto 1rem;"></div>
            <div style="font-size:0.85rem; color:#7A7A6E;">Memuat detail booking…</div>
        </div>

        {{-- Content --}}
        <div x-show="!loading && booking && !booking.error">

            {{-- Header --}}
            <div style="padding:1.5rem 1.5rem 1rem; border-bottom:1px solid #F0EBE3; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem;">
                <div>
                    <div style="font-size:0.68rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Booking Code</div>
                    <div style="font-size:1rem; font-weight:700; color:#1E3A2F; font-family:'DM Sans',sans-serif;" x-text="booking?.kode_booking"></div>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    {{-- Status Badge --}}
                    <span x-text="booking?.status_label"
                        :style="`font-size:0.72rem; font-weight:700; letter-spacing:0.06em; padding:0.3rem 0.8rem; border-radius:999px;
                        background:${{'confirmed':'#EBF5EE','completed':'#E8E4DC','pending_payment':'#FDF6EE','cancelled':'#FEF2F2','expired':'#FEF2F2'}[booking?.status] ?? '#F3F4F6'};
                        color:${{'confirmed':'#2D5240','completed':'#1E3A2F','pending_payment':'#C4783A','cancelled':'#C0392B','expired':'#C0392B'}[booking?.status] ?? '#7A7A6E'}`">
                    </span>
                    {{-- Close --}}
                    <button @click="showDetailModal = false"
                        style="width:30px; height:30px; border-radius:50%; border:1.5px solid #EDE7DC; background:white; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#7A7A6E;"
                        onmouseover="this.style.background='#F7F3ED'"
                        onmouseout="this.style.background='white'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div style="padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:1.25rem;">

                {{-- Experience Info --}}
                <div style="background:#F7F3ED; border-radius:10px; padding:1rem;">
                    <div style="font-size:0.68rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.6rem;">Experience</div>
                    <div style="font-size:0.9rem; font-weight:600; color:#1E3A2F;" x-text="booking?.experience_title"></div>
                    <div style="font-size:0.78rem; color:#7A7A6E; margin-top:0.25rem;" x-text="booking?.location"></div>
                    <div style="display:flex; gap:1.5rem; margin-top:0.75rem; flex-wrap:wrap;">
                        <div>
                            <div style="font-size:0.68rem; color:#9CA3AF; margin-bottom:0.2rem;">Tanggal</div>
                            <div style="font-size:0.82rem; font-weight:500; color:#1E3A2F;" x-text="booking?.tanggal"></div>
                        </div>
                        <div x-show="booking?.jam">
                            <div style="font-size:0.68rem; color:#9CA3AF; margin-bottom:0.2rem;">Waktu</div>
                            <div style="font-size:0.82rem; font-weight:500; color:#1E3A2F;" x-text="booking?.jam"></div>
                        </div>
                        <div>
                            <div style="font-size:0.68rem; color:#9CA3AF; margin-bottom:0.2rem;">Peserta</div>
                            <div style="font-size:0.82rem; font-weight:500; color:#1E3A2F;">
                                <span x-text="booking?.jumlah_peserta"></span> orang
                                <span x-show="booking?.is_private" style="font-size:0.68rem; background:#FDF6EE; color:#C4783A; padding:0.1rem 0.4rem; border-radius:999px; margin-left:0.35rem; font-weight:700;">PRIVATE</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Guest Info --}}
                <div>
                    <div style="font-size:0.68rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.6rem;">Tamu</div>
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#2D5240,#C4A882); display:flex; align-items:center; justify-content:center; color:white; font-size:0.8rem; font-weight:700; flex-shrink:0;">
                            <span x-text="booking?.guest_name?.charAt(0)?.toUpperCase()"></span>
                        </div>
                        <div>
                            <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F;" x-text="booking?.guest_name"></div>
                            <div style="font-size:0.78rem; color:#7A7A6E;" x-text="booking?.guest_email"></div>
                        </div>
                    </div>
                    <div x-show="booking?.notes_for_host" style="margin-top:0.75rem; background:#F7F3ED; border-left:3px solid #C4A882; padding:0.6rem 0.75rem; border-radius:0 8px 8px 0;">
                        <div style="font-size:0.7rem; color:#7A7A6E; margin-bottom:0.2rem;">Catatan untuk host</div>
                        <div style="font-size:0.82rem; color:#1E3A2F; font-style:italic;" x-text="booking?.notes_for_host"></div>
                    </div>
                </div>

                {{-- Price Breakdown --}}
                <div>
                    <div style="font-size:0.68rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.6rem;">Rincian Harga</div>
                    <div style="border:1.5px solid #EDE7DC; border-radius:10px; overflow:hidden;">
                        <div style="display:flex; justify-content:space-between; padding:0.6rem 1rem; border-bottom:1px solid #F0EBE3;">
                            <span style="font-size:0.82rem; color:#7A7A6E;">Harga per orang</span>
                            <span style="font-size:0.82rem; color:#1E3A2F;" x-text="booking?.harga_per_orang"></span>
                        </div>
                        <div x-show="booking?.discount" style="display:flex; justify-content:space-between; padding:0.6rem 1rem; border-bottom:1px solid #F0EBE3;">
                            <span style="font-size:0.82rem; color:#2D5240;">
                                Diskon
                                <span x-show="booking?.coupon_code" style="font-size:0.7rem; background:#EBF5EE; padding:0.1rem 0.4rem; border-radius:4px; margin-left:0.35rem;" x-text="booking?.coupon_code"></span>
                            </span>
                            <span style="font-size:0.82rem; color:#2D5240;" x-text="'- ' + booking?.discount"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; padding:0.6rem 1rem; border-bottom:1px solid #F0EBE3;">
                            <span style="font-size:0.82rem; color:#7A7A6E;">Platform fee</span>
                            <span style="font-size:0.82rem; color:#7A7A6E;" x-text="booking?.platform_fee"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; padding:0.7rem 1rem; background:#F7F3ED;">
                            <span style="font-size:0.875rem; font-weight:700; color:#1E3A2F;">Penghasilan Kamu</span>
                            <span style="font-size:0.875rem; font-weight:700; color:#1E3A2F;" x-text="booking?.host_earning"></span>
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div>
                    <div style="font-size:0.68rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.6rem;">Timeline</div>
                    <div style="display:flex; flex-direction:column; gap:0.5rem;">
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:8px; height:8px; border-radius:50%; background:#9CA3AF; flex-shrink:0;"></div>
                            <div style="font-size:0.78rem; color:#7A7A6E;">Dibuat: <span style="color:#1E3A2F; font-weight:500;" x-text="booking?.created_at"></span></div>
                        </div>
                        <div x-show="booking?.cancelled_at" style="display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:8px; height:8px; border-radius:50%; background:#C0392B; flex-shrink:0;"></div>
                            <div style="font-size:0.78rem; color:#7A7A6E;">Dibatalkan: <span style="color:#C0392B; font-weight:500;" x-text="booking?.cancelled_at"></span></div>
                        </div>
                        <div x-show="booking?.cancel_reason" style="display:flex; align-items:center; gap:0.75rem; padding-left:1.25rem;">
                            <div style="font-size:0.75rem; color:#C0392B; font-style:italic;" x-text="'Alasan: ' + booking?.cancel_reason"></div>
                        </div>
                        <div x-show="booking?.completed_at" style="display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:8px; height:8px; border-radius:50%; background:#2D5240; flex-shrink:0;"></div>
                            <div style="font-size:0.78rem; color:#7A7A6E;">Selesai: <span style="color:#2D5240; font-weight:500;" x-text="booking?.completed_at"></span></div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div style="padding:1rem 1.5rem; border-top:1px solid #F0EBE3; display:flex; justify-content:flex-end;">
                <button @click="showDetailModal = false"
                    style="padding:0.6rem 1.5rem; border:1.5px solid #EDE7DC; border-radius:8px; background:white; font-size:0.875rem; font-weight:500; color:#4A4A4A; cursor:pointer; font-family:'DM Sans',sans-serif;"
                    onmouseover="this.style.background='#F7F3ED'"
                    onmouseout="this.style.background='white'">
                    Tutup
                </button>
            </div>
        </div>

        {{-- Error state --}}
        <div x-show="!loading && booking?.error" style="padding:2rem; text-align:center; color:#C0392B; font-size:0.875rem;">
            <div style="font-size:1.5rem; margin-bottom:0.5rem;">⚠</div>
            <span x-text="booking?.error"></span>
        </div>

    </div>
</div>

</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
[x-cloak] { display: none !important; }
</style>

@endsection
