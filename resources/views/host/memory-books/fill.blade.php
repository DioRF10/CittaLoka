@extends('layouts.dashboard')

@section('title', 'Fill Memory Book')
@section('page-title', 'Fill Memory Book')

@section('content')

<div style="max-width:860px; display:flex; flex-direction:column; gap:1.5rem;">

    {{-- Back --}}
    <a href="{{ route('host.memory-books.index') }}"
        style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.82rem; color:#7A7A6E; text-decoration:none;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke Memory Books
    </a>

    {{-- Booking info card --}}
    <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; padding:1.25rem 1.5rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
        <div>
            <div style="font-size:0.7rem; font-weight:700; color:#C4783A; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.4rem;">Memory Book untuk</div>
            <div style="font-size:1.1rem; font-weight:700; color:#1E3A2F;">{{ $booking->user->name }}</div>
            <div style="font-size:0.82rem; color:#7A7A6E; margin-top:0.2rem;">
                {{ $booking->experience_title_snapshot }} ·
                {{ \Carbon\Carbon::parse($booking->tanggal_experience)->locale('id')->isoFormat('D MMMM YYYY') }}
            </div>
        </div>
        <div style="text-align:right;">
            @php
                $statusColor = match($memoryBook->status) {
                    'sent'         => '#2D5240',
                    'pending_host' => '#C4783A',
                    'overdue'      => '#C0392B',
                    default        => '#7A7A6E',
                };
                $statusBg = match($memoryBook->status) {
                    'sent'         => '#EBF5EE',
                    'pending_host' => '#FDF6EE',
                    'overdue'      => '#FEF2F2',
                    default        => '#F3F4F6',
                };
                $statusLabel = match($memoryBook->status) {
                    'sent'         => 'Sudah Dikirim',
                    'pending_host' => 'Perlu Diisi',
                    'overdue'      => 'Terlambat',
                    default        => 'Belum Dimulai',
                };
            @endphp
            <span style="font-size:0.72rem; font-weight:700; letter-spacing:0.06em; padding:0.3rem 0.75rem; border-radius:999px; background:{{ $statusBg }}; color:{{ $statusColor }};">
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div style="background:#EBF5EE; border:1px solid #B8DFC8; color:#2D5240; padding:0.75rem 1rem; border-radius:10px; font-size:0.875rem;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:10px; font-size:0.875rem;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Form --}}
    <form method="POST"
          action="{{ route('host.memory-books.fill.update', $memoryBook->id) }}"
          enctype="multipart/form-data"
          x-data="memoryBookForm()"
          style="display:flex; flex-direction:column; gap:1.25rem;">
        @csrf
        @method('PUT')

        {{-- ── Section 1: Pesan Utama ── --}}
        <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; overflow:hidden;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #EFE7DC; display:flex; align-items:center; gap:0.6rem;">
                <div style="width:28px; height:28px; border-radius:8px; background:#F7F3ED; display:flex; align-items:center; justify-content:center; font-size:0.9rem;">✍️</div>
                <span style="font-size:0.95rem; font-weight:700; color:#1E3A2F;">Pesan untuk Wisatawan</span>
            </div>
            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1.25rem;">

                {{-- Judul --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:600; color:#4A4A4A; margin-bottom:0.4rem;">
                        Judul <span style="color:#C4783A;">*</span>
                    </label>
                    <input type="text"
                           name="judul"
                           value="{{ old('judul', $memoryBook->judul ?? 'Terima kasih, ' . $booking->user->name . '!') }}"
                           placeholder="Contoh: Terima kasih, Sarah!"
                           required
                           style="width:100%; padding:0.65rem 0.875rem; border:1.5px solid #E8E0D3; border-radius:10px; font-size:0.875rem; color:#1E3A2F; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#1E3A2F'"
                           onblur="this.style.borderColor='#E8E0D3'">
                    <div style="font-size:0.72rem; color:#9CA3AF; margin-top:0.3rem;">Judul personal yang muncul di bagian "Cerita dari Host"</div>
                </div>

                {{-- Pesan Host --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:600; color:#4A4A4A; margin-bottom:0.4rem;">
                        Pesan Personal <span style="color:#C4783A;">*</span>
                    </label>
                    <textarea name="host_message"
                              rows="6"
                              placeholder="Ceritakan pengalaman hari itu dari sudut pandangmu sebagai host..."
                              required
                              style="width:100%; padding:0.65rem 0.875rem; border:1.5px solid #E8E0D3; border-radius:10px; font-size:0.875rem; color:#1E3A2F; outline:none; resize:vertical; box-sizing:border-box; line-height:1.7;"
                              onfocus="this.style.borderColor='#1E3A2F'"
                              onblur="this.style.borderColor='#E8E0D3'">{{ old('host_message', $memoryBook->host_message) }}</textarea>
                    <div style="font-size:0.72rem; color:#9CA3AF; margin-top:0.3rem;">Tulis dengan tulus — ini akan menjadi kenangan abadi bagi wisatawan</div>
                </div>

                {{-- Quote Highlight --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:600; color:#4A4A4A; margin-bottom:0.4rem;">
                        Quote / Kalimat Emosional <span style="color:#9CA3AF; font-weight:400;">(opsional)</span>
                    </label>
                    <input type="text"
                           name="quote_highlight"
                           value="{{ old('quote_highlight', $memoryBook->quote_highlight) }}"
                           placeholder="Contoh: Setiap pertemuan adalah cerita yang tak terlupakan."
                           style="width:100%; padding:0.65rem 0.875rem; border:1.5px solid #E8E0D3; border-radius:10px; font-size:0.875rem; color:#1E3A2F; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#1E3A2F'"
                           onblur="this.style.borderColor='#E8E0D3'">
                    <div style="font-size:0.72rem; color:#9CA3AF; margin-top:0.3rem;">Ditampilkan sebagai quote di hero Memory Book</div>
                </div>

                {{-- Pesan Penutup --}}
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:600; color:#4A4A4A; margin-bottom:0.4rem;">
                        Pesan Penutup <span style="color:#9CA3AF; font-weight:400;">(opsional)</span>
                    </label>
                    <textarea name="pesan_penutup"
                              rows="3"
                              placeholder="Contoh: Sampai jumpa lagi di Bali!"
                              style="width:100%; padding:0.65rem 0.875rem; border:1.5px solid #E8E0D3; border-radius:10px; font-size:0.875rem; color:#1E3A2F; outline:none; resize:vertical; box-sizing:border-box; line-height:1.7;"
                              onfocus="this.style.borderColor='#1E3A2F'"
                              onblur="this.style.borderColor='#E8E0D3'">{{ old('pesan_penutup', $memoryBook->pesan_penutup) }}</textarea>
                </div>

            </div>
        </div>

        {{-- ── Section 2: Highlight ── --}}
        <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; overflow:hidden;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #EFE7DC; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div style="width:28px; height:28px; border-radius:8px; background:#F7F3ED; display:flex; align-items:center; justify-content:center; font-size:0.9rem;">⭐</div>
                    <span style="font-size:0.95rem; font-weight:700; color:#1E3A2F;">Highlight Hari Itu</span>
                    <span style="font-size:0.72rem; color:#9CA3AF;">(opsional, maks 4)</span>
                </div>
                <button type="button"
                        x-on:click="addHighlight"
                        x-bind:disabled="highlights.length >= 4"
                        style="font-size:0.78rem; font-weight:600; color:#1E3A2F; border:1.5px solid #1E3A2F; background:white; padding:0.4rem 0.875rem; border-radius:8px; cursor:pointer;"
                        x-bind:style="highlights.length >= 4 ? 'opacity:0.4; cursor:not-allowed;' : ''">
                    + Tambah
                </button>
            </div>
            <div style="padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:0.875rem;">

                <template x-for="(item, index) in highlights" :key="index">
                    <div style="display:grid; grid-template-columns:56px 1fr 1fr auto; gap:0.75rem; align-items:start; background:#FAFAF8; border:1.5px solid #EFE7DC; border-radius:10px; padding:0.875rem;">

                        {{-- Icon --}}
                        <div>
                            <div style="font-size:0.7rem; color:#9CA3AF; margin-bottom:0.3rem;">Icon</div>
                            <input type="text"
                                   x-model="item.icon"
                                   placeholder="🌿"
                                   maxlength="4"
                                   style="width:100%; padding:0.5rem; border:1.5px solid #E8E0D3; border-radius:8px; font-size:1.1rem; text-align:center; box-sizing:border-box; outline:none;"
                                   onfocus="this.style.borderColor='#1E3A2F'"
                                   onblur="this.style.borderColor='#E8E0D3'">
                        </div>

                        {{-- Judul --}}
                        <div>
                            <div style="font-size:0.7rem; color:#9CA3AF; margin-bottom:0.3rem;">Judul</div>
                            <input type="text"
                                   x-model="item.judul"
                                   placeholder="Belajar membuat Bumbu Base Genep"
                                   style="width:100%; padding:0.5rem 0.65rem; border:1.5px solid #E8E0D3; border-radius:8px; font-size:0.82rem; box-sizing:border-box; outline:none;"
                                   onfocus="this.style.borderColor='#1E3A2F'"
                                   onblur="this.style.borderColor='#E8E0D3'">
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <div style="font-size:0.7rem; color:#9CA3AF; margin-bottom:0.3rem;">Deskripsi</div>
                            <input type="text"
                                   x-model="item.deskripsi"
                                   placeholder="Rahasia bumbu khas Bali..."
                                   style="width:100%; padding:0.5rem 0.65rem; border:1.5px solid #E8E0D3; border-radius:8px; font-size:0.82rem; box-sizing:border-box; outline:none;"
                                   onfocus="this.style.borderColor='#1E3A2F'"
                                   onblur="this.style.borderColor='#E8E0D3'">
                        </div>

                        {{-- Hapus --}}
                        <div style="padding-top:1.4rem;">
                            <button type="button"
                                    x-on:click="removeHighlight(index)"
                                    style="width:32px; height:32px; border-radius:8px; border:1.5px solid #FECACA; background:white; color:#EF4444; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                            </button>
                        </div>

                    </div>
                </template>

                <div x-show="highlights.length === 0" style="text-align:center; padding:1.5rem; color:#9CA3AF; font-size:0.82rem;">
                    Belum ada highlight. Klik "+ Tambah" untuk menambahkan.
                </div>

                {{-- Hidden input untuk kirim ke server --}}
                <input type="hidden" name="highlight_items" x-bind:value="JSON.stringify(highlights)">

            </div>
        </div>

        {{-- ── Section 3: Foto Cover ── --}}
        <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; overflow:hidden;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #EFE7DC; display:flex; align-items:center; gap:0.6rem;">
                <div style="width:28px; height:28px; border-radius:8px; background:#F7F3ED; display:flex; align-items:center; justify-content:center; font-size:0.9rem;">🖼️</div>
                <span style="font-size:0.95rem; font-weight:700; color:#1E3A2F;">Foto Cover</span>
                <span style="font-size:0.72rem; color:#9CA3AF;">(1 foto, untuk hero & cerita host)</span>
            </div>
            <div style="padding:1.5rem;">

                @if($memoryBook->cover_photo_url)
                {{-- Cover yang sudah ada --}}
                <div style="position:relative; max-width:320px; aspect-ratio:4/5; border-radius:12px; overflow:hidden; border:1.5px solid #E8E0D3;">
                    <img src="{{ $memoryBook->cover_photo_url }}" alt="Cover" style="width:100%; height:100%; object-fit:cover;">
                    <label style="position:absolute; bottom:0.5rem; right:0.5rem; padding:0.4rem 0.75rem; background:rgba(0,0,0,0.65); color:white; border-radius:8px; font-size:0.75rem; cursor:pointer;">
                        Ganti foto
                        <input type="file"
                               name="cover_photo"
                               accept="image/jpeg,image/png,image/jpg"
                               style="display:none;"
                               x-on:change="previewCover($event)">
                    </label>
                </div>
                <div x-show="coverPreview" style="margin-top:0.75rem;">
                    <div style="font-size:0.72rem; color:#C4783A; margin-bottom:0.5rem;">Foto baru (belum disimpan):</div>
                    <div style="max-width:320px; aspect-ratio:4/5; border-radius:12px; overflow:hidden; border:1.5px solid #C4783A;">
                        <img :src="coverPreview" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                </div>
                @else
                {{-- Upload cover baru --}}
                <label style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.5rem; padding:2rem; border:2px dashed #E8E0D3; border-radius:12px; cursor:pointer; background:#FAFAF8; transition:border-color 0.2s; max-width:320px;"
                       onmouseover="this.style.borderColor='#1E3A2F'"
                       onmouseout="this.style.borderColor='#E8E0D3'">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C4783A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span style="font-size:0.82rem; color:#7A7A6E; text-align:center;">Klik untuk upload foto cover<br><span style="font-size:0.72rem; color:#9CA3AF;">JPG, PNG — maks 5MB</span></span>
                    <input type="file"
                           name="cover_photo"
                           accept="image/jpeg,image/png,image/jpg"
                           style="display:none;"
                           x-on:change="previewCover($event)">
                </label>

                <div x-show="coverPreview" style="margin-top:0.75rem; max-width:320px;">
                    <div style="font-size:0.72rem; color:#9CA3AF; margin-bottom:0.5rem;">Preview:</div>
                    <div style="aspect-ratio:4/5; border-radius:12px; overflow:hidden; border:1.5px solid #E8E0D3;">
                        <img :src="coverPreview" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- ── Section 4: Gallery Foto (Momen Berharga) ── --}}
        <div style="background:white; border:1.5px solid #E8E0D3; border-radius:14px; overflow:hidden;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #EFE7DC; display:flex; align-items:center; gap:0.6rem;">
                <div style="width:28px; height:28px; border-radius:8px; background:#F7F3ED; display:flex; align-items:center; justify-content:center; font-size:0.9rem;">📸</div>
                <span style="font-size:0.95rem; font-weight:700; color:#1E3A2F;">Galeri Momen Berharga</span>
                <span style="font-size:0.72rem; color:#9CA3AF;">(maks 20 foto)</span>
            </div>
            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">

                {{-- Existing gallery photos --}}
                @if($memoryBook->photos->isNotEmpty())
                <div>
                    <div style="font-size:0.78rem; font-weight:600; color:#4A4A4A; margin-bottom:0.75rem;">
                        Foto yang sudah ada ({{ $memoryBook->photos->count() }}/20):
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0.75rem;">
                        @foreach($memoryBook->photos as $photo)
                        <div style="position:relative; aspect-ratio:4/3; border-radius:10px; overflow:hidden; border:1.5px solid #E8E0D3;">
                            <img src="{{ $photo->url }}" alt="foto" style="width:100%; height:100%; object-fit:cover;">
                            <button type="button"
                                    x-on:click="deletePhoto({{ $photo->id }}, $el.closest('div[style*=position]'))"
                                    style="position:absolute; top:0.3rem; right:0.3rem; width:24px; height:24px; border-radius:50%; background:rgba(0,0,0,0.6); border:none; color:white; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.7rem;">
                                ✕
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Upload gallery baru --}}
                @php $maxUpload = 20 - $memoryBook->photos->count(); @endphp
                @if($maxUpload > 0)
                <div>
                    @if($memoryBook->photos->isNotEmpty())
                    <div style="font-size:0.78rem; font-weight:600; color:#4A4A4A; margin-bottom:0.75rem;">Tambah foto baru (maks {{ $maxUpload }} lagi):</div>
                    @endif
                    <label style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.5rem; padding:2rem; border:2px dashed #E8E0D3; border-radius:12px; cursor:pointer; background:#FAFAF8; transition:border-color 0.2s;"
                           onmouseover="this.style.borderColor='#1E3A2F'"
                           onmouseout="this.style.borderColor='#E8E0D3'">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C4783A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span style="font-size:0.82rem; color:#7A7A6E; text-align:center;">Klik untuk upload beberapa foto sekaligus<br><span style="font-size:0.72rem; color:#9CA3AF;">JPG, PNG — maks 5MB per foto, maks {{ $maxUpload }} foto</span></span>
                        <input type="file"
                               name="photos[]"
                               multiple
                               accept="image/jpeg,image/png,image/jpg"
                               style="display:none;"
                               x-on:change="previewPhotos($event)">
                    </label>

                    {{-- Preview foto baru --}}
                    <div x-show="newPhotos.length > 0" style="margin-top:0.75rem;">
                        <div style="font-size:0.72rem; color:#9CA3AF; margin-bottom:0.5rem;" x-text="`${newPhotos.length} foto dipilih`"></div>
                        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:0.75rem;">
                            <template x-for="(photo, i) in newPhotos" :key="i">
                                <div style="aspect-ratio:4/3; border-radius:10px; overflow:hidden; border:1.5px solid #E8E0D3;">
                                    <img :src="photo" style="width:100%; height:100%; object-fit:cover;">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                @else
                <div style="text-align:center; padding:1rem; color:#9CA3AF; font-size:0.8rem;">
                    Sudah mencapai batas maksimal 20 foto.
                </div>
                @endif

            </div>
        </div>

        {{-- ── Action Buttons ── --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">

            <div style="font-size:0.78rem; color:#9CA3AF;">
                Memory Book akan dikirim ke email wisatawan setelah kamu klik "Kirim".
            </div>

            <div style="display:flex; gap:0.75rem;">
                {{-- Simpan Draft --}}
                <button type="submit"
                        name="action"
                        value="draft"
                        style="padding:0.65rem 1.25rem; border:1.5px solid #E8E0D3; background:white; color:#4A4A4A; border-radius:10px; font-size:0.875rem; font-weight:600; cursor:pointer; transition:all 0.15s;"
                        onmouseover="this.style.borderColor='#1E3A2F'; this.style.color='#1E3A2F'"
                        onmouseout="this.style.borderColor='#E8E0D3'; this.style.color='#4A4A4A'">
                    Simpan Draft
                </button>

                {{-- Kirim ke Wisatawan --}}
                <button type="submit"
                        name="action"
                        value="send"
                        style="padding:0.65rem 1.5rem; background:#1E3A2F; color:white; border:none; border-radius:10px; font-size:0.875rem; font-weight:600; cursor:pointer; transition:background 0.15s;"
                        onmouseover="this.style.background='#2D5240'"
                        onmouseout="this.style.background='#1E3A2F'">
                    Kirim ke Wisatawan ✉️
                </button>
            </div>
        </div>

    </form>

</div>

@push('scripts')
<script>
function memoryBookForm() {
    return {
        highlights: @json(
            $memoryBook->highlight_items
                ? (is_array($memoryBook->highlight_items)
                    ? $memoryBook->highlight_items
                    : json_decode($memoryBook->highlight_items, true))
                : []
        ),
        newPhotos: [],
        coverPreview: null,

        addHighlight() {
            if (this.highlights.length < 4) {
                this.highlights.push({ icon: '', judul: '', deskripsi: '' });
            }
        },

        removeHighlight(index) {
            this.highlights.splice(index, 1);
        },

        previewCover(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => this.coverPreview = e.target.result;
            reader.readAsDataURL(file);
        },

        previewPhotos(event) {
            const files = Array.from(event.target.files);
            this.newPhotos = [];
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => this.newPhotos.push(e.target.result);
                reader.readAsDataURL(file);
            });
        },

        deletePhoto(photoId, el) {
            if (!confirm('Hapus foto ini?')) return;
            fetch(`/dashboard/memory-books/photos/${photoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) el.remove();
            })
            .catch(() => alert('Gagal menghapus foto.'));
        }
    }
}
</script>
@endpush

@endsection