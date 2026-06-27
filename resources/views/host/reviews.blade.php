@extends('layouts.dashboard')

@section('title', 'Reviews')
@section('page-title', 'Guest Reviews')

@section('content')

@if(session('success'))
    <div style="background:#F0FDF4; border:1.5px solid #BBF7D0; color:#166534; padding:0.75rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1.25rem;">
        {{ session('success') }}
    </div>
@endif

<div x-data="{ openReply: null, zoomedPhoto: null }">

    @if($reviews->isEmpty())
        <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:3rem; text-align:center; color:#9CA3AF; font-size:0.875rem;">
            <div style="font-size:2rem; margin-bottom:0.75rem;">⭐</div>
            Belum ada review yang tayang untuk experience kamu.
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:1rem;">
            @foreach($reviews as $review)
                <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; padding:1.25rem 1.5rem;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.6rem;">
                        <div style="display:flex; align-items:center; gap:0.6rem;">
                            <div style="width:38px; height:38px; border-radius:50%; background:#1E3A2F; color:white; display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:500;">
                                {{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:0.9rem; font-weight:500; color:#1E3A2F;">{{ $review->user->name ?? 'Traveler' }}</div>
                                <div style="font-size:0.72rem; color:#7A7A6E;">{{ $review->experience?->getJudul() }} · {{ $review->published_at?->translatedFormat('d M Y') }}</div>
                            </div>
                        </div>
                        <div style="color:#C4783A; font-size:0.85rem; white-space:nowrap;">
                            @for($i = 0; $i < $review->rating; $i++)★@endfor@for($i = $review->rating; $i < 5; $i++)☆@endfor
                        </div>
                    </div>

                    @if($review->text)
                        <p style="font-size:0.85rem; color:#4A4A4A; line-height:1.6; margin-bottom:0.75rem;">{{ $review->text }}</p>
                    @endif

                    @if($review->photos->isNotEmpty())
                        <div style="display:flex; gap:0.5rem; margin-bottom:0.75rem; flex-wrap:wrap;">
                            @foreach($review->photos as $photo)
                                <img src="{{ $photo->url }}" alt="" @click="zoomedPhoto = '{{ $photo->url }}'" style="width:56px; height:56px; border-radius:8px; object-fit:cover; cursor:zoom-in;">
                            @endforeach
                        </div>
                    @endif

                    @if($review->reply)
                        <div style="background:#F7F3ED; border-radius:10px; padding:0.75rem 1rem; margin-top:0.5rem;">
                            <div style="font-size:0.72rem; font-weight:600; color:#1E3A2F; margin-bottom:0.2rem;">Balasan Kamu</div>
                            <div style="font-size:0.8rem; color:#4A4A4A; line-height:1.5;">{{ $review->reply->reply }}</div>
                        </div>
                    @else
                        <div x-show="openReply !== {{ $review->id }}">
                            <button @click="openReply = {{ $review->id }}"
                                style="font-size:0.8rem; color:#1E3A2F; font-weight:500; background:none; border:none; cursor:pointer; padding:0; text-decoration:underline;">
                                Balas Review
                            </button>
                        </div>
                        <form x-show="openReply === {{ $review->id }}" action="{{ route('host.reviews.reply', $review->id) }}" method="POST" style="margin-top:0.5rem;">
                            @csrf
                            <textarea name="reply" rows="3" maxlength="1000" required placeholder="Tulis balasan kamu..."
                                style="width:100%; padding:0.65rem 0.85rem; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.82rem; font-family:'DM Sans',sans-serif; resize:vertical; outline:none; box-sizing:border-box; margin-bottom:0.5rem;"></textarea>
                            <div style="display:flex; gap:0.5rem;">
                                <button type="submit"
                                    style="padding:0.45rem 1rem; background:#1E3A2F; color:white; border:none; border-radius:8px; font-size:0.8rem; font-weight:500; cursor:pointer;">
                                    Kirim Balasan
                                </button>
                                <button type="button" @click="openReply = null"
                                    style="padding:0.45rem 1rem; background:white; color:#7A7A6E; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.8rem; font-weight:500; cursor:pointer;">
                                    Batal
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div style="margin-top:1.5rem;">
            {{ $reviews->links() }}
        </div>
    @endif
    
    <!-- Modal Zoom -->
    <div x-show="zoomedPhoto" x-transition.opacity x-cloak
        style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);"
        @click="zoomedPhoto = null">
        <button style="position:absolute; top:1.5rem; right:1.5rem; background:white; color:#1E3A2F; border:none; border-radius:50%; width:40px; height:40px; font-size:1.5rem; font-weight:bold; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.2);" @click.stop="zoomedPhoto = null">&times;</button>
        <img :src="zoomedPhoto" @click.stop="" style="max-width:90vw; max-height:90vh; border-radius:12px; object-fit:contain; box-shadow:0 8px 32px rgba(0,0,0,0.3);">
    </div>
</div>

@endsection
