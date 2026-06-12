@extends('layouts.app')

@section('title', 'Checkout — ' . $experience->getJudul())

@section('content')

<div style="background:#F7F3ED; min-height:100vh; padding-bottom:4rem;">

    {{-- Progress Steps --}}
    <div style="max-width:800px; margin:0 auto; padding:2.5rem 2rem 2rem;">
        <div style="display:flex; align-items:center; justify-content:center; gap:0; margin-bottom:2.5rem;">

            @php
                $steps = [
                    ['label' => 'Select Date', 'num' => 1],
                    ['label' => 'Confirm',     'num' => 2],
                    ['label' => 'Pay',         'num' => 3],
                    ['label' => 'Done',        'num' => 4],
                ];
            @endphp

            @foreach($steps as $i => $step)
                <div style="display:flex; align-items:center;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:0.35rem;">
                        <div style="width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:600;
                            {{ $step['num'] === 2 ? 'background:#1E3A2F; color:white;' : 'background:#E8E4DC; color:#7A7A6E;' }}">
                            {{ $step['num'] }}
                        </div>
                        <span style="font-size:0.7rem; font-weight:{{ $step['num'] === 2 ? '600' : '400' }}; color:{{ $step['num'] === 2 ? '#1E3A2F' : '#9CA3AF' }}; white-space:nowrap;">
                            {{ $step['label'] }}
                        </span>
                    </div>
                    @if(!$loop->last)
                        <div style="width:80px; height:1px; background:#E2DDD5; margin:0 0.5rem; margin-bottom:1.2rem;"></div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Error / Success Messages --}}
        @if(session('error'))
            <div style="background:#FEF2F2; border:1px solid #FECACA; color:#C0392B; padding:0.75rem 1rem; border-radius:8px; font-size:0.875rem; margin-bottom:1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Main Grid --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; align-items:start;">

            {{-- ══ KIRI: Booking Summary ══ --}}
            <div>
                <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.75rem; font-weight:500; color:#1E3A2F; margin-bottom:1.25rem;">
                    Booking Summary
                </h2>

                <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; overflow:hidden;">

                    {{-- Experience Info --}}
                    <div style="padding:1.25rem; display:flex; gap:1rem; align-items:flex-start; border-bottom:1px solid #EDE7DC;">
                        <div style="width:72px; height:72px; border-radius:10px; overflow:hidden; flex-shrink:0;">
                            @if($cover)
                                <img src="{{ $cover->url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <div style="width:100%; height:100%; background:linear-gradient(135deg,#2D5240,#C4A882);"></div>
                            @endif
                        </div>
                        <div>
                            <div style="font-size:0.65rem; font-weight:700; color:#C4783A; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.25rem;">
                                {{ strtoupper($experience->kategori?->getNama($locale) ?? 'Experience') }}
                            </div>
                            <div style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.05rem; font-weight:500; color:#1E3A2F; line-height:1.3; margin-bottom:0.25rem;">
                                {{ $experience->getJudul($locale) }}
                            </div>
                            <div style="font-size:0.78rem; color:#7A7A6E;">
                                Hosted by {{ $experience->host->user->name }}
                                @if($experience->host->village)
                                    · {{ $experience->host->village }}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Date, Time, Guests --}}
                    <div style="padding:1.25rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem; border-bottom:1px solid #EDE7DC;">
                        <div>
                            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Date</div>
                            <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $tanggal }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Time</div>
                            <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $jamMulai }} – {{ $jamSelesai }} WITA</div>
                        </div>
                        <div>
                            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Guests</div>
                            <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $guests }} {{ $guests === 1 ? 'person' : 'people' }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Session</div>
                            <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">{{ $experience->is_indoor ? 'Indoor' : 'Outdoor' }}</div>
                        </div>
                    </div>

                    {{-- Price Breakdown --}}
                    <div style="padding:1.25rem; border-bottom:1px solid #EDE7DC;">
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#4A4A4A; margin-bottom:0.6rem;">
                            <span>{{ $guests }} {{ $guests === 1 ? 'guest' : 'guests' }}</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#4A4A4A; margin-bottom:0.75rem;">
                            <span>Platform fee</span>
                            <span>Rp {{ number_format($platformFee, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:1rem; font-weight:700; color:#1E3A2F; padding-top:0.75rem; border-top:1px solid #EDE7DC;">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Booking Code --}}
                    <div style="padding:1rem 1.25rem; background:#F7F3ED; border-bottom:1px solid #EDE7DC;">
                        <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.3rem;">Booking Code</div>
                        <div style="font-size:0.875rem; font-weight:600; color:#1E3A2F; font-family:'DM Sans',sans-serif;">
                            Will be generated after payment
                        </div>
                    </div>

                    {{-- Cancellation Policy --}}
                    <div style="padding:1rem 1.25rem;" x-data="{ open: false }">
                        <button @click="open = !open"
                            style="width:100%; display:flex; justify-content:space-between; align-items:center; background:none; border:none; cursor:pointer; padding:0;">
                            <span style="font-size:0.875rem; font-weight:500; color:#1E3A2F;">Cancellation Policy</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7A7A6E" stroke-width="2"
                                :style="open ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s;">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition style="margin-top:0.75rem; font-size:0.82rem; color:#7A7A6E; line-height:1.6;">
                            Free cancellation up to 24 hours before the experience starts.
                            After that, cancellations are non-refundable.
                        </div>
                    </div>
                </div>

                {{-- Secure Payment --}}
                <div style="text-align:center; margin-top:1.25rem;">
                    <div style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.5rem;">Secure Payment Via</div>
                    <div style="display:flex; align-items:center; justify-content:center; gap:0.75rem; color:#7A7A6E; font-size:0.85rem; font-weight:600;">
                        <span>Midtrans</span>
                        <span style="font-size:1rem;">⠿</span>
                        <span style="font-size:1rem;">🏦</span>
                        <span style="font-size:1rem;">💳</span>
                    </div>
                </div>
            </div>

            {{-- ══ KANAN: Your Details ══ --}}
            <div>
                <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-size:1.75rem; font-weight:500; color:#1E3A2F; margin-bottom:1.25rem;">
                    Your Details
                </h2>

                <div style="background:white; border-radius:16px; border:1.5px solid #EDE7DC; padding:1.75rem;">

                    <form method="POST" action="{{ route('checkout.store', $experience->slug) }}">
                        @csrf
                        <input type="hidden" name="date"   value="{{ $date }}">
                        <input type="hidden" name="time"   value="{{ $time }}">
                        <input type="hidden" name="guests" value="{{ $guests }}">

                        {{-- Nama & Email --}}
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                            <div>
                                <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Full Name</label>
                                <input type="text" name="full_name" value="{{ Auth::user()->name }}" readonly
                                    style="width:100%; padding:0.75rem 1rem; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.875rem; font-family:'DM Sans',sans-serif; color:#1E3A2F; background:#F9F9F7; outline:none; box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Email Address</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" readonly
                                    style="width:100%; padding:0.75rem 1rem; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.875rem; font-family:'DM Sans',sans-serif; color:#1E3A2F; background:#F9F9F7; outline:none; box-sizing:border-box;">
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div style="margin-bottom:1rem;">
                            <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Phone Number</label>
                            <div style="display:flex; border:1.5px solid #E2DDD5; border-radius:8px; overflow:hidden;"
                                onfocusin="this.style.borderColor='#1E3A2F'"
                                onfocusout="this.style.borderColor='#E2DDD5'">
                                <div style="padding:0.75rem 1rem; background:#F7F3ED; font-size:0.875rem; color:#1E3A2F; font-weight:500; border-right:1.5px solid #E2DDD5; white-space:nowrap;">
                                    +62
                                </div>
                                <input type="text" name="phone_number"
                                    value="{{ Auth::user()->phone_number ?? '' }}"
                                    placeholder="812 3456 7890"
                                    style="flex:1; padding:0.75rem 1rem; border:none; font-size:0.875rem; font-family:'DM Sans',sans-serif; color:#1E3A2F; outline:none; background:white;">
                            </div>
                            @error('phone_number')
                                <div style="color:#C0392B; font-size:0.78rem; margin-top:0.3rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div style="margin-bottom:1.5rem;">
                            <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">
                                Notes for Host <span style="font-weight:400; text-transform:none;">(Optional)</span>
                            </label>
                            <textarea name="notes_for_host" rows="3"
                                placeholder="Any dietary restrictions, special requests, or questions for your host?"
                                style="width:100%; padding:0.75rem 1rem; border:1.5px solid #E2DDD5; border-radius:8px; font-size:0.875rem; font-family:'DM Sans',sans-serif; color:#1E3A2F; outline:none; resize:none; box-sizing:border-box; line-height:1.5;"
                                onfocus="this.style.borderColor='#1E3A2F'"
                                onblur="this.style.borderColor='#E2DDD5'">{{ old('notes_for_host') }}</textarea>
                        </div>

                        {{-- Terms & Conditions --}}
                        <div style="margin-bottom:1.5rem;">
                            <label style="display:flex; align-items:flex-start; gap:0.6rem; cursor:pointer;">
                                <input type="checkbox" name="agree_terms" value="1" style="margin-top:2px; flex-shrink:0; accent-color:#1E3A2F;">
                                <span style="font-size:0.82rem; color:#4A4A4A; line-height:1.5;">
                                    I agree to CittaLoka's
                                    <a href="#" style="color:#1E3A2F; text-decoration:underline;">Terms & Conditions</a>
                                    and
                                    <a href="#" style="color:#1E3A2F; text-decoration:underline;">Cancellation Policy</a>.
                                </span>
                            </label>
                            @error('agree_terms')
                                <div style="color:#C0392B; font-size:0.78rem; margin-top:0.3rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            style="width:100%; padding:0.95rem; background:#1E3A2F; color:white; border:none; border-radius:10px; font-size:0.95rem; font-weight:600; font-family:'DM Sans',sans-serif; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.5rem;"
                            onmouseover="this.style.background='#2D4A32'"
                            onmouseout="this.style.background='#1E3A2F'">
                            Proceed to Payment
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>

                        {{-- Security Badge --}}
                        <div style="display:flex; align-items:center; justify-content:center; gap:1rem; margin-top:1rem; font-size:0.75rem; color:#9CA3AF;">
                            <span style="display:flex; align-items:center; gap:0.3rem;">
                                🔒 Secured by Midtrans
                            </span>
                            <span>•</span>
                            <span style="display:flex; align-items:center; gap:0.3rem;">
                                🛡️ SSL encrypted
                            </span>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection