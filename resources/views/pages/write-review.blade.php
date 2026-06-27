@extends('layouts.app')

@section('title', 'Write a Review — CittaLoka')

@section('content')

@php
    $exp = $booking->experience;
    $cover = $exp?->photos->where('is_cover', true)->first() ?? $exp?->photos->first();
@endphp

<div style="background:#FAFAF8; min-height:100vh; padding-bottom:5rem;">
    <div style="max-width:640px; margin:0 auto; padding:3rem 2rem 0;">

        <a href="{{ route('bookings.index') }}" style="font-size:0.8rem; color:#7A7A6E; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; margin-bottom:1.5rem;">
            ← Back to My Bookings
        </a>

        <h1 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:2rem; font-weight:500; color:#1E3A2F; margin-bottom:0.5rem;">
            Write a Review
        </h1>
        <p style="font-size:0.9rem; color:#7A7A6E; margin-bottom:1.75rem;">
            Bagikan pengalaman kamu, ini bakal bantu traveler lain dan host-nya.
        </p>

        {{-- Card experience --}}
        <div style="display:flex; align-items:center; gap:0.85rem; background:white; border:1.5px solid #EDE7DC; border-radius:12px; padding:0.85rem 1rem; margin-bottom:1.75rem;">
            @if($cover)
                <img src="{{ $cover->url }}" alt="" style="width:52px; height:52px; border-radius:8px; object-fit:cover; flex-shrink:0;">
            @endif
            <div>
                <div style="font-size:0.92rem; font-weight:500; color:#1E3A2F;">{{ $booking->experience_title_snapshot }}</div>
                <div style="font-size:0.75rem; color:#7A7A6E;">{{ $booking->host_name_snapshot }} · {{ \Carbon\Carbon::parse($booking->tanggal_experience)->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        @if($errors->any())
            <div style="background:#FEF2F2; border:1.5px solid #FECACA; color:#C0392B; padding:0.85rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1.5rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('reviews.store', $booking->kode_booking) }}" method="POST" enctype="multipart/form-data"
              x-data="{
                  rating: 0,
                  hover: 0,
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
                      if (this.rating === 0) {
                          alert('Mohon pilih rating bintang dulu.');
                          return;
                      }
                      const dt = new DataTransfer();
                      this.photos.forEach(p => dt.items.add(p.file));
                      this.$refs.photoInput.files = dt.files;
                      this.$refs.ratingInput.value = this.rating;
                      e.target.submit();
                  }
              }"
              @submit.prevent="submitForm($event)">
            @csrf
            <input type="hidden" name="rating" x-ref="ratingInput">
            <input type="file" name="photos[]" multiple accept="image/*" x-ref="photoInput" style="display:none;" @change="addPhotos($event)">

            {{-- Rating bintang --}}
            <div style="background:white; border:1.5px solid #EDE7DC; border-radius:12px; padding:1.5rem; margin-bottom:1.25rem; text-align:center;">
                <label style="display:block; font-size:0.85rem; font-weight:500; color:#1E3A2F; margin-bottom:0.85rem;">Berapa rating kamu untuk experience ini?</label>
                <div style="display:flex; justify-content:center; gap:0.5rem;">
                    <template x-for="star in [1,2,3,4,5]" :key="star">
                        <button type="button" @click="rating = star" @mouseenter="hover = star" @mouseleave="hover = 0"
                            style="background:none; border:none; cursor:pointer; padding:0; font-size:2.2rem; line-height:1; transition:transform 0.1s;"
                            :style="(hover || rating) >= star ? 'color:#C4783A;' : 'color:#E2DDD5;'"
                            x-text="(hover || rating) >= star ? '★' : '☆'">
                        </button>
                    </template>
                </div>
            </div>

            {{-- Teks review --}}
            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.85rem; font-weight:500; color:#1E3A2F; margin-bottom:0.5rem;">Ceritakan pengalaman kamu (opsional)</label>
                <textarea name="text" rows="5" maxlength="2000" placeholder="Apa yang paling kamu suka? Ada saran buat host atau traveler lain?"
                    style="width:100%; padding:0.85rem 1rem; border:1.5px solid #E2DDD5; border-radius:10px; font-size:0.85rem; font-family:'DM Sans',sans-serif; resize:vertical; outline:none; box-sizing:border-box;"></textarea>
            </div>

            {{-- Upload foto --}}
            <div style="margin-bottom:1.75rem;">
                <label style="display:block; font-size:0.85rem; font-weight:500; color:#1E3A2F; margin-bottom:0.5rem;">Tambahkan foto (opsional, maks 6)</label>
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
                Kirim Review
            </button>
            <p style="font-size:0.75rem; color:#7A7A6E; text-align:center; margin-top:0.75rem;">
                Review akan ditampilkan setelah ditinjau oleh tim CittaLoka.
            </p>
        </form>
    </div>
</div>

@endsection
