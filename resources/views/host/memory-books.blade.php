@extends('layouts.dashboard')

@section('title', 'Memory Books')
@section('page-title', 'Memory Books')

@section('content')

@php use Illuminate\Support\Str; @endphp

<div style="display:flex; flex-direction:column; gap:1.5rem;">

    {{-- Flash messages --}}
    @if(session('success'))
        <div style="background:#EBF5EE; border:1px solid #B8DFC8; color:#2D5240; padding:0.75rem 1rem; border-radius:10px; font-size:0.875rem;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:10px; font-size:0.875rem;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Urgent Section --}}
    @if($urgent->isNotEmpty())
        <div style="background:#FDF6EE; border:1.5px solid #F0DFC0; border-radius:14px; overflow:hidden;">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid #F0DFC0; display:flex; align-items:center; gap:0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C4783A" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span style="font-size:0.875rem; font-weight:700; color:#C4783A;">Needs Attention ({{ $urgent->count() }})</span>
            </div>
            <div>
                @foreach($urgent as $mb)
                    @php
                        $hoursAgo = (int) now()->diffInHours($mb->updated_at);
                        $guestName = $mb->booking->user?->name ?? 'Guest';
                        $expTitle  = $mb->booking->experience_title_snapshot ?? 'Experience';
                        $tanggal   = \Carbon\Carbon::parse($mb->booking->tanggal_experience)->format('d M Y');
                    @endphp
                    <div style="padding:1rem 1.25rem; border-bottom:1px solid #F0DFC0; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                        <div>
                            <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F; margin-bottom:0.2rem;">
                                {{ $guestName }}
                            </div>
                            <div style="font-size:0.8rem; color:#7A7A6E;">
                                {{ Str::limit($expTitle, 50) }} · {{ $tanggal }}
                            </div>
                            <div style="font-size:0.75rem; color:#C4783A; margin-top:0.2rem;">
                                Waiting {{ $hoursAgo }} hours
                            </div>
                        </div>
                        <a href="{{ route('host.memory-books.fill', $mb->id) }}"
                            style="padding:0.5rem 1rem; background:#C4783A; color:white; border-radius:8px; font-size:0.8rem; font-weight:600; text-decoration:none; white-space:nowrap;">
                            Fill Now
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- All Memory Books --}}
    <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; overflow:hidden;">

        {{-- Header --}}
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #EFE7DC; display:flex; align-items:center; justify-content:space-between;">
            <div style="font-size:0.95rem; font-weight:700; color:#1E3A2F;">All Memory Books</div>
            <div style="font-size:0.8rem; color:#7A7A6E;">{{ $all->total() }} total</div>
        </div>

        {{-- Empty state --}}
        @if($all->isEmpty())
            <div style="padding:3rem; text-align:center;">
                <div style="font-size:2.5rem; margin-bottom:0.75rem;">📖</div>
                <div style="font-size:0.875rem; color:#9CA3AF; margin-bottom:0.3rem; font-weight:600;">No Memory Books yet</div>
                <div style="font-size:0.8rem; color:#9CA3AF;">Memory Books appear after a booking is completed.</div>
            </div>

        @else
            {{-- Table header --}}
            <div style="display:grid; grid-template-columns:1fr 160px 100px 120px; gap:0; padding:0.6rem 1.25rem; background:#F7F3ED; border-bottom:1px solid #EDE7DC;">
                @foreach(['Guest & Experience', 'Date', 'Status', 'Action'] as $col)
                    <div style="font-size:0.7rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.06em;">{{ $col }}</div>
                @endforeach
            </div>

            @foreach($all as $mb)
                @php
                    $guestName = $mb->booking->user?->name ?? 'Guest';
                    $expTitle  = $mb->booking->experience_title_snapshot ?? 'Experience';
                    $tanggal   = \Carbon\Carbon::parse($mb->booking->tanggal_experience)->format('d M Y');
                    $statusColor = match($mb->status) {
                        'sent'         => '#2D5240',
                        'pending_host' => '#C4783A',
                        'overdue'      => '#C0392B',
                        default        => '#7A7A6E',
                    };
                    $statusBg = match($mb->status) {
                        'sent'         => '#EBF5EE',
                        'pending_host' => '#FDF6EE',
                        'overdue'      => '#FEF2F2',
                        default        => '#F3F4F6',
                    };
                    $statusLabel = match($mb->status) {
                        'sent'         => 'Sent',
                        'pending_host' => 'Pending',
                        'overdue'      => 'Overdue',
                        'not_started'  => 'Not Started',
                        default        => ucfirst($mb->status),
                    };
                @endphp
                <div style="display:grid; grid-template-columns:1fr 160px 100px 120px; gap:0; padding:1rem 1.25rem; border-bottom:1px solid #F7F3ED; align-items:center;"
                    onmouseover="this.style.background='#FAFAF8'"
                    onmouseout="this.style.background='white'">

                    {{-- Guest & Experience --}}
                    <div>
                        <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F; margin-bottom:0.15rem;">
                            {{ $guestName }}
                        </div>
                        <div style="font-size:0.78rem; color:#7A7A6E;">
                            {{ Str::limit($expTitle, 48) }}
                        </div>
                    </div>

                    {{-- Date --}}
                    <div style="font-size:0.82rem; color:#4A4A4A;">{{ $tanggal }}</div>

                    {{-- Status --}}
                    <div>
                        <span style="font-size:0.68rem; font-weight:700; letter-spacing:0.06em; padding:0.25rem 0.65rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Action --}}
                    <div>
                        @if(in_array($mb->status, ['pending_host', 'overdue']))
                            <a href="{{ route('host.memory-books.fill', $mb->id) }}"
                                style="font-size:0.78rem; font-weight:600; color:#1E3A2F; text-decoration:underline;">
                                Fill Memory Book
                            </a>
                        @elseif($mb->status === 'sent')
                            <a href="{{ route('memory-book.show', $mb->booking->kode_booking) }}"
                                style="font-size:0.78rem; color:#7A7A6E; text-decoration:underline;">
                                View
                            </a>
                        @else
                            <span style="font-size:0.78rem; color:#9CA3AF;">—</span>
                        @endif
                    </div>

                </div>
            @endforeach

            {{-- Pagination --}}
            @if($all->hasPages())
                <div style="padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; border-top:1px solid #EDE7DC;">
                    <div style="font-size:0.8rem; color:#7A7A6E;">
                        Showing {{ $all->firstItem() }}–{{ $all->lastItem() }} of {{ $all->total() }}
                    </div>
                    <div style="display:flex; gap:0.4rem;">
                        @if($all->onFirstPage())
                            <span style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#D1D5DB; font-size:0.8rem;">‹</span>
                        @else
                            <a href="{{ $all->previousPageUrl() }}" style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#1E3A2F; font-size:0.8rem; text-decoration:none;">‹</a>
                        @endif
                        <span style="width:32px; height:32px; border-radius:6px; background:#1E3A2F; color:white; display:flex; align-items:center; justify-content:center; font-size:0.8rem; font-weight:600;">{{ $all->currentPage() }}</span>
                        @if($all->hasMorePages())
                            <a href="{{ $all->nextPageUrl() }}" style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#1E3A2F; font-size:0.8rem; text-decoration:none;">›</a>
                        @else
                            <span style="width:32px; height:32px; border-radius:6px; border:1.5px solid #EDE7DC; display:flex; align-items:center; justify-content:center; color:#D1D5DB; font-size:0.8rem;">›</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

</div>

@endsection