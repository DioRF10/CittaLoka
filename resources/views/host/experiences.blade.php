@extends('layouts.dashboard')

@section('title', 'My Experiences')
@section('page-title', 'Manage Experiences')

@section('content')

<div x-data="{
    showDeleteModal: false,
    deleteId: null,
    deleteTitle: '',
    confirmDelete(id, title) {
        this.deleteId = id;
        this.deleteTitle = title;
        this.showDeleteModal = true;
    }
}">

@php $locale = app()->getLocale(); @endphp

{{-- Success/Error --}}
@if(session('success'))
    <div style="background:#EBF5EE; border:1px solid #B8DFC8; color:#2D5240; padding:0.75rem 1rem; border-radius:8px; font-size:0.875rem; margin-bottom:1.25rem;">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="background:#FEF2F2; border:1px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:8px; font-size:0.875rem; margin-bottom:1.25rem;">{{ session('error') }}</div>
@endif

{{-- Stat Cards --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.75rem;">
    @php
        $statCards = [
            ['label' => 'Active', 'value' => $stats['active'], 'sub' => 'Live listings', 'color' => '#2D5240', 'bg' => '#EBF5EE'],
            ['label' => 'Draft', 'value' => $stats['draft'], 'sub' => 'In progress', 'color' => '#7A7A6E', 'bg' => '#F3F4F6'],
            ['label' => 'Pending Review', 'value' => $stats['pending'], 'sub' => 'Awaiting approval', 'color' => '#C4783A', 'bg' => '#FDF6EE'],
            ['label' => 'Rejected', 'value' => $stats['rejected'], 'sub' => 'Issues found', 'color' => '#C0392B', 'bg' => '#FEF2F2'],
        ];
    @endphp
    @foreach($statCards as $card)
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1.25rem;">
            <div style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">{{ $card['label'] }}</div>
            <div style="font-size:2rem; font-weight:700; color:{{ $card['color'] }}; font-family:'DM Sans',sans-serif; line-height:1; margin-bottom:0.3rem;">{{ $card['value'] }}</div>
            <div style="font-size:0.75rem; color:#9CA3AF;">{{ $card['sub'] }}</div>
        </div>
    @endforeach
</div>

{{-- Filter + Actions --}}
<div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">

    <div style="padding:1rem 1.5rem; border-bottom:1px solid #EDE7DC; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">

        {{-- Filter Tabs --}}
        <div style="display:flex; gap:0.4rem;">
            @foreach([['all','All'],['active','Active'],['draft','Draft'],['pending','Pending'],['inactive','Inactive']] as [$val,$label])
                <a href="{{ route('host.experiences.index', ['filter' => $val]) }}"
                    style="padding:0.4rem 0.875rem; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none; transition:all 0.15s;
                        {{ $filter === $val ? 'background:#1E3A2F; color:white;' : 'background:#F7F3ED; color:#4A4A4A;' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div style="display:flex; align-items:center; gap:0.75rem;">
            {{-- Sort --}}
            <select style="padding:0.45rem 0.875rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.8rem; color:#1E3A2F; background:white; outline:none; font-family:'DM Sans',sans-serif;">
                <option>Sort by: Newest</option>
                <option>Sort by: Oldest</option>
                <option>Sort by: Rating</option>
            </select>

            {{-- Create Button --}}
            <a href="{{ route('host.experiences.create') }}"
                style="display:flex; align-items:center; gap:0.4rem; padding:0.5rem 1rem; background:#1E3A2F; color:white; border-radius:8px; font-size:0.8rem; font-weight:500; text-decoration:none; transition:all 0.2s;"
                onmouseover="this.style.background='#2D4A32'"
                onmouseout="this.style.background='#1E3A2F'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Create New Experience
            </a>
        </div>
    </div>

    {{-- Table Header --}}
    <div style="display:grid; grid-template-columns:60px 1fr 120px 80px 80px 120px 100px; gap:0; padding:0.75rem 1.5rem; background:#F7F3ED; border-bottom:1px solid #EDE7DC;">
        @foreach(['Photo','Experience','Status','Bookings','Rating','Earnings','Actions'] as $col)
            <div style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.06em;">{{ $col }}</div>
        @endforeach
    </div>

    {{-- Table Rows --}}
    @if($experiences->isEmpty())
        <div style="padding:3rem; text-align:center;">
            <div style="font-size:2rem; margin-bottom:0.75rem;">🌿</div>
            <div style="font-size:0.875rem; color:#9CA3AF; margin-bottom:1rem;">No experiences yet</div>
            <a href="{{ route('host.experiences.create') }}" style="padding:0.6rem 1.25rem; background:#1E3A2F; color:white; border-radius:8px; font-size:0.82rem; font-weight:500; text-decoration:none;">
                Create Your First Experience
            </a>
        </div>
    @else
        @foreach($experiences as $exp)
            @php
                $cover = $exp->photos->where('is_cover', true)->first() ?? $exp->photos->first();
                $judul = $exp->getJudul($locale);
                $statusColor = match($exp->status) {
                    'active'   => '#2D5240', 'draft'    => '#7A7A6E',
                    'pending'  => '#C4783A', 'rejected' => '#C0392B',
                    'inactive' => '#9CA3AF', default    => '#7A7A6E',
                };
                $statusBg = match($exp->status) {
                    'active'   => '#EBF5EE', 'draft'    => '#F3F4F6',
                    'pending'  => '#FDF6EE', 'rejected' => '#FEF2F2',
                    'inactive' => '#F9FAFB', default    => '#F3F4F6',
                };
                $totalBookings = \App\Models\Booking::where('experience_id', $exp->id)->count();
                $totalEarnings = \App\Models\Booking::where('experience_id', $exp->id)->where('status', 'completed')->sum('host_earning');
            @endphp
            <div style="display:grid; grid-template-columns:60px 1fr 120px 80px 80px 120px 100px; gap:0; padding:1rem 1.5rem; border-bottom:1px solid #F7F3ED; align-items:center;"
                onmouseover="this.style.background='#FAFAF8'"
                onmouseout="this.style.background='white'">

                {{-- Foto --}}
                <div style="width:48px; height:40px; border-radius:6px; overflow:hidden; flex-shrink:0;">
                    @if($cover)
                        <img src="{{ $cover->url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="width:100%; height:100%; background:linear-gradient(135deg,#2D5240,#C4A882);"></div>
                    @endif
                </div>

                {{-- Info --}}
                <div style="padding-right:1rem;">
                    <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F; margin-bottom:0.2rem;">{{ $judul }}</div>
                    <div style="font-size:0.75rem; color:#7A7A6E;">
                        {{ $exp->kabupaten ?? $exp->lokasi_nama }} · {{ $exp->getDurasiFormatted() }}
                    </div>
                    @if($exp->status === 'rejected' && $exp->admin_note)
                        <a href="{{ route('host.experiences.create') }}" style="font-size:0.72rem; color:#C0392B; text-decoration:underline;">View Feedback</a>
                    @endif
                </div>

                {{-- Status --}}
                <div>
                    <span style="font-size:0.68rem; font-weight:700; letter-spacing:0.06em; padding:0.25rem 0.65rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                        {{ strtoupper($exp->status) }}
                    </span>
                </div>

                {{-- Bookings --}}
                <div style="font-size:0.875rem; color:{{ $totalBookings > 0 ? '#1E3A2F' : '#9CA3AF' }}; font-weight:{{ $totalBookings > 0 ? '500' : '400' }};">
                    {{ $totalBookings > 0 ? $totalBookings : '—' }}
                </div>

                {{-- Rating --}}
                <div style="font-size:0.875rem; color:#1E3A2F;">
                    @if($exp->rating_avg > 0)
                        <span style="color:#C4783A;">★</span> {{ number_format($exp->rating_avg, 1) }}
                    @else
                        <span style="color:#9CA3AF;">—</span>
                    @endif
                </div>

                {{-- Earnings --}}
                <div style="font-size:0.875rem; color:{{ $totalEarnings > 0 ? '#1E3A2F' : '#9CA3AF' }}; font-weight:{{ $totalEarnings > 0 ? '500' : '400' }};">
                    {{ $totalEarnings > 0 ? 'Rp ' . number_format($totalEarnings, 0, ',', '.') : 'Rp 0' }}
                </div>

                {{-- Actions --}}
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    {{-- Edit --}}
                    <a href="{{ route('host.experiences.edit', $exp->id) }}" title="Edit"
                        style="width:30px; height:30px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#7A7A6E; text-decoration:none; transition:all 0.15s;"
                        onmouseover="this.style.background='#F7F3ED'; this.style.color='#1E3A2F'"
                        onmouseout="this.style.background='white'; this.style.color='#7A7A6E'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </a>
                    {{-- View --}}
                    <a href="{{ route('experiences.show', $exp->slug) }}" target="_blank" title="View"
                        style="width:30px; height:30px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#7A7A6E; text-decoration:none; transition:all 0.15s;"
                        onmouseover="this.style.background='#F7F3ED'; this.style.color='#1E3A2F'"
                        onmouseout="this.style.background='white'; this.style.color='#7A7A6E'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                    {{-- Delete --}}
                    <button title="Hapus"
                        @click="confirmDelete({{ $exp->id }}, '{{ addslashes($judul) }}')"
                        style="width:30px; height:30px; border-radius:6px; border:1.5px solid #FECACA; background:white; display:flex; align-items:center; justify-content:center; color:#C0392B; cursor:pointer; transition:all 0.15s;"
                        onmouseover="this.style.background='#FEF2F2'"
                        onmouseout="this.style.background='white'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    </button>
                </div>

            </div>
        @endforeach

        {{-- Pagination --}}
        @if($experiences->hasPages())
            <div style="padding:1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; border-top:1px solid #EDE7DC;">
                <div style="font-size:0.8rem; color:#7A7A6E;">
                    Showing {{ $experiences->firstItem() }}–{{ $experiences->lastItem() }} of {{ $experiences->total() }} experiences
                </div>
                <div style="display:flex; gap:0.4rem;">
                    @if($experiences->onFirstPage())
                        <span style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#D1D5DB; font-size:0.8rem;">‹</span>
                    @else
                        <a href="{{ $experiences->previousPageUrl() }}" style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#1E3A2F; font-size:0.8rem; text-decoration:none;">‹</a>
                    @endif
                    <span style="width:32px; height:32px; border-radius:6px; background:#1E3A2F; color:white; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:600;">{{ $experiences->currentPage() }}</span>
                    @if($experiences->hasMorePages())
                        <a href="{{ $experiences->nextPageUrl() }}" style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#1E3A2F; font-size:0.8rem; text-decoration:none;">›</a>
                    @else
                        <span style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#D1D5DB; font-size:0.8rem;">›</span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>

{{-- Delete Confirmation Modal --}}
<div x-show="showDeleteModal"
    style="position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center;"
    x-cloak>
    {{-- Backdrop --}}
    <div style="position:absolute; inset:0; background:rgba(0,0,0,0.45); backdrop-filter:blur(3px);"
        @click="showDeleteModal = false"></div>

    {{-- Modal Box --}}
    <div style="position:relative; background:white; border-radius:16px; padding:2rem; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.18); margin:1rem;"
        x-show="showDeleteModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">

        {{-- Icon --}}
        <div style="width:52px; height:52px; background:#FEF2F2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C0392B" stroke-width="2" stroke-linecap="round">
                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
            </svg>
        </div>

        <h3 style="text-align:center; font-size:1.05rem; font-weight:700; color:#1E3A2F; margin:0 0 0.5rem;">Hapus Experience?</h3>
        <p style="text-align:center; font-size:0.85rem; color:#7A7A6E; margin:0 0 0.35rem;">
            Experience berikut akan dihapus secara permanen:
        </p>
        <p style="text-align:center; font-size:0.85rem; font-weight:600; color:#1E3A2F; margin:0 0 1.5rem;" x-text="'\"' + deleteTitle + '\"'"></p>
        <p style="text-align:center; font-size:0.78rem; color:#C0392B; margin:0 0 1.5rem;">&#9888; Tindakan ini tidak bisa dibatalkan.</p>

        <div style="display:flex; gap:0.75rem;">
            <button @click="showDeleteModal = false"
                style="flex:1; padding:0.65rem; border:1.5px solid #EDE7DC; border-radius:8px; background:white; font-size:0.875rem; font-weight:500; color:#4A4A4A; cursor:pointer;"
                onmouseover="this.style.background='#F7F3ED'"
                onmouseout="this.style.background='white'">
                Batal
            </button>
            <form :action="'{{ url('dashboard/experiences') }}/' + deleteId" method="POST" style="flex:1;">
                @csrf
                @method('DELETE')
                <button type="submit"
                    style="width:100%; padding:0.65rem; border:none; border-radius:8px; background:#C0392B; font-size:0.875rem; font-weight:600; color:white; cursor:pointer;"
                    onmouseover="this.style.background='#A93226'"
                    onmouseout="this.style.background='#C0392B'">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

</div>{{-- /x-data wrapper --}}

<style>
[x-cloak] { display: none !important; }
</style>

@endsection
