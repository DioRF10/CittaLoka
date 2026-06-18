@extends('layouts.dashboard')

@section('title', 'Host Profile')
@section('page-title', 'My Profile')

@push('styles')
<style>
    .profile-tabs {
        display: flex;
        gap: 0;
        border-bottom: 1px solid #EDE7DC;
        margin-bottom: 1.5rem;
        background: white;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        border: 1px solid #EDE7DC;
    }
    .profile-tab {
        padding: 0.875rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #7A7A6E;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all 0.15s;
        background: none;
        border: none;
        font-family: 'DM Sans', sans-serif;
    }
    .profile-tab.active { color: #1E3A2F; border-bottom: 2px solid #1E3A2F; background: #F7F3ED; }
    .profile-tab:hover:not(.active) { color: #1E3A2F; background: #FAFAF8; }

    .form-section { background: white; border: 1px solid #EDE7DC; border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem; }
    .form-section-header { padding: 1.1rem 1.5rem; border-bottom: 1px solid #EDE7DC; background: #F7F3ED; display: flex; align-items: center; justify-content: space-between; }
    .form-section-title { font-family: 'Cormorant Garamond', serif; font-size: 1.1rem; font-weight: 500; color: #1E3A2F; }
    .form-section-body { padding: 1.5rem; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-label { font-size: 0.72rem; font-weight: 700; color: #7A7A6E; text-transform: uppercase; letter-spacing: 0.08em; }
    .form-input, .form-select, .form-textarea { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #EDE7DC; border-radius: 8px; font-size: 0.875rem; color: #1E3A2F; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color 0.15s; background: white; box-sizing: border-box; }
    .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: #1E3A2F; }
    .form-textarea { resize: vertical; min-height: 100px; line-height: 1.6; }

    /* Heritage Tree */
    .heritage-node {
        background: white;
        border: 1.5px solid #EDE7DC;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        position: relative;
    }
    .heritage-node-num {
        position: absolute;
        top: -12px;
        left: 1rem;
        background: #1E3A2F;
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
    }
    .heritage-connector {
        display: flex;
        justify-content: center;
        margin: -0.5rem 0;
        color: #EDE7DC;
        font-size: 1.5rem;
    }

    .btn-primary { display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #1E3A2F; color: white; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.2s; }
    .btn-primary:hover { background: #2D4A32; }
    .btn-secondary { display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: white; color: #1E3A2F; border: 1.5px solid #EDE7DC; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.2s; }
    .btn-secondary:hover { background: #F7F3ED; }
    .btn-danger { padding: 0.5rem 0.875rem; background: white; color: #C0392B; border: 1.5px solid #FECACA; border-radius: 8px; font-size: 0.8rem; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.2s; }
    .btn-danger:hover { background: #FEF2F2; }
</style>
@endpush

@section('content')

@php $locale = app()->getLocale(); @endphp

{{-- Success/Error --}}
@if(session('success'))
    <div style="background:#EBF5EE; border:1px solid #B8DFC8; color:#2D5240; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1.25rem;">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="background:#FEF2F2; border:1px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1.25rem;">{{ session('error') }}</div>
@endif

{{-- Tabs --}}
<div x-data="{ activeTab: '{{ request('tab', 'public') }}' }">

    <div class="profile-tabs">
        <button class="profile-tab" :class="activeTab === 'public' ? 'active' : ''" x-on:click="activeTab = 'public'">
            🌿 Public Profile
        </button>
        <button class="profile-tab" :class="activeTab === 'heritage' ? 'active' : ''" x-on:click="activeTab = 'heritage'">
            🌳 Heritage Tree
        </button>
        <button class="profile-tab" :class="activeTab === 'account' ? 'active' : ''" x-on:click="activeTab = 'account'">
            ⚙️ Account & Bank
        </button>
    </div>

    {{-- ══ TAB 1: Public Profile ══ --}}
    <div x-show="activeTab === 'public'">
        <form method="POST" action="{{ route('host.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="public">

            {{-- Avatar --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="form-section-title">Profile Photo</div>
                </div>
                <div class="form-section-body" x-data="{ preview: null }">
                    <div style="display:flex; align-items:center; gap:1.5rem;">
                        <div style="position:relative;">
                            <img :src="preview || '{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=1E3A2F&color=fff&size=200' }}'" alt=""
                                style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:2px solid #EDE7DC;">
                        </div>
                        <div>
                            <label style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; cursor:pointer; font-size:0.82rem; font-weight:500; color:#1E3A2F; transition:all 0.15s;"
                                onmouseover="this.style.background='#F7F3ED'" onmouseout="this.style.background='white'">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Upload Photo
                                <input type="file" name="avatar" accept="image/*" style="display:none;"
                                    x-on:change="preview = URL.createObjectURL($event.target.files[0])">
                            </label>
                            <div style="font-size:0.72rem; color:#9CA3AF; margin-top:0.4rem;">JPG, PNG — Max 2MB</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Basic Info --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="form-section-title">Basic Information</div>
                </div>
                <div class="form-section-body">
                    <div class="form-grid-2" style="margin-bottom:1.25rem;">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-input" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Village / Area</label>
                            <input type="text" name="village" class="form-input"
                                value="{{ $host->village }}" placeholder="e.g. Ubud, Gianyar">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-textarea" rows="4"
                            placeholder="Tell guests about yourself, your craft, and your heritage...">{{ $host->bio }}</textarea>
                        <span style="font-size:0.72rem; color:#9CA3AF;">Max 500 characters. This appears on your public profile.</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Video URL (60 seconds)</label>
                        <input type="url" name="video_url" class="form-input"
                            value="{{ $host->video_url }}" placeholder="https://youtube.com/... or https://vimeo.com/...">
                        <span style="font-size:0.72rem; color:#9CA3AF;">A short video introducing yourself to guests.</span>
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-primary">Save Public Profile</button>
            </div>
        </form>
    </div>

    {{-- ══ TAB 2: Heritage Tree ══ --}}
    <div x-show="activeTab === 'heritage'" x-data="heritageTree()">

        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-title">Heritage Tree</div>
                <span style="font-size:0.78rem; color:#7A7A6E;">Your lineage of skill & knowledge</span>
            </div>
            <div class="form-section-body">
                <p style="font-size:0.82rem; color:#7A7A6E; margin-bottom:1.5rem; line-height:1.6;">
                    Document the lineage of your craft — who taught you, and who taught them. This helps guests understand the depth and authenticity of your expertise.
                </p>

                {{-- Existing nodes from DB --}}
                @foreach($heritageTree as $node)
                    <div class="heritage-node">
                        <div class="heritage-node-num">Generation {{ $node->generation_number ?? $loop->iteration }}</div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:0.75rem;">
                            <div>
                                <div style="font-size:0.72rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Teacher / Mentor</div>
                                <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $node->teacher_name }}</div>
                            </div>
                            <div>
                                <div style="font-size:0.72rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Year Learned</div>
                                <div style="font-size:0.875rem; color:#1E3A2F;">{{ $node->learned_from_year ?? '—' }}</div>
                            </div>
                        </div>
                        @if($node->skill_description)
                            <div style="font-size:0.82rem; color:#4A4A4A; line-height:1.6;">{{ $node->skill_description }}</div>
                        @endif
                        <form method="POST" action="{{ route('host.profile.heritage.delete', $node->id) }}" style="margin-top:0.75rem;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus node ini?')">Delete</button>
                        </form>
                    </div>
                    @if(!$loop->last)
                        <div class="heritage-connector">↑</div>
                    @endif
                @endforeach

                {{-- Add new node --}}
                <div style="border-top: 1px dashed #EDE7DC; padding-top:1.5rem; margin-top:{{ $heritageTree->count() > 0 ? '1.5rem' : '0' }};">
                    <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F; margin-bottom:1rem;">
                        + Add {{ $heritageTree->count() > 0 ? 'Another' : 'First' }} Generation
                    </div>
                    <form method="POST" action="{{ route('host.profile.heritage.store') }}">
                        @csrf
                        <div class="form-grid-2" style="margin-bottom:1rem;">
                            <div class="form-group">
                                <label class="form-label">Teacher / Mentor Name <span style="color:#C0392B;">*</span></label>
                                <input type="text" name="teacher_name" class="form-input"
                                    placeholder="e.g. I Made Suweca (Grandfather)">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Year Learned</label>
                                <input type="number" name="learned_from_year" class="form-input"
                                    placeholder="e.g. 1985" min="1900" max="{{ date('Y') }}">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:1rem;">
                            <label class="form-label">Skill Description</label>
                            <textarea name="skill_description" class="form-textarea" rows="2"
                                placeholder="What did you learn from them? What makes their teaching unique?"></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom:1rem;">
                            <label class="form-label">Generation Number</label>
                            <input type="number" name="generation_number" class="form-input"
                                value="{{ $heritageTree->count() + 1 }}" min="1" style="max-width:120px;">
                        </div>
                        <button type="submit" class="btn-primary">Add to Heritage Tree</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TAB 3: Account & Bank ══ --}}
    <div x-show="activeTab === 'account'">
        <form method="POST" action="{{ route('host.profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="tab" value="account">

            {{-- Account Info --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="form-section-title">Account Information</div>
                </div>
                <div class="form-section-body">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" value="{{ auth()->user()->email }}" disabled
                                style="background:#F7F3ED; color:#9CA3AF;">
                            <span style="font-size:0.72rem; color:#9CA3AF;">Email cannot be changed here.</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">KTP Status</label>
                            <div style="padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; background:#F7F3ED;">
                                @php
                                    $ktpColor = match($host->ktp_status) {
                                        'verified' => '#2D5240', 'pending' => '#C4783A',
                                        'rejected' => '#C0392B', default => '#7A7A6E',
                                    };
                                    $ktpBg = match($host->ktp_status) {
                                        'verified' => '#EBF5EE', 'pending' => '#FDF6EE',
                                        'rejected' => '#FEF2F2', default => '#F3F4F6',
                                    };
                                @endphp
                                <span style="font-size:0.72rem; font-weight:700; letter-spacing:0.08em; padding:0.2rem 0.6rem; border-radius:999px; background:{{ $ktpBg }}; color:{{ $ktpColor }};">
                                    {{ strtoupper($host->ktp_status) }}
                                </span>
                                @if($host->ktp_status === 'rejected' && $host->ktp_rejection_note)
                                    <div style="font-size:0.75rem; color:#C0392B; margin-top:0.5rem;">{{ $host->ktp_rejection_note }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bank Info --}}
            <div class="form-section">
                <div class="form-section-header">
                    <div class="form-section-title">Bank Account</div>
                    <span style="font-size:0.78rem; color:#7A7A6E;">For receiving your earnings payouts</span>
                </div>
                <div class="form-section-body">
                    <div class="form-grid-2" style="margin-bottom:1.25rem;">
                        <div class="form-group">
                            <label class="form-label">Bank Name</label>
                            <select name="bank_name" class="form-select">
                                <option value="">Select bank...</option>
                                @foreach(['BCA','BNI','BRI','Mandiri','CIMB Niaga','Danamon','Permata','BTN'] as $bank)
                                    <option value="{{ $bank }}" {{ $host->bank_name === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Holder Name</label>
                            <input type="text" name="bank_account_name" class="form-input"
                                value="{{ $host->bank_account_name }}" placeholder="As per bank records">
                        </div>
                    </div>
                    <div class="form-group" style="max-width:300px;">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="bank_account_number" class="form-input"
                            value="{{ $host->bank_account_number }}" placeholder="e.g. 1234567890">
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-primary">Save Account & Bank</button>
            </div>
        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
function heritageTree() {
    return {}
}
</script>
@endpush