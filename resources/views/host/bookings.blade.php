@extends('layouts.dashboard')

@section('title', 'Bookings')
@section('page-title', 'Manage Bookings')

@section('content')

    @if(session('success'))
        <div
            style="background:#F0FDF4; border:1.5px solid #BBF7D0; color:#166534; padding:0.75rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1.25rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div
            style="background:#FEF2F2; border:1.5px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1.25rem;">
            {{ session('error') }}
        </div>
    @endif

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
            @foreach([['all', 'All Bookings'], ['upcoming', 'Upcoming'], ['completed', 'Completed'], ['cancelled', 'Cancelled']] as [$val, $label])
                <a href="{{ route('host.bookings.index', ['filter' => $val]) }}"
                    style="padding:0.45rem 1rem; border-radius:8px; font-size:0.82rem; font-weight:500; text-decoration:none; transition:all 0.15s;
                                        {{ $filter === $val ? 'background:#1E3A2F; color:white;' : 'background:white; color:#4A4A4A; border:1.5px solid #EDE7DC;' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Table --}}
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">
            <div
                style="display:grid; grid-template-columns:1fr 1fr 120px 80px minmax(140px, 180px) 110px 80px; padding:0.75rem 1.5rem; background:#F7F3ED; border-bottom:1px solid #EDE7DC;">
                @foreach(['Guest', 'Experience', 'Date', 'Guests', 'Status', 'Total', 'Action'] as $col)
                    <div
                        style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.06em;">
                        {{ $col }}
                    </div>
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
                        $statusColor = match ($booking->status) {
                            'confirmed' => '#2D5240',
                            'completed' => '#1E3A2F',
                            'pending_payment' => '#C4783A',
                            default => '#C0392B',
                        };
                        $statusBg = match ($booking->status) {
                            'confirmed' => '#EBF5EE',
                            'completed' => '#E8E4DC',
                            'pending_payment' => '#FDF6EE',
                            default => '#FEF2F2',
                        };
                        $tanggal = \Carbon\Carbon::parse($booking->tanggal_experience)->format('d M Y');
                        $jam = $booking->jam_experience
                            ? \Carbon\Carbon::parse($booking->jam_experience)->format('H:i')
                            : null;
                    @endphp
                    <div style="display:grid; grid-template-columns:1fr 1fr 120px 80px minmax(140px, 180px) 110px 80px; padding:1rem 1.5rem; border-bottom:1px solid #F7F3ED; align-items:center;"
                        onmouseover="this.style.background='#FAFAF8'" onmouseout="this.style.background='white'">
                        <div>
                            <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $booking->user->name }}</div>
                            <div style="font-size:0.75rem; color:#9CA3AF;">{{ $booking->kode_booking }}</div>
                        </div>
                        <div style="padding-right:1rem;">
                            <div style="font-size:0.82rem; color:#1E3A2F;">{{ Str::limit($booking->experience_title_snapshot, 30) }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:0.82rem; color:#1E3A2F;">{{ $tanggal }}</div>
                            @if($jam)
                                <div style="font-size:0.72rem; color:#9CA3AF;">{{ $jam }} WITA</div>
                            @endif
                        </div>
                        <div style="font-size:0.875rem; color:#1E3A2F;">{{ $booking->jumlah_peserta }}</div>
                        <div style="min-width:0;">
                            <span
                                style="font-size:0.68rem; font-weight:700; letter-spacing:0.06em; padding:0.2rem 0.6rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                                {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                        <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F;">
                            Rp {{ number_format($booking->host_earning, 0, ',', '.') }}
                        </div>
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

                @if($bookings->hasPages())
                    <div
                        style="padding:1rem 1.5rem; border-top:1px solid #EDE7DC; display:flex; justify-content:space-between; align-items:center;">
                        <div style="font-size:0.8rem; color:#7A7A6E;">
                            Showing {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }}
                        </div>
                        {{ $bookings->links() }}
                    </div>
                @endif
            @endif
        </div>

        {{-- ── Booking Detail Modal ── --}}
        <template x-teleport="body">
            <div x-show="showDetailModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>

                {{-- Backdrop --}}
                <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);"
                    @click="showDetailModal = false"></div>

                {{-- Modal --}}
                <div style="position:relative; background:white; border-radius:20px; width:100%; max-width:560px; max-height:92vh; overflow-y:auto; box-shadow:0 30px 80px rgba(0,0,0,0.25);"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                    {{-- Loading --}}
                    <div x-show="loading" style="padding:4rem; text-align:center;">
                        <div
                            style="width:40px; height:40px; border:3px solid #EDE7DC; border-top-color:#1E3A2F; border-radius:50%; animation:spin 0.7s linear infinite; margin:0 auto 1rem;">
                        </div>
                        <div style="font-size:0.85rem; color:#7A7A6E;">Memuat detail booking…</div>
                    </div>

                    {{-- Error --}}
                    <div x-show="!loading && booking?.error" style="padding:3rem; text-align:center; color:#C0392B;">
                        <div style="font-size:2rem; margin-bottom:0.5rem;">⚠</div>
                        <span x-text="booking?.error"></span>
                    </div>

                    {{-- Content --}}
                    <div x-show="!loading && booking && !booking.error">

                        {{-- Header --}}
                        <div
                            style="padding:1.5rem 1.5rem 1.25rem; border-bottom:1px solid #F0EBE3; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                            <div>
                                <div
                                    style="font-size:0.65rem; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.2rem;">
                                    Booking Code</div>
                                <div style="font-size:1rem; font-weight:800; color:#1E3A2F; font-family:'DM Sans',sans-serif;"
                                    x-text="booking?.kode_booking"></div>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <span x-text="booking?.status_label"
                                    :style="`font-size:0.72rem; font-weight:700; letter-spacing:0.06em; padding:0.3rem 0.875rem; border-radius:999px;
                                    background:${({ confirmed: '#EBF5EE', completed: '#E8E4DC', pending_payment: '#FDF6EE', cancelled: '#FEF2F2', expired: '#FEF2F2' }[booking?.status] ?? '#F3F4F6')};
                                    color:${({ confirmed: '#2D5240', completed: '#1E3A2F', pending_payment: '#C4783A', cancelled: '#C0392B', expired: '#C0392B' }[booking?.status] ?? '#7A7A6E')}`">
                                </span>
                                <button @click="showDetailModal = false"
                                    style="width:32px; height:32px; border-radius:50%; border:1.5px solid #EDE7DC; background:white; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#7A7A6E; flex-shrink:0;"
                                    onmouseover="this.style.background='#F7F3ED'"
                                    onmouseout="this.style.background='white'">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1.5rem;">

                            {{-- ── TRAVELER ── --}}
                            <div>
                                <div
                                    style="font-size:0.65rem; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.875rem;">
                                    Traveler</div>
                                <div style="display:flex; align-items:center; gap:1rem;">
                                    <img :src="booking?.guest_avatar" alt=""
                                        style="width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid #EDE7DC; flex-shrink:0;">
                                    <div style="flex:1; min-width:0;">
                                        <div
                                            style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.2rem;">
                                            <div style="font-size:0.9rem; font-weight:700; color:#1E3A2F;"
                                                x-text="booking?.guest_name"></div>
                                            <template x-if="booking?.guest_is_verified">
                                                <span
                                                    style="font-size:0.65rem; font-weight:700; background:#EBF5EE; color:#2D5240; padding:0.15rem 0.5rem; border-radius:999px; letter-spacing:0.05em;">✓
                                                    VERIFIED</span>
                                            </template>
                                        </div>
                                        <div style="font-size:0.78rem; color:#7A7A6E;" x-text="booking?.guest_email"></div>
                                        <template x-if="booking?.status === 'confirmed' || booking?.status === 'completed'">
                                            <div style="display:flex; align-items:center; gap:0.4rem; margin-top:0.4rem;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C4783A"
                                                    stroke-width="2" stroke-linecap="round">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13 19.79 19.79 0 0 1 1.61 4.46 2 2 0 0 1 3.6 2.28h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                                <span style="font-size:0.8rem; color:#C4783A; font-weight:600;"
                                                    x-text="booking?.guest_phone ? booking.guest_phone : 'Belum ada nomor HP'"></span>
                                            </div>
                                        </template>
                                        <template x-if="booking?.status !== 'confirmed' && booking?.status !== 'completed'">
                                            <div style="display:flex; align-items:center; gap:0.4rem; margin-top:0.4rem;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF"
                                                    stroke-width="2" stroke-linecap="round">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13 19.79 19.79 0 0 1 1.61 4.46 2 2 0 0 1 3.6 2.28h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                                <span style="font-size:0.8rem; color:#9CA3AF; font-style:italic;">(Muncul
                                                    jika confirmed)</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <template x-if="booking?.notes_for_host">
                                    <div
                                        style="margin-top:0.875rem; background:#FFFBF5; border:1px solid #F5E6CC; border-left:3px solid #C4783A; padding:0.75rem 0.875rem; border-radius:0 8px 8px 0;">
                                        <div
                                            style="font-size:0.65rem; font-weight:700; color:#C4783A; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">
                                            📝 Catatan / Special Request</div>
                                        <div style="font-size:0.82rem; color:#3A3A3A; font-style:italic; line-height:1.6;"
                                            x-text="booking?.notes_for_host"></div>
                                    </div>
                                </template>
                            </div>

                            {{-- ── EXPERIENCE ── --}}
                            <div>
                                <div
                                    style="font-size:0.65rem; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.875rem;">
                                    Detail Experience</div>
                                <div style="background:#F7F3ED; border-radius:12px; padding:1rem 1.125rem;">
                                    <div style="font-size:0.9rem; font-weight:700; color:#1E3A2F; margin-bottom:0.75rem;"
                                        x-text="booking?.experience_title"></div>
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                                        <div>
                                            <div style="font-size:0.65rem; color:#9CA3AF; margin-bottom:0.2rem;">📅 Tanggal
                                            </div>
                                            <div style="font-size:0.82rem; font-weight:600; color:#1E3A2F;"
                                                x-text="booking?.tanggal"></div>
                                        </div>
                                        <div x-show="booking?.jam">
                                            <div style="font-size:0.65rem; color:#9CA3AF; margin-bottom:0.2rem;">🕐 Jam
                                                Mulai</div>
                                            <div style="font-size:0.82rem; font-weight:600; color:#1E3A2F;"
                                                x-text="booking?.jam"></div>
                                        </div>
                                        <div x-show="booking?.jam_selesai">
                                            <div style="font-size:0.65rem; color:#9CA3AF; margin-bottom:0.2rem;">🕓 Jam
                                                Selesai</div>
                                            <div style="font-size:0.82rem; font-weight:600; color:#1E3A2F;"
                                                x-text="booking?.jam_selesai"></div>
                                        </div>
                                        <div>
                                            <div style="font-size:0.65rem; color:#9CA3AF; margin-bottom:0.2rem;">👥 Peserta
                                            </div>
                                            <div style="font-size:0.82rem; font-weight:600; color:#1E3A2F;">
                                                <span x-text="booking?.jumlah_peserta"></span> orang
                                                <span x-show="booking?.is_private"
                                                    style="font-size:0.65rem; background:#FDF6EE; color:#C4783A; padding:0.1rem 0.4rem; border-radius:999px; margin-left:0.3rem; font-weight:700;">PRIVATE</span>
                                            </div>
                                        </div>
                                    </div>
                                    <template x-if="booking?.meeting_point">
                                        <div style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px solid #EDE7DC;">
                                            <div style="font-size:0.65rem; color:#9CA3AF; margin-bottom:0.2rem;">📍 Meeting
                                                Point / Lokasi Jemput</div>
                                            <div style="font-size:0.82rem; font-weight:500; color:#1E3A2F;"
                                                x-text="booking?.meeting_point"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- ── KEUANGAN ── --}}
                            <div>
                                <div
                                    style="font-size:0.65rem; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.875rem;">
                                    Rincian Keuangan</div>
                                <div style="border:1.5px solid #EDE7DC; border-radius:12px; overflow:hidden;">
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 1rem; border-bottom:1px solid #F0EBE3;">
                                        <span style="font-size:0.82rem; color:#7A7A6E;">Price per person</span>
                                        <span style="font-size:0.82rem; color:#1E3A2F;"
                                            x-text="booking?.harga_per_orang"></span>
                                    </div>
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 1rem; border-bottom:1px solid #F0EBE3;">
                                        <span style="font-size:0.82rem; color:#7A7A6E;">
                                            Total dibayar traveler
                                            <span x-show="booking?.jumlah_peserta > 1"
                                                style="font-size:0.72rem; color:#9CA3AF;"
                                                x-text="'(' + booking?.jumlah_peserta + ' orang)'"></span>
                                        </span>
                                        <span style="font-size:0.82rem; font-weight:600; color:#1E3A2F;"
                                            x-text="booking?.total_harga"></span>
                                    </div>
                                    <template x-if="booking?.discount">
                                        <div
                                            style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 1rem; border-bottom:1px solid #F0EBE3; background:#F9FFF9;">
                                            <span style="font-size:0.82rem; color:#2D5240;">
                                                Diskon
                                                <span x-show="booking?.coupon_code"
                                                    style="font-size:0.7rem; background:#EBF5EE; color:#2D5240; padding:0.1rem 0.4rem; border-radius:4px; margin-left:0.3rem;"
                                                    x-text="booking?.coupon_code"></span>
                                            </span>
                                            <span style="font-size:0.82rem; color:#2D5240;"
                                                x-text="'- ' + booking?.discount"></span>
                                        </div>
                                    </template>
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 1rem; border-bottom:1px solid #F0EBE3;">
                                        <span style="font-size:0.82rem; color:#9CA3AF;">Potongan platform CittaLoka</span>
                                        <span style="font-size:0.82rem; color:#9CA3AF;"
                                            x-text="'- ' + booking?.platform_fee"></span>
                                    </div>
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; padding:0.875rem 1rem; background:linear-gradient(135deg, #1E3A2F, #2D5240);">
                                        <div>
                                            <div
                                                style="font-size:0.65rem; font-weight:700; color:rgba(255,255,255,0.7); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.15rem;">
                                                Kamu Terima</div>
                                            <div style="font-size:0.72rem; color:rgba(255,255,255,0.5);">Setelah platform
                                                fee</div>
                                        </div>
                                        <span style="font-size:1.15rem; font-weight:800; color:white;"
                                            x-text="booking?.host_earning"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- ── TOMBOL AKSI ── --}}
                            <div style="display:flex; gap:0.75rem;">
                                <template x-if="booking?.status === 'confirmed' || booking?.status === 'completed'">
                                    <div style="flex:2; display:flex; gap:0.5rem;">
                                        <template x-if="booking?.guest_phone">
                                            <a :href="'https://wa.me/' + booking?.guest_phone?.replace(/[^0-9]/g,'')"
                                                target="_blank"
                                                style="flex:1; display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.7rem 1rem; background:#25D366; color:white; border-radius:10px; font-size:0.8rem; font-weight:600; text-decoration:none; transition:all 0.15s;"
                                                onmouseover="this.style.background='#1EBE5A'"
                                                onmouseout="this.style.background='#25D366'">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                                    <path
                                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                                </svg>
                                                WhatsApp
                                            </a>
                                        </template>
                                        <a :href="'mailto:' + booking?.guest_email"
                                            style="flex:1; display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.7rem 1rem; background:#1E3A2F; color:white; border-radius:10px; font-size:0.8rem; font-weight:600; text-decoration:none; transition:all 0.15s;"
                                            onmouseover="this.style.background='#2D5240'"
                                            onmouseout="this.style.background='#1E3A2F'">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                                <path
                                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                                <polyline points="22,6 12,13 2,6" />
                                            </svg>
                                            Chat / Email
                                        </a>
                                    </div>
                                </template>
                                <button @click="showDetailModal = false"
                                    style="flex:1; padding:0.7rem 1rem; border:1.5px solid #EDE7DC; border-radius:10px; background:white; font-size:0.85rem; font-weight:500; color:#4A4A4A; cursor:pointer; font-family:'DM Sans',sans-serif;"
                                    onmouseover="this.style.background='#F7F3ED'"
                                    onmouseout="this.style.background='white'">
                                    Tutup
                                </button>
                                <template x-if="booking?.can_file_complaint">
                                    <div style="flex:1; display:flex; flex-direction:column; gap:0.3rem;">
                                        <a :href="'{{ url('/dashboard/complaints') }}/' + booking?.kode_booking + '/create'"
                                            style="display:flex; align-items:center; justify-content:center; padding:0.7rem 1rem; background:white; color:#C0392B; border:1.5px solid #FECACA; border-radius:10px; font-size:0.8rem; font-weight:600; text-decoration:none; font-family:'DM Sans',sans-serif;">
                                            Ajukan Complaint
                                        </a>
                                        <template x-if="booking?.complaint_deadline">
                                            <span style="font-size:0.68rem; color:#9CA3AF; text-align:center;"
                                                x-text="'Batas: ' + booking?.complaint_deadline"></span>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!booking?.can_file_complaint && booking?.my_complaint_status">
                                    <div
                                        style="flex:1; text-align:center; padding:0.6rem 1rem; background:#F7F3ED; border:1px solid #EDE7DC; border-radius:10px; font-size:0.75rem; color:#1E3A2F;">
                                        <span x-text="'Complaint kamu: ' + booking?.my_complaint_status"></span>
                                    </div>
                                </template>
                                <template
                                    x-if="!booking?.can_file_complaint && !booking?.my_complaint_status && booking?.complaint_disabled_reason">
                                    <div x-data="{ showTip: false }" style="position:relative; flex:1;">
                                        <span @mouseenter="showTip = true" @mouseleave="showTip = false"
                                            style="display:flex; align-items:center; justify-content:center; padding:0.7rem 1rem; background:#F7F3ED; color:#B8B0A2; border:1.5px solid #EDE7DC; border-radius:10px; font-size:0.8rem; font-weight:600; font-family:'DM Sans',sans-serif; cursor:not-allowed; user-select:none;">
                                            Ajukan Complaint
                                        </span>
                                        <div x-show="showTip" x-transition.opacity
                                            style="position:absolute; bottom:100%; left:50%; transform:translateX(-50%); margin-bottom:0.5rem; background:#1E3A2F; color:white; padding:0.5rem 0.8rem; border-radius:6px; font-size:0.7rem; white-space:nowrap; z-index:10; pointer-events:none;"
                                            x-text="booking?.complaint_disabled_reason"></div>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>
                </div>
        </template>
    </div>

    </div>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

@endsection