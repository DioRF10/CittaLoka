@extends('layouts.app')

@section('title', 'My Profile | CittaLoka')

@section('content')

<style>
    .profile-header-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 2.75rem;
        font-weight: 500;
        color: #1a2e1c;
        line-height: 1.1;
    }
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
    .profile-tab.active { color: #1a2e1c; border-bottom: 2px solid #1a2e1c; background: #F7F3ED; }
    .profile-tab:hover:not(.active) { color: #1a2e1c; background: #FAFAF8; }

    .form-section { background: white; border: 1px solid #EDE7DC; border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem; }
    .form-section-header { padding: 1.1rem 1.5rem; border-bottom: 1px solid #EDE7DC; background: #F7F3ED; }
    .form-section-title { font-family: 'Cormorant Garamond', serif; font-size: 1.1rem; font-weight: 500; color: #1a2e1c; }
    .form-section-body { padding: 1.5rem; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-label { font-size: 0.72rem; font-weight: 700; color: #7A7A6E; text-transform: uppercase; letter-spacing: 0.08em; }
    .form-input, .form-select { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #EDE7DC; border-radius: 8px; font-size: 0.875rem; color: #1a2e1c; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color 0.15s; background: white; box-sizing: border-box; }
    .form-input:focus, .form-select:focus { border-color: #1a2e1c; }
    .form-input:disabled { background: #F7F3ED; color: #9CA3AF; cursor: not-allowed; }

    .btn-primary { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #1a2e1c; color: white; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all 0.2s; }
    .btn-primary:hover { background: #2D4A32; }
</style>

<div class="max-w-4xl mx-auto px-6 py-12">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="profile-header-title">My Profile</h1>
        <p style="font-size:0.9rem; color:#6B7280; margin-top:0.5rem;">Manage your account information.</p>
    </div>

    {{-- Success/Error --}}
    @if(session('success'))
        <div style="background:#EBF5EE; border:1px solid #B8DFC8; color:#2D5240; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1.25rem;">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1.25rem;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div x-data="{ activeTab: '{{ request('tab', 'public') }}' }">

        <div class="profile-tabs">
            <button class="profile-tab" :class="activeTab === 'public' ? 'active' : ''" x-on:click="activeTab = 'public'">
                Public Profile
            </button>
            <button class="profile-tab" :class="activeTab === 'contact' ? 'active' : ''" x-on:click="activeTab = 'contact'">
                Contach
            </button>
            <button class="profile-tab" :class="activeTab === 'security' ? 'active' : ''" x-on:click="activeTab = 'security'">
                Security
            </button>
        </div>

        {{-- ══ TAB 1: Profil Publik ══ --}}
        <div x-show="activeTab === 'public'">
            <form method="POST" action="{{ route('my-profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="public">

                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-title">Profile Picture</div>
                    </div>
                    <div class="form-section-body" x-data="{ preview: null }">
                        <div style="display:flex; align-items:center; gap:1.5rem; margin-bottom:1.5rem;">
                            <img :src="preview || '{{ $user->avatarUrl() }}'" alt=""
                                style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:2px solid #EDE7DC;">
                            <label style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; cursor:pointer; font-size:0.82rem; font-weight:500; color:#1a2e1c;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Change Photo
                                <input type="file" name="avatar" accept="image/png, image/jpeg"
                                    style="display:none;"
                                    @change="preview = URL.createObjectURL($event.target.files[0])">
                            </label>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Language</label>
                                <select name="locale" class="form-select">
                                    <option value="id" {{ $user->locale === 'id' ? 'selected' : '' }}>🇮🇩 Bahasa Indonesia</option>
                                    <option value="en" {{ $user->locale === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </form>
        </div>

        {{-- ══ TAB 2: Kontak ══ --}}
        <div x-show="activeTab === 'contact'">
            <form method="POST" action="{{ route('my-profile.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="contact">

                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-title">Informasi Kontak</div>
                    </div>
                    <div class="form-section-body">
                        <div class="form-group" style="margin-bottom:1.25rem;">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" value="{{ $user->email }}" disabled>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Kode Negara</label>
                                <input type="text" name="country_code" class="form-input" placeholder="+62"
                                    value="{{ old('country_code', $user->country_code) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="phone_number" class="form-input" placeholder="812xxxxxxx"
                                    value="{{ old('phone_number', $user->phone_number) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </form>
        </div>

        {{-- ══ TAB 3: Keamanan ══ --}}
        <div x-show="activeTab === 'security'">
            <form method="POST" action="{{ route('my-profile.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="security">

                <div class="form-section">
                    <div class="form-section-header">
                        <div class="form-section-title">Ganti Password</div>
                    </div>
                    <div class="form-section-body">
                        <div class="form-group" style="margin-bottom:1.25rem;">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-input" required>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-input" required minlength="8">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-input" required minlength="8">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Ganti Password</button>
            </form>
        </div>

    </div>
</div>

@endsection