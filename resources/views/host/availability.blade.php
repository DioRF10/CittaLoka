@extends('layouts.dashboard')

@section('title', 'Availability')
@section('page-title', 'Manage Availability')

@section('content')

{{-- Success/Error --}}
@if(session('success'))
    <div style="background:#EBF5EE; border:1px solid #B8DFC8; color:#2D5240; padding:0.75rem 1rem; border-radius:8px; font-size:0.875rem; margin-bottom:1.25rem;">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="background:#FEF2F2; border:1px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:8px; font-size:0.875rem; margin-bottom:1.25rem;">{{ session('error') }}</div>
@endif

<div style="display:grid; grid-template-columns:1fr 380px; gap:1.5rem; align-items:start;">

    {{-- Kiri: Existing Availability --}}
    <div>
        {{-- Pilih Experience --}}
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1.25rem; margin-bottom:1.25rem;">
            <label style="font-size:0.72rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.5rem;">
                Select Experience
            </label>
            <form method="GET" action="{{ route('host.availability.index') }}">
                <select name="experience_id" onchange="this.form.submit()"
                    style="width:100%; padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.875rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; background:white;">
                    @foreach($experiences as $exp)
                        <option value="{{ $exp->id }}" {{ $selectedExpId == $exp->id ? 'selected' : '' }}>
                            {{ $exp->getJudul(app()->getLocale()) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Availability List --}}
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #EDE7DC;">
                <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:500; color:#1E3A2F;">
                    Upcoming Availability
                </h3>
            </div>

            @if($availabilities->isEmpty())
                <div style="padding:2.5rem; text-align:center;">
                    <div style="font-size:2rem; margin-bottom:0.75rem;">📅</div>
                    <div style="font-size:0.875rem; color:#9CA3AF;">No availability set yet. Add slots on the right.</div>
                </div>
            @else
                {{-- Group by date --}}
                @php
                    $grouped = $availabilities->groupBy(fn($a) => $a->date->format('Y-m-d'));
                @endphp

                @foreach($grouped as $date => $slots)
                    <div style="border-bottom:1px solid #F7F3ED;">
                        {{-- Date Header --}}
                        <div style="padding:0.75rem 1.5rem; background:#F7F3ED; display:flex; align-items:center; justify-content:space-between;">
                            <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F;">
                                {{ \Carbon\Carbon::parse($date)->locale('en')->isoFormat('ddd, D MMM YYYY') }}
                            </div>
                            <div style="font-size:0.75rem; color:#7A7A6E;">{{ $slots->count() }} slot(s)</div>
                        </div>

                        {{-- Time Slots --}}
                        @foreach($slots as $slot)
                            <div style="padding:0.75rem 1.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F7F3ED;">
                                <div style="display:flex; align-items:center; gap:1rem;">
                                    <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F; min-width:60px;">
                                        {{ \Carbon\Carbon::parse($slot->time)->format('H:i') }}
                                    </div>
                                    <div>
                                        <span style="font-size:0.8rem; color:#4A4A4A;">
                                            {{ $slot->getAvailableSlot() }}/{{ $slot->max_slot }} slots available
                                        </span>
                                        @if($slot->booked_slot > 0)
                                            <span style="font-size:0.72rem; color:#C4783A; margin-left:0.5rem;">
                                                · {{ $slot->booked_slot }} booked
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap:0.5rem;">
                                    {{-- Status indicator --}}
                                    <div style="width:8px; height:8px; border-radius:50%; background:{{ $slot->getAvailableSlot() > 0 ? '#2D5240' : '#C0392B' }};"></div>
                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('host.availability.delete', $slot->id) }}"
                                        onsubmit="return confirm('Hapus slot ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="width:28px; height:28px; border-radius:6px; border:1.5px solid #FECACA; background:white; color:#C0392B; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.15s;"
                                            onmouseover="this.style.background='#FEF2F2'"
                                            onmouseout="this.style.background='white'"
                                            {{ $slot->booked_slot > 0 ? 'disabled title=Tidak bisa hapus ada booking' : '' }}>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Kanan: Add Availability --}}
    <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1.5rem; position:sticky; top:1.5rem;" x-data="availabilityForm()">

        <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.2rem; font-weight:500; color:#1E3A2F; margin-bottom:1.25rem;">
            Add Availability
        </h3>

        <form method="POST" action="{{ route('host.availability.store') }}">
            @csrf
            <input type="hidden" name="experience_id" value="{{ $selectedExpId }}">

            {{-- Date --}}
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Date</label>
                <input type="date" name="date" min="{{ now()->toDateString() }}"
                    style="width:100%; padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.875rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; box-sizing:border-box;"
                    onfocus="this.style.borderColor='#1E3A2F'"
                    onblur="this.style.borderColor='#EDE7DC'">
            </div>

            {{-- Time Slots --}}
            <div style="margin-bottom:1rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                    <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em;">Time Slots</label>
                    <button type="button" @click="addSlot()"
                        style="font-size:0.75rem; color:#1E3A2F; background:none; border:none; cursor:pointer; font-weight:500; text-decoration:underline;">
                        + Add Slot
                    </button>
                </div>

                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    <template x-for="(slot, index) in slots" :key="index">
                        <div style="display:grid; grid-template-columns:1fr 1fr 32px; gap:0.5rem; align-items:center;">
                            <input type="time" :name="`times[${index}][time]`" x-model="slot.time"
                                style="padding:0.65rem 0.75rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.82rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif;">
                            <div style="position:relative;">
                                <input type="number" :name="`times[${index}][max_slot]`" x-model="slot.max_slot"
                                    min="1" max="50" placeholder="Slots"
                                    style="width:100%; padding:0.65rem 0.75rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.82rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; box-sizing:border-box;">
                            </div>
                            <button type="button" @click="removeSlot(index)" x-show="slots.length > 1"
                                style="width:32px; height:32px; border-radius:6px; border:1.5px solid #FECACA; background:white; color:#C0392B; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div style="display:flex; justify-content:space-between; margin-top:0.4rem;">
                    <span style="font-size:0.7rem; color:#9CA3AF;">Time · Max Slots</span>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit"
                style="width:100%; padding:0.85rem; background:#1E3A2F; color:white; border:none; border-radius:8px; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s;"
                onmouseover="this.style.background='#2D4A32'"
                onmouseout="this.style.background='#1E3A2F'">
                Save Availability
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function availabilityForm() {
    return {
        slots: [{ time: '09:00', max_slot: 5 }],
        addSlot() {
            this.slots.push({ time: '10:00', max_slot: 5 });
        },
        removeSlot(index) {
            if (this.slots.length > 1) this.slots.splice(index, 1);
        }
    }
}
</script>
@endpush
