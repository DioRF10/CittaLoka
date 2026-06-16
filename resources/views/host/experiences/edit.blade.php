@extends('layouts.dashboard')

@section('title', 'Edit Experience')
@section('page-title', 'Edit Experience')

@push('styles')
<style>
    /* Typography & Colors */
    :root {
        --primary: #1E3A2F;
        --primary-light: #2D5240;
        --primary-soft: #EBF5EE;
        --accent: #C0392B;
        --cream: #F7F3ED;
        --border: #E5E7EB;
        --text-main: #111827;
        --text-muted: #6B7280;
    }

    .form-container {
        max-width: 860px;
        margin: 0 auto;
        padding-bottom: 2rem;
    }

    /* Cards */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .form-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border);
        background: #FAFAFA;
    }

    .form-card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
    }

    .form-card-desc {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .form-card-body {
        padding: 2rem;
    }

    /* Form Controls */
    .form-grid { display: grid; gap: 1.5rem; }
    .form-grid-2 { grid-template-columns: repeat(2, 1fr); }
    .form-grid-3 { grid-template-columns: repeat(3, 1fr); }

    .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
    
    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-main);
    }
    
    .form-label span.req { color: var(--accent); }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: 0.95rem;
        color: var(--text-main);
        font-family: inherit;
        transition: all 0.2s;
        background: #F9FAFB;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
        line-height: 1.5;
    }

    /* Toggle Switches (Radio) */
    .segmented-control {
        display: flex;
        background: #F3F4F6;
        padding: 0.375rem;
        border-radius: 12px;
        gap: 0.375rem;
    }

    .segment-label {
        flex: 1;
        text-align: center;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }

    .segment-label input { display: none; }

    .segment-label:has(input:checked) {
        background: white;
        color: var(--primary);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Dynamic Lists */
    .dynamic-list { display: flex; flex-direction: column; gap: 0.75rem; }
    .dynamic-item {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    .btn-remove {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        border: 1.5px solid #FCA5A5;
        background: #FEF2F2;
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .btn-remove:hover { background: var(--accent); color: white; border-color: var(--accent); }
    
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 0;
        color: var(--primary);
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        background: none;
        border: none;
    }
    .btn-add:hover { color: var(--primary-light); text-decoration: underline; }

    /* Photo Upload */
    .upload-zone {
        border: 2px dashed #D1D5DB;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        background: #F9FAFB;
        transition: all 0.2s;
        margin-top: 1rem;
    }
    .upload-zone:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
    }
    .upload-icon {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        color: var(--primary);
    }
    
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .photo-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .photo-item img { width: 100%; height: 100%; object-fit: cover; }
    .photo-item.is-cover { border-color: var(--primary); }
    .photo-item.is-cover::after {
        content: '';
        position: absolute;
        inset: 0;
        border: 4px solid var(--primary);
        border-radius: 12px;
        pointer-events: none;
    }
    
    .badge-cover {
        position: absolute;
        bottom: 0.5rem;
        left: 0.5rem;
        background: var(--primary);
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .btn-remove-photo {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255,255,255,0.9);
        color: var(--accent);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s;
        z-index: 10;
    }
    .btn-remove-photo:hover { background: var(--accent); color: white; }

    /* Bottom Navigation */
    .bottom-nav {
        position: sticky;
        bottom: 0;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        border-top: 1px solid var(--border);
        padding: 1.25rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 2rem;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.03);
        z-index: 50;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }
    
    .btn-primary { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(30,58,47,0.2); }
    .btn-primary:hover { background: var(--primary-light); transform: translateY(-1px); }
    
    .btn-outline { background: white; color: var(--text-main); border: 1.5px solid var(--border); }
    .btn-outline:hover { background: #F9FAFB; border-color: #D1D5DB; }

    /* Map */
    #map {
        height: 300px;
        width: 100%;
        border-radius: 12px;
        border: 1.5px solid var(--border);
        margin-bottom: 1.5rem;
        z-index: 10;
    }

    @media (max-width: 768px) {
        .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
        .form-card-body { padding: 1.5rem; }
        .bottom-nav { padding: 1rem; flex-direction: column; gap: 1rem; }
        .bottom-nav > div { width: 100%; display: flex; justify-content: space-between; }
        .bottom-nav .btn-primary { width: 100%; justify-content: center; }
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@section('content')

@php
    $locale = app()->getLocale();
    $judulArr = is_string($experience->judul) ? json_decode($experience->judul, true) : ($experience->judul ?? []);
    $deskArr  = is_string($experience->deskripsi) ? json_decode($experience->deskripsi, true) : ($experience->deskripsi ?? []);
    $whatYouDoData = $experience->getWhatYouDo();
    $includedData  = $experience->getIncluded();
    $notIncludedData = $experience->getNotIncluded();
@endphp

<div class="form-container" x-data="editForm()">

    {{-- Status Banner --}}
    @if($experience->status === 'rejected' && $experience->admin_note)
        <div style="background:#FEF2F2; border:1.5px solid #FCA5A5; border-radius:16px; padding:1.25rem; margin-bottom:1.5rem; box-shadow: 0 4px 12px rgba(220,38,38,0.05);">
            <div style="font-size:1rem; font-weight:700; color:#991B1B; margin-bottom:0.5rem; display:flex; align-items:center; gap:0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Rejected by Admin
            </div>
            <p style="font-size:0.9rem; color:#B91C1C; margin:0; line-height:1.6;">{{ $experience->admin_note }}</p>
        </div>
    @endif

    {{-- Success/Error --}}
    @if(session('success'))
        <div style="background:#F0FDF4; border:1.5px solid #BBF7D0; color:#166534; padding:1rem 1.25rem; border-radius:12px; margin-bottom:1.5rem; font-weight:500; display:flex; align-items:center; gap:0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:#FEF2F2; border:1.5px solid #FCA5A5; color:#991B1B; padding:1.25rem; border-radius:16px; margin-bottom:1.5rem; box-shadow: 0 4px 12px rgba(220,38,38,0.05);">
            <div style="font-weight:700; margin-bottom:0.5rem; display:flex; align-items:center; gap:0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Please correct the following errors:
            </div>
            <ul style="margin:0; padding-left:1.5rem; font-size:0.9rem; gap:0.25rem; display:flex; flex-direction:column;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('host.experiences.update', $experience->id) }}" enctype="multipart/form-data" id="editExperienceForm" @submit="prepareSubmit">
        @csrf
        @method('PUT')

        {{-- Hidden input for real file submission --}}
        <input type="file" name="photos[]" multiple x-ref="finalPhotosInput" style="display:none;">

        {{-- ── Basic Info ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">Basic Information</h2>
            </div>
            <div class="form-card-body">
                <div class="form-grid form-grid-2" style="margin-bottom:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Title (English) <span class="req">*</span></label>
                        <input type="text" name="judul_en" class="form-control" value="{{ old('judul_en', $judulArr['en'] ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Judul (Indonesia) <span class="req">*</span></label>
                        <input type="text" name="judul_id" class="form-control" value="{{ old('judul_id', $judulArr['id'] ?? '') }}" required>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">Category <span class="req">*</span></label>
                    <select name="category_id" class="form-control" required>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('category_id', $experience->category_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->getNama($locale) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Description (English)</label>
                        <textarea name="deskripsi_en" class="form-control">{{ old('deskripsi_en', $deskArr['en'] ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi (Indonesia)</label>
                        <textarea name="deskripsi_id" class="form-control">{{ old('deskripsi_id', $deskArr['id'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Details ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">Duration, Capacity & Environment</h2>
            </div>
            <div class="form-card-body">
                <div class="form-grid form-grid-3" style="margin-bottom:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Duration (minutes) <span class="req">*</span></label>
                        <input type="number" name="durasi_menit" class="form-control" value="{{ old('durasi_menit', $experience->durasi_menit) }}" min="30" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Min. Guests <span class="req">*</span></label>
                        <input type="number" name="kapasitas_min" class="form-control" value="{{ old('kapasitas_min', $experience->kapasitas_min) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max. Guests <span class="req">*</span></label>
                        <input type="number" name="kapasitas_max" class="form-control" value="{{ old('kapasitas_max', $experience->kapasitas_max) }}" min="1" required>
                    </div>
                </div>
                <div class="form-grid form-grid-2" style="margin-bottom:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Environment</label>
                        <div class="segmented-control">
                            <label class="segment-label">
                                <input type="radio" name="is_indoor" value="0" {{ old('is_indoor', $experience->is_indoor ? '1' : '0') == '0' ? 'checked' : '' }}>
                                🌿 Outdoor
                            </label>
                            <label class="segment-label">
                                <input type="radio" name="is_indoor" value="1" {{ old('is_indoor', $experience->is_indoor ? '1' : '0') == '1' ? 'checked' : '' }}>
                                🏠 Indoor
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Seasonality</label>
                        <div class="segmented-control">
                            <label class="segment-label">
                                <input type="radio" name="is_seasonal" value="0" {{ old('is_seasonal', $experience->is_seasonal ? '1' : '0') == '0' ? 'checked' : '' }}>
                                📅 Year-round
                            </label>
                            <label class="segment-label">
                                <input type="radio" name="is_seasonal" value="1" {{ old('is_seasonal', $experience->is_seasonal ? '1' : '0') == '1' ? 'checked' : '' }}>
                                🌸 Seasonal
                            </label>
                        </div>
                    </div>
                </div>

                {{-- What You'll Do --}}
                <div class="form-group">
                    <label class="form-label" style="margin-bottom:0.75rem;">Itinerary / What You'll Do</label>
                    <div class="dynamic-list">
                        <template x-for="(item, index) in whatYouDo" :key="index">
                            <div class="dynamic-item">
                                <div class="form-group" style="flex:1;">
                                    <input type="text" :name="`what_you_do[${index}][title]`" x-model="item.title" class="form-control" placeholder="Activity title">
                                </div>
                                <div class="form-group" style="flex:2;">
                                    <input type="text" :name="`what_you_do[${index}][desc]`" x-model="item.desc" class="form-control" placeholder="Brief description">
                                </div>
                                <button type="button" class="btn-remove" @click="whatYouDo.splice(index, 1)" x-show="whatYouDo.length > 1">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn-add" @click="if(whatYouDo.length < 4) whatYouDo.push({title:'',desc:''})" style="margin-top: 0.75rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Activity
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Pricing & Inclusions ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">Pricing & Inclusions</h2>
            </div>
            <div class="form-card-body">
                <div class="form-group" style="max-width:400px; margin-bottom:1.5rem;">
                    <label class="form-label">Price per Person (IDR) <span class="req">*</span></label>
                    <div style="position:relative; display:flex; align-items:center;">
                        <span style="position:absolute; left:1.25rem; font-weight:600; color:var(--text-muted);">Rp</span>
                        <input type="number" name="harga" class="form-control" style="padding-left:3.25rem; font-size:1.1rem; font-weight:600;" value="{{ old('harga', $experience->harga) }}" min="1000" required>
                    </div>
                </div>

                <div class="form-grid form-grid-2">
                    <div>
                        <label class="form-label" style="margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; color:var(--primary);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            What's Included
                        </label>
                        <div class="dynamic-list">
                            <template x-for="(item, index) in included" :key="index">
                                <div class="dynamic-item">
                                    <input type="text" :name="`included[${index}]`" x-model="included[index]" class="form-control" placeholder="e.g. All materials">
                                    <button type="button" class="btn-remove" @click="included.splice(index,1)" x-show="included.length > 1">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" class="btn-add" @click="included.push('')" style="margin-top: 0.5rem;">+ Add Inclusion</button>
                    </div>
                    <div>
                        <label class="form-label" style="margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; color:var(--accent);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Not Included
                        </label>
                        <div class="dynamic-list">
                            <template x-for="(item, index) in notIncluded" :key="index">
                                <div class="dynamic-item">
                                    <input type="text" :name="`not_included[${index}]`" x-model="notIncluded[index]" class="form-control" placeholder="e.g. Transportation">
                                    <button type="button" class="btn-remove" @click="notIncluded.splice(index,1)" x-show="notIncluded.length > 1">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" class="btn-add" @click="notIncluded.push('')" style="margin-top: 0.5rem;">+ Add Exclusion</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Location ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">Location</h2>
            </div>
            <div class="form-card-body">
                <div class="form-grid form-grid-2" style="margin-bottom:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Location Name <span class="req">*</span></label>
                        <input type="text" name="lokasi_nama" class="form-control" value="{{ old('lokasi_nama', $experience->lokasi_nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kabupaten <span class="req">*</span></label>
                        <select name="kabupaten" class="form-control" required>
                            @foreach(['Gianyar','Ubud','Bangli','Badung','Tabanan','Klungkung','Buleleng','Jembrana','Karangasem'] as $kab)
                                <option value="{{ $kab }}" {{ old('kabupaten', $experience->kabupaten) === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="display:block; margin-bottom:0.75rem;">Pinpoint on Map <span class="req">*</span></label>
                    <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1rem;">Drag the marker or click on the map to update the exact location.</p>
                    <div id="map" x-ref="mapContainer"></div>
                </div>

                <div class="form-grid form-grid-2" style="margin-bottom:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Latitude <span class="req">*</span></label>
                        <input type="number" name="lokasi_lat" class="form-control" step="any" x-model="lat" @input="updateMapFromInput()" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Longitude <span class="req">*</span></label>
                        <input type="number" name="lokasi_lng" class="form-control" step="any" x-model="lng" @input="updateMapFromInput()" required>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">Full Address <span class="req">*</span></label>
                    <textarea name="alamat_lengkap" class="form-control" rows="2">{{ old('alamat_lengkap', $experience->alamat_lengkap) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Meeting Point <span class="req">*</span></label>
                    <textarea name="meeting_point" class="form-control" rows="2">{{ old('meeting_point', $experience->meeting_point) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Photos ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-card-title">Photos</h2>
            </div>
            <div class="form-card-body">

                {{-- Existing photos --}}
                @if($experience->photos->count() > 0)
                    <p style="font-size:0.875rem; color:var(--text-muted); margin-bottom:1rem; font-weight:500;">Existing Photos — click ✕ to remove:</p>
                    <div class="photo-grid" style="margin-bottom:1.5rem;">
                        @foreach($experience->photos as $photo)
                            <div class="photo-item {{ $photo->is_cover ? 'is-cover' : '' }}">
                                <img src="{{ $photo->url }}" alt="">
                                @if($photo->is_cover)
                                    <div class="badge-cover">Cover</div>
                                @endif
                                {{-- We handle deletion natively here. --}}
                                <button type="button" class="btn-remove-photo" onclick="if(confirm('Hapus foto ini?')) document.getElementById('delete-photo-{{$photo->id}}').submit();">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Upload new photos (Alpine logic) --}}
                @if($experience->photos->count() < 8)
                    <div class="upload-zone" @click="$refs.photoInput.click()" x-show="newPhotos.length < (8 - {{ $experience->photos->count() }})">
                        <div class="upload-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <div style="font-size:0.95rem; font-weight:600; color:var(--text-main);">Add more photos</div>
                        <div style="font-size:0.85rem; color:var(--text-muted);">{{ 8 - $experience->photos->count() }} remaining</div>
                    </div>
                    
                    {{-- Temp file input --}}
                    <input type="file" multiple accept="image/*" x-ref="photoInput" @change="handlePhotoUpload($event)" style="display:none;">
                    
                    {{-- Preview for newly added photos --}}
                    <div class="photo-grid" x-show="newPhotos.length > 0">
                        <template x-for="(photo, index) in newPhotos" :key="index">
                            <div class="photo-item">
                                <img :src="photo.preview" alt="New Photo">
                                <div style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.2); pointer-events:none;"></div>
                                <div style="position:absolute; bottom:0.5rem; left:0.5rem; background:#3B82F6; color:white; font-size:0.7rem; font-weight:700; padding:0.25rem 0.6rem; border-radius:6px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">NEW</div>
                                <button type="button" class="btn-remove-photo" @click.stop="removeNewPhoto(index)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                @endif
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="bottom-nav">
            <div style="display:flex; gap:1rem;">
                <a href="{{ route('host.experiences.index') }}" class="btn btn-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
                    Back
                </a>
                @if(in_array($experience->status, ['draft', 'rejected']))
                    <button type="button" class="btn btn-outline" style="color:#D97706; border-color:#FCD34D; background:#FEF3C7;" onclick="if(confirm('Submit experience ini untuk review admin?')) document.getElementById('submit-review-form').submit();">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Submit for Review
                    </button>
                @endif
            </div>
            
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Changes
            </button>
        </div>

    </form>

    {{-- Hidden forms for auxiliary actions --}}
    @foreach($experience->photos as $photo)
        <form id="delete-photo-{{$photo->id}}" method="POST" action="{{ route('host.experiences.deletePhoto', $photo->id) }}" style="display:none;">
            @csrf @method('DELETE')
        </form>
    @endforeach

    @if(in_array($experience->status, ['draft', 'rejected']))
        <form id="submit-review-form" method="POST" action="{{ route('host.experiences.submitReview', $experience->id) }}" style="display:none;">
            @csrf
        </form>
    @endif

</div>

@endsection

@push('scripts')
<script>
function editForm() {
    return {
        included:    @json(array_values($includedData) ?: ['']),
        notIncluded: @json(array_values($notIncludedData) ?: ['']),
        whatYouDo:   @json($whatYouDoData ?: [['title'=>'', 'desc'=>'']]),
        newPhotos: [],
        maxNewPhotos: {{ 8 - $experience->photos->count() }},
        
        lat: '{{ old('lokasi_lat', $experience->lokasi_lat) }}',
        lng: '{{ old('lokasi_lng', $experience->lokasi_lng) }}',
        mapInstance: null,
        marker: null,

        init() {
            this.$nextTick(() => {
                this.initMap();
            });
        },

        initMap() {
            if (this.mapInstance) return;

            const startLat = parseFloat(this.lat) || -8.5069;
            const startLng = parseFloat(this.lng) || 115.2625;

            this.mapInstance = L.map(this.$refs.mapContainer).setView([startLat, startLng], 12);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CARTO'
            }).addTo(this.mapInstance);

            this.marker = L.marker([startLat, startLng], {draggable: true}).addTo(this.mapInstance);

            this.marker.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                this.lat = pos.lat.toFixed(6);
                this.lng = pos.lng.toFixed(6);
            });

            this.mapInstance.on('click', (e) => {
                const pos = e.latlng;
                this.marker.setLatLng(pos);
                this.lat = pos.lat.toFixed(6);
                this.lng = pos.lng.toFixed(6);
            });
        },

        updateMapFromInput() {
            if (this.mapInstance && this.marker && this.lat && this.lng) {
                const newLat = parseFloat(this.lat);
                const newLng = parseFloat(this.lng);
                if (!isNaN(newLat) && !isNaN(newLng)) {
                    const newPos = [newLat, newLng];
                    this.marker.setLatLng(newPos);
                    this.mapInstance.setView(newPos);
                }
            }
        },

        handlePhotoUpload(event) {
            const files = Array.from(event.target.files);
            const remaining = this.maxNewPhotos - this.newPhotos.length;
            const toAdd = files.slice(0, remaining);

            toAdd.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.newPhotos.push({ file: file, preview: e.target.result });
                };
                reader.readAsDataURL(file);
            });

            // Reset temp input
            event.target.value = '';
        },

        removeNewPhoto(index) {
            this.newPhotos.splice(index, 1);
        },

        prepareSubmit(e) {
            // Transfer stored files to native input
            if (this.newPhotos.length > 0) {
                const dt = new DataTransfer();
                this.newPhotos.forEach(p => dt.items.add(p.file));
                this.$refs.finalPhotosInput.files = dt.files;
            }

            // Cleanup
            this.whatYouDo = this.whatYouDo.filter(item => item.title.trim() !== '' || item.desc.trim() !== '');
            this.included = this.included.filter(item => item.trim() !== '');
            this.notIncluded = this.notIncluded.filter(item => item.trim() !== '');
        }
    }
}
</script>
@endpush
