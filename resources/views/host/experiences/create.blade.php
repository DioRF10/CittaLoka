@extends('layouts.dashboard')

@section('title', 'Create Experience')
@section('page-title', 'Create New Experience')

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

    /* Steps Progress */
    .steps-wrapper {
        background: white;
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .steps-wrapper::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 3rem;
        right: 3rem;
        height: 2px;
        background: var(--border);
        transform: translateY(-50%);
        z-index: 1;
    }

    .step-item {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0 0.5rem;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        border: 2px solid var(--border);
        background: white;
        color: var(--text-muted);
    }

    .step-item.active .step-circle {
        border-color: var(--primary);
        background: var(--primary);
        color: white;
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    .step-item.completed .step-circle {
        border-color: var(--primary);
        background: var(--primary);
        color: white;
    }

    .step-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.3s;
    }

    .step-item.active .step-label {
        color: var(--primary);
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
        padding: 3rem 2rem;
        text-align: center;
        cursor: pointer;
        background: #F9FAFB;
        transition: all 0.2s;
    }
    .upload-zone:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
    }
    .upload-icon {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
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
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .photo-item:hover { transform: translateY(-2px); }
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

    /* Animations */
    .fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
    .fade-enter-from { opacity: 0; transform: translateY(10px); }
    .fade-leave-to { opacity: 0; transform: translateY(-10px); }

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
        .steps-wrapper::before { display: none; }
        .step-label { display: none; }
        .steps-wrapper { padding: 1rem; }
        .form-card-body { padding: 1.5rem; }
        .bottom-nav { padding: 1rem; }
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@section('content')

<div class="form-container" x-data="experienceForm()">

    {{-- Error Messages --}}
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

    {{-- Step Indicator --}}
    <div class="steps-wrapper">
        <template x-for="step in 5" :key="step">
            <div class="step-item" :class="{'active': currentStep === step, 'completed': currentStep > step}">
                <div class="step-circle">
                    <template x-if="currentStep > step">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </template>
                    <template x-if="currentStep <= step">
                        <span x-text="step"></span>
                    </template>
                </div>
                <div class="step-label" x-text="stepLabels[step-1]"></div>
            </div>
        </template>
    </div>

    <form method="POST" action="{{ route('host.experiences.store') }}" enctype="multipart/form-data" id="experienceForm" @submit="prepareSubmit">
        @csrf
        {{-- Hidden input for real file submission --}}
        <input type="file" name="photos[]" multiple x-ref="finalPhotosInput" style="display:none;">

        {{-- STEP 1: Basic Info --}}
        <div x-show="currentStep === 1" x-transition:enter="fade-enter-active" x-transition:enter-start="fade-enter-from" x-transition:enter-end="opacity-100 transform-none">
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Basic Information</h2>
                    <p class="form-card-desc">Give your experience a catchy title and select the most fitting category.</p>
                </div>
                <div class="form-card-body">
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Title (English) <span class="req">*</span></label>
                            <input type="text" name="judul_en" class="form-control" placeholder="e.g. Traditional Batik Class in Ubud" value="{{ old('judul_en') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Judul (Indonesia) <span class="req">*</span></label>
                            <input type="text" name="judul_id" class="form-control" placeholder="contoh: Kelas Membatik Tradisional di Ubud" value="{{ old('judul_id') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label class="form-label">Experience Category <span class="req">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select category...</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('category_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->getNama(app()->getLocale()) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Description</h2>
                    <p class="form-card-desc">Describe what makes your experience unique and why guests will love it.</p>
                </div>
                <div class="form-card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Description (English)</label>
                            <textarea name="deskripsi_en" class="form-control" placeholder="Describe this experience in English...">{{ old('deskripsi_en') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi (Indonesia)</label>
                            <textarea name="deskripsi_id" class="form-control" placeholder="Ceritakan pengalaman ini dalam Bahasa Indonesia...">{{ old('deskripsi_id') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: Details --}}
        <div x-show="currentStep === 2" style="display:none;" x-transition:enter="fade-enter-active" x-transition:enter-start="fade-enter-from" x-transition:enter-end="opacity-100 transform-none">
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Experience Settings</h2>
                    <p class="form-card-desc">Define the duration, capacity, and environment of your experience.</p>
                </div>
                <div class="form-card-body">
                    <div class="form-grid form-grid-3">
                        <div class="form-group">
                            <label class="form-label">Duration (min) <span class="req">*</span></label>
                            <input type="number" name="durasi_menit" class="form-control" placeholder="e.g. 120" min="30" value="{{ old('durasi_menit') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Min Guests <span class="req">*</span></label>
                            <input type="number" name="kapasitas_min" class="form-control" placeholder="1" min="1" value="{{ old('kapasitas_min', 1) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max Guests <span class="req">*</span></label>
                            <input type="number" name="kapasitas_max" class="form-control" placeholder="10" min="1" value="{{ old('kapasitas_max') }}" required>
                        </div>
                    </div>

                    <div class="form-grid form-grid-2" style="margin-top: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Environment</label>
                            <div class="segmented-control">
                                <label class="segment-label">
                                    <input type="radio" name="is_indoor" value="0" {{ old('is_indoor', '0') == '0' ? 'checked' : '' }}>
                                    🌿 Outdoor
                                </label>
                                <label class="segment-label">
                                    <input type="radio" name="is_indoor" value="1" {{ old('is_indoor') == '1' ? 'checked' : '' }}>
                                    🏠 Indoor
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seasonality</label>
                            <div class="segmented-control">
                                <label class="segment-label">
                                    <input type="radio" name="is_seasonal" value="0" {{ old('is_seasonal', '0') == '0' ? 'checked' : '' }}>
                                    📅 Year-round
                                </label>
                                <label class="segment-label">
                                    <input type="radio" name="is_seasonal" value="1" {{ old('is_seasonal') == '1' ? 'checked' : '' }}>
                                    🌸 Seasonal
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Itinerary / What You'll Do</h2>
                    <p class="form-card-desc">Break down the experience into key activities (up to 4).</p>
                </div>
                <div class="form-card-body">
                    <div class="dynamic-list">
                        <template x-for="(item, index) in whatYouDo" :key="index">
                            <div class="dynamic-item">
                                <div class="form-group" style="flex:1;">
                                    <input type="text" :name="`what_you_do[${index}][title]`" x-model="item.title" class="form-control" placeholder="Activity title (e.g. Welcome & Tea)">
                                </div>
                                <div class="form-group" style="flex:2;">
                                    <input type="text" :name="`what_you_do[${index}][desc]`" x-model="item.desc" class="form-control" placeholder="Brief description of the activity">
                                </div>
                                <button type="button" class="btn-remove" @click="whatYouDo.splice(index, 1)" x-show="whatYouDo.length > 1" title="Remove activity">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn-add" @click="if(whatYouDo.length < 4) whatYouDo.push({title:'',desc:''})" style="margin-top: 1rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Activity
                    </button>
                </div>
            </div>
        </div>

        {{-- STEP 3: Photos --}}
        <div x-show="currentStep === 3" style="display:none;" x-transition:enter="fade-enter-active" x-transition:enter-start="fade-enter-from" x-transition:enter-end="opacity-100 transform-none">
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Stunning Visuals</h2>
                    <p class="form-card-desc">High-quality photos attract more guests. Upload up to 8 images.</p>
                </div>
                <div class="form-card-body">
                    <div class="upload-zone" @click="$refs.photoInput.click()" x-show="photos.length < 8">
                        <div class="upload-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <h3 style="font-size:1.1rem; font-weight:600; color:var(--text-main); margin:0 0 0.5rem;">Click to upload photos</h3>
                        <p style="font-size:0.875rem; color:var(--text-muted); margin:0;">JPG, PNG, WEBP (Max 5MB per file)</p>
                    </div>

                    {{-- Temp visible input for selecting --}}
                    <input type="file" multiple accept="image/*" x-ref="photoInput" @change="handlePhotoUpload($event)" style="display:none;">
                    <input type="hidden" name="cover_index" x-model="coverIndex">

                    <div class="photo-grid" x-show="photos.length > 0">
                        <template x-for="(photo, index) in photos" :key="index">
                            <div class="photo-item" :class="coverIndex === index ? 'is-cover' : ''" @click="coverIndex = index">
                                <img :src="photo.preview" alt="Experience Photo">
                                <div class="badge-cover" x-show="coverIndex === index">Cover Photo</div>
                                <button type="button" class="btn-remove-photo" @click.stop="removePhoto(index)" title="Remove photo">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div x-show="photos.length > 0" style="margin-top:1.5rem; padding:1rem; background:var(--primary-soft); border-radius:12px; display:flex; align-items:center; gap:0.75rem; color:var(--primary-light);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span style="font-size:0.9rem; font-weight:500;">Click on any uploaded photo to set it as the primary cover photo. (<span x-text="photos.length"></span>/8 uploaded)</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 4: Pricing & Inclusions --}}
        <div x-show="currentStep === 4" style="display:none;" x-transition:enter="fade-enter-active" x-transition:enter-start="fade-enter-from" x-transition:enter-end="opacity-100 transform-none">
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Pricing</h2>
                    <p class="form-card-desc">Set a fair price per person for your experience.</p>
                </div>
                <div class="form-card-body">
                    <div class="form-group" style="max-width: 400px;">
                        <label class="form-label">Price per Person (IDR) <span class="req">*</span></label>
                        <div style="position:relative; display:flex; align-items:center;">
                            <span style="position:absolute; left:1.25rem; font-weight:600; color:var(--text-muted);">Rp</span>
                            <input type="number" name="harga" class="form-control" style="padding-left:3.25rem; font-size:1.1rem; font-weight:600;" placeholder="350000" min="1000" value="{{ old('harga') }}" required>
                        </div>
                        <p style="font-size:0.85rem; color:var(--text-muted); margin-top:0.5rem; display:flex; align-items:center; gap:0.4rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            Platform fee is 10%. You will earn 90% per booking.
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Inclusions & Exclusions</h2>
                    <p class="form-card-desc">Be transparent about what guests need to bring.</p>
                </div>
                <div class="form-card-body">
                    <div class="form-grid form-grid-2">
                        {{-- Included --}}
                        <div>
                            <label class="form-label" style="margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; color:var(--primary);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                What's Included
                            </label>
                            <div class="dynamic-list">
                                <template x-for="(item, index) in included" :key="index">
                                    <div class="dynamic-item">
                                        <input type="text" :name="`included[${index}]`" x-model="included[index]" class="form-control" placeholder="e.g. Tools and materials">
                                        <button type="button" class="btn-remove" @click="included.splice(index, 1)" x-show="included.length > 1">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" class="btn-add" @click="included.push('')" style="margin-top: 0.5rem;">+ Add Inclusion</button>
                        </div>

                        {{-- Not Included --}}
                        <div>
                            <label class="form-label" style="margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; color:var(--accent);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                What's Not Included
                            </label>
                            <div class="dynamic-list">
                                <template x-for="(item, index) in notIncluded" :key="index">
                                    <div class="dynamic-item">
                                        <input type="text" :name="`not_included[${index}]`" x-model="notIncluded[index]" class="form-control" placeholder="e.g. Transportation">
                                        <button type="button" class="btn-remove" @click="notIncluded.splice(index, 1)" x-show="notIncluded.length > 1">
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
        </div>

        {{-- STEP 5: Location --}}
        <div x-show="currentStep === 5" style="display:none;" x-transition:enter="fade-enter-active" x-transition:enter-start="fade-enter-from" x-transition:enter-end="opacity-100 transform-none">
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-card-title">Location Details</h2>
                    <p class="form-card-desc">Where will the experience take place and where should guests meet you?</p>
                </div>
                <div class="form-card-body">
                    <div class="form-grid form-grid-2" style="margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Location Name <span class="req">*</span></label>
                            <input type="text" name="lokasi_nama" class="form-control" placeholder="e.g. Ubud Art Studio" value="{{ old('lokasi_nama') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kabupaten <span class="req">*</span></label>
                            <select name="kabupaten" class="form-control" required>
                                <option value="">Select Region...</option>
                                @foreach(['Gianyar','Ubud','Bangli','Badung','Tabanan','Klungkung','Buleleng','Jembrana','Karangasem'] as $kab)
                                    <option value="{{ $kab }}" {{ old('kabupaten') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="display:block; margin-bottom:0.75rem;">Pinpoint on Map <span class="req">*</span></label>
                        <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1rem;">Click on the map to set the exact location of your experience.</p>
                        <div id="map" x-ref="mapContainer"></div>
                    </div>

                    <div class="form-grid form-grid-2" style="margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Latitude <span class="req">*</span></label>
                            <input type="number" name="lokasi_lat" class="form-control" placeholder="-8.5069" step="any" x-model="lat" @input="updateMapFromInput()" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude <span class="req">*</span></label>
                            <input type="number" name="lokasi_lng" class="form-control" placeholder="115.2625" step="any" x-model="lng" @input="updateMapFromInput()" required>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label">Full Address <span class="req">*</span></label>
                        <textarea name="alamat_lengkap" class="form-control" rows="2" placeholder="Full address of the experience location...">{{ old('alamat_lengkap') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meeting Point <span class="req">*</span></label>
                        <textarea name="meeting_point" class="form-control" rows="2" placeholder="Instructions on where and how to meet you... e.g. In front of the main gate, look for the green umbrella.">{{ old('meeting_point') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Summary Notice --}}
            <div style="background:#F0FDF4; border:1px solid #BBF7D0; border-radius:16px; padding:1.5rem; display:flex; gap:1rem; align-items:flex-start;">
                <div style="background:#22C55E; color:white; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:0.25rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <h4 style="font-size:1rem; font-weight:700; color:#166534; margin:0 0 0.5rem;">Ready to create your experience!</h4>
                    <p style="font-size:0.9rem; color:#15803D; margin:0; line-height:1.6;">
                        Your experience will be saved as a draft. You can review all details on the next page before submitting it for admin approval. Once approved, it will be live for guests to book.
                    </p>
                </div>
            </div>
        </div>

        {{-- Bottom Navigation --}}
        <div class="bottom-nav">
            <div style="display:flex; gap:1rem;">
                <a href="{{ route('host.experiences.index') }}" class="btn btn-outline" x-show="currentStep === 1">Cancel</a>
                <button type="button" class="btn btn-outline" @click="currentStep--" x-show="currentStep > 1">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
                    Back
                </button>
            </div>
            
            <div style="display:flex; align-items:center; gap:1.5rem;">
                <span style="font-size:0.875rem; font-weight:600; color:var(--text-muted);" x-text="`Step ${currentStep} of 5`"></span>
                <button type="button" class="btn btn-primary" @click="nextStep()" x-show="currentStep < 5">
                    Continue
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
                <button type="submit" class="btn btn-primary" x-show="currentStep === 5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save as Draft
                </button>
            </div>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
function experienceForm() {
    return {
        currentStep: 1,
        stepLabels: ['Basic Info', 'Details', 'Photos', 'Pricing', 'Location'],
        photos: [],
        coverIndex: 0,
        whatYouDo: [{ title: '', desc: '' }],
        included: [''],
        notIncluded: [''],
        lat: '{{ old('lokasi_lat', '-8.5069') }}', // Default to Bali (Ubud area roughly)
        lng: '{{ old('lokasi_lng', '115.2625') }}',
        mapInstance: null,
        marker: null,

        nextStep() {
            if (this.validateStep()) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Initialize map if moving to step 5
                if (this.currentStep === 5) {
                    this.$nextTick(() => {
                        this.initMap();
                    });
                }
            }
        },

        initMap() {
            if (this.mapInstance) {
                this.mapInstance.invalidateSize();
                return;
            }

            // Init Leaflet map
            const startLat = parseFloat(this.lat) || -8.5069;
            const startLng = parseFloat(this.lng) || 115.2625;

            this.mapInstance = L.map(this.$refs.mapContainer).setView([startLat, startLng], 12);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CARTO'
            }).addTo(this.mapInstance);

            this.marker = L.marker([startLat, startLng], {draggable: true}).addTo(this.mapInstance);

            // Sync dragging marker to inputs
            this.marker.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                this.lat = pos.lat.toFixed(6);
                this.lng = pos.lng.toFixed(6);
            });

            // Sync clicking map to inputs and marker
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

        validateStep() {
            if (this.currentStep === 1) {
                const judulEn = document.querySelector('[name="judul_en"]').value;
                const judulId = document.querySelector('[name="judul_id"]').value;
                const category = document.querySelector('[name="category_id"]').value;
                if (!judulEn || !judulId) {
                    alert('Please enter the experience title in both English and Indonesian.');
                    return false;
                }
                if (!category) {
                    alert('Please select a category.');
                    return false;
                }
            }
            if (this.currentStep === 2) {
                const durasi = document.querySelector('[name="durasi_menit"]').value;
                const kapMax = document.querySelector('[name="kapasitas_max"]').value;
                if (!durasi || durasi < 30) {
                    alert('Duration must be at least 30 minutes.');
                    return false;
                }
                if (!kapMax || kapMax < 1) {
                    alert('Please specify the maximum capacity.');
                    return false;
                }
            }
            if (this.currentStep === 3) {
                // Not forcing photos here, but will check on submit
            }
            if (this.currentStep === 4) {
                const harga = document.querySelector('[name="harga"]').value;
                if (!harga || harga < 1000) {
                    alert('Price must be at least Rp 1,000.');
                    return false;
                }
            }
            return true;
        },

        handlePhotoUpload(event) {
            const files = Array.from(event.target.files);
            const remaining = 8 - this.photos.length;
            const toAdd = files.slice(0, remaining);

            toAdd.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photos.push({ file: file, preview: e.target.result });
                };
                reader.readAsDataURL(file);
            });

            // Reset temp input so same files can be selected again if needed
            event.target.value = '';
        },

        removePhoto(index) {
            this.photos.splice(index, 1);
            if (this.coverIndex >= this.photos.length) {
                this.coverIndex = 0;
            }
        },

        prepareSubmit(e) {
            // Validate photos before submission
            if (this.photos.length === 0) {
                e.preventDefault();
                alert('Please upload at least 1 photo for your experience.');
                this.currentStep = 3;
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            // Transfer stored files to the hidden native file input
            const dt = new DataTransfer();
            this.photos.forEach(p => {
                dt.items.add(p.file);
            });
            
            this.$refs.finalPhotosInput.files = dt.files;
            
            // Clean up empty dynamic fields
            this.whatYouDo = this.whatYouDo.filter(item => item.title.trim() !== '' || item.desc.trim() !== '');
            this.included = this.included.filter(item => item.trim() !== '');
            this.notIncluded = this.notIncluded.filter(item => item.trim() !== '');
            
            // Form continues to submit natively
        }
    }
}
</script>
@endpush
