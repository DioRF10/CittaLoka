@extends('layouts.app')

@section('title', 'All Reviews | ' . $experience->getJudul())

@section('content')
    <div style="max-width:1100px; margin:0 auto; padding:2rem 1.25rem 3rem;">
        <a href="{{ route('experiences.show', $experience->slug) }}"
            style="display:inline-flex; align-items:center; gap:0.4rem; color:#1E3A2F; font-size:0.9rem; font-weight:600; margin-bottom:1.25rem; text-decoration:none;">
            ← Back to experience
        </a>

        <div
            style="background:white; border:1.5px solid #EDE7DC; border-radius:16px; padding:1.5rem; margin-bottom:1.25rem;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap;">
                <div>
                    <div
                        style="font-size:0.75rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.4rem;">
                        Guest Reviews</div>
                    <h1
                        style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.8rem; font-weight:500; color:#1E3A2F; margin:0;">
                        {{ $experience->getJudul() }}
                    </h1>
                </div>
                <div style="text-align:right;">
                    @if($approvedCount > 0)
                        <div style="font-size:2rem; font-weight:700; color:#1E3A2F;">
                            {{ number_format($experience->rating_avg, 1) }}
                        </div>
                        <div style="color:#C4783A; font-size:1rem; margin:0.2rem 0;">
                            @for($i = 0; $i < round($experience->rating_avg); $i++)★@endfor@for($i = round($experience->rating_avg); $i < 5; $i++)☆@endfor
                        </div>
                        <div style="font-size:0.8rem; color:#7A7A6E;">{{ $approvedCount }} reviews</div>
                    @else
                        <div style="font-size:2rem; font-weight:700; color:#1E3A2F;">0.0</div>
                        <div style="color:#C4783A; font-size:1rem; margin:0.2rem 0;">☆☆☆☆☆</div>
                        <div style="font-size:0.8rem; color:#7A7A6E;">0 reviews</div>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:grid; gap:1rem;">
            @forelse($reviews as $review)
                <div style="background:white; border:1.5px solid #EDE7DC; border-radius:14px; padding:1.1rem 1.25rem;">
                    <div
                        style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.75rem; flex-wrap:wrap; margin-bottom:0.6rem;">
                        <div style="display:flex; align-items:center; gap:0.65rem;">
                            @if($review->user && method_exists($review->user, 'avatarUrl') && $review->user->avatarUrl())
                                <img src="{{ $review->user->avatarUrl() }}" alt="{{ $review->user->name }}"
                                    style="width:42px; height:42px; border-radius:50%; object-fit:cover; flex-shrink:0; border:1px solid #EDE7DC;">
                            @else
                                <div
                                    style="width:42px; height:42px; border-radius:50%; background:#1E3A2F; color:white; display:flex; align-items:center; justify-content:center; font-size:0.95rem; font-weight:600; flex-shrink:0;">
                                    {{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-size:0.95rem; font-weight:600; color:#2C2C2C;">
                                    {{ $review->user->name ?? 'Guest' }}
                                </div>
                                <div style="font-size:0.75rem; color:#7A7A6E;">
                                    {{ $review->published_at?->translatedFormat('d M Y') }}
                                </div>
                            </div>
                        </div>
                        <div style="color:#C4783A; font-size:0.9rem;">
                            @for($i = 0; $i < $review->rating; $i++)★@endfor@for($i = $review->rating; $i < 5; $i++)☆@endfor
                        </div>
                    </div>
                    @if($review->text)
                        <div style="font-size:0.92rem; color:#4A4A4A; line-height:1.7;">{{ $review->text }}</div>
                    @endif
                    @if($review->photos->isNotEmpty())
                        <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-top:0.8rem;">
                            @foreach($review->photos as $photo)
                                <img src="{{ $photo->url }}" alt=""
                                    style="width:72px; height:72px; object-fit:cover; border-radius:8px; border:1px solid #EDE7DC;">
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div style="background:white; border:1.5px solid #EDE7DC; border-radius:14px; padding:1.25rem; color:#7A7A6E;">
                    No public reviews for this experience yet.
                </div>
            @endforelse
        </div>

        <div style="margin-top:1.25rem;">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection