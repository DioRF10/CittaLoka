@extends('layouts.dashboard')

@section('title', 'Ajukan Complaint')
@section('page-title', 'Ajukan Complaint')

@php
    $categories = [
        'no_show' => 'Traveler Tidak Hadir (No-Show)',
        'not_as_described' => 'Perilaku Tidak Sesuai',
        'safety_concern' => 'Masalah Keamanan',
        'payment_issue' => 'Masalah Pembayaran',
        'inappropriate_behavior' => 'Perilaku Tidak Pantas',
        'other' => 'Lainnya',
    ];
@endphp

@section('content')

<div style="max-width:640px;">

    <a href="{{ route('host.bookings.index') }}" style="font-size:0.8rem; color:#7A7A6E; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; margin-bottom:1.5rem;">
        ← Kembali ke Bookings
    </a>

    {{-- Card booking --}}
    <div style="display:flex; align-items:center; gap:0.85rem; background:white; border:1.5px solid #EDE7DC; border-radius:12px; padding:0.85rem 1rem; margin-bottom:1.75rem;">
        <div>
            <div style="font-size:0.92rem; font-weight:500; color:#1E3A2F;">{{ $booking->experience_title_snapshot }}</div>
            <div style="font-size:0.75rem; color:#7A7A6E;">{{ $booking->user->name }} · {{ \Carbon\Carbon::parse($booking->tanggal_experience)->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    @if($errors->any())
        <div style="background:#FEF2F2; border:1.5px solid #FECACA; color:#C0392B; padding:0.85rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1.5rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('host.complaints.store', $booking->kode_booking) }}" method="POST" enctype="multipart/form-data"
          x-data="{
              photos: [],
              addPhotos(e) {
                  const files = Array.from(e.target.files).slice(0, 6 - this.photos.length);
                  files.forEach(file => {
                      const reader = new FileReader();
                      reader.onload = (ev) => this.photos.push({ file, preview: ev.target.result });
                      reader.readAsDataURL(file);
                  });
              },
              removePhoto(index) { this.photos.splice(index, 1); },
              submitForm(e) {
                  const dt = new DataTransfer();
                  this.photos.forEach(p => dt.items.add(p.file));
                  this.$refs.photoInput.files = dt.files;
                  e.target.submit();
              }
          }"
          @submit.prevent="submitForm($event)">
        @csrf
        <input type="file" name="photos[]" multiple accept="image/*" x-ref="photoInput" style="display:none;" @change="addPhotos($event)">

        {{-- Kategori --}}
        <div style="margin-bottom:1.25rem;">
            <label style="display:block; font-size:0.85rem; font-weight:500; color:#1E3A2F; margin-bottom:0.5rem;">Kategori complaint</label>
            <select name="category" required
                style="width:100%; padding:0.85rem 1rem; border:1.5px solid #E2DDD5; border-radius:10px; font-size:0.85rem; font-family:'DM Sans',sans-serif; outline:none; background:white; box-sizing:border-box;">
                <option value="" disabled selected>Pilih kategori</option>
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Deskripsi --}}
        <div style="margin-bottom:1.25rem;">
            <label style="display:block; font-size:0.85rem; font-weight:500; color:#1E3A2F; margin-bottom:0.5rem;">Ceritakan detailnya</label>
            <textarea name="description" rows="6" maxlength="2000" required placeholder="Jelaskan apa yang terjadi, kapan, dan bagaimana kejadiannya..."
                style="width:100%; padding:0.85rem 1rem; border:1.5px solid #E2DDD5; border-radius:10px; font-size:0.85rem; font-family:'DM Sans',sans-serif; resize:vertical; outline:none; box-sizing:border-box;">{{ old('description') }}</textarea>
        </div>

        {{-- Upload foto --}}
        <div style="margin-bottom:1.75rem;">
            <label style="display:block; font-size:0.85rem; font-weight:500; color:#1E3A2F; margin-bottom:0.5rem;">Lampirkan bukti foto (opsional, maks 6)</label>
            <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
                <template x-for="(photo, index) in photos" :key="index">
                    <div style="position:relative; width:72px; height:72px;">
                        <img :src="photo.preview" style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                        <button type="button" @click="removePhoto(index)"
                            style="position:absolute; top:-6px; right:-6px; width:20px; height:20px; border-radius:50%; background:#C0392B; color:white; border:none; cursor:pointer; font-size:0.7rem; line-height:1;">✕</button>
                    </div>
                </template>
                <button type="button" @click="$refs.photoInput.click()" x-show="photos.length < 6"
                    style="width:72px; height:72px; border:1.5px dashed #C4B8A8; border-radius:8px; background:#F7F3ED; color:#7A7A6E; cursor:pointer; font-size:0.7rem;">
                    + Tambah
                </button>
            </div>
        </div>

        <button type="submit"
            style="width:100%; padding:0.9rem; background:#1E3A2F; color:white; border:none; border-radius:10px; font-size:0.9rem; font-weight:600; cursor:pointer;">
            Kirim Complaint
        </button>
        <p style="font-size:0.75rem; color:#7A7A6E; text-align:center; margin-top:0.75rem;">
            Tim CittaLoka akan meninjau complaint ini dan menghubungi kamu lewat email.
        </p>
    </form>
</div>

@endsection
