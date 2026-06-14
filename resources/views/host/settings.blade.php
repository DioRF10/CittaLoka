@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')

{{-- Success/Error --}}
@if(session('success'))
    <div style="background:#EBF5EE; border:1px solid #B8DFC8; color:#2D5240; padding:0.75rem 1rem; border-radius:8px; font-size:0.875rem; margin-bottom:1.25rem;">✓ {{ session('success') }}</div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; align-items:start;">

    {{-- Profile Settings --}}
    <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #EDE7DC;">
            <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.2rem; font-weight:500; color:#1E3A2F;">Profile</h3>
        </div>
        <div style="padding:1.5rem;">
            <form method="POST" action="{{ route('host.settings.update') }}">
                @csrf
                @method('PUT')

                {{-- Avatar --}}
                <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; padding-bottom:1.5rem; border-bottom:1px solid #EDE7DC;">
                    <img src="{{ auth()->user()->avatar ?? "https://ui-avatars.com/api/?name=" . urlencode(auth()->user()->name) . "&background=1E3A2F&color=fff&size=64" }}" alt=""
                        style="width:64px; height:64px; border-radius:50%; object-fit:cover; border:2px solid #EDE7DC;">
                    <div>
                        <div style="font-size:0.875rem; font-weight:500; color:#1E3A2F; margin-bottom:0.2rem;">{{ auth()->user()->name }}</div>
                        <div style="font-size:0.78rem; color:#7A7A6E;">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                {{-- Name --}}
                <div style="margin-bottom:1rem;">
                    <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Full Name</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                        style="width:100%; padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.875rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; box-sizing:border-box;"
                        onfocus="this.style.borderColor='#1E3A2F'" onblur="this.style.borderColor='#EDE7DC'">
                </div>

                {{-- Bio --}}
                <div style="margin-bottom:1rem;">
                    <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Bio</label>
                    <textarea name="bio" rows="3"
                        placeholder="Tell guests about yourself and your expertise..."
                        style="width:100%; padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.875rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; box-sizing:border-box; resize:none; line-height:1.5;"
                        onfocus="this.style.borderColor='#1E3A2F'" onblur="this.style.borderColor='#EDE7DC'">{{ $host->bio }}</textarea>
                </div>

                {{-- Village --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Village / Area</label>
                    <input type="text" name="village" value="{{ $host->village }}" placeholder="e.g. Ubud, Gianyar"
                        style="width:100%; padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.875rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; box-sizing:border-box;"
                        onfocus="this.style.borderColor='#1E3A2F'" onblur="this.style.borderColor='#EDE7DC'">
                </div>

                <button type="submit"
                    style="padding:0.75rem 1.5rem; background:#1E3A2F; color:white; border:none; border-radius:8px; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s;"
                    onmouseover="this.style.background='#2D4A32'"
                    onmouseout="this.style.background='#1E3A2F'">
                    Save Profile
                </button>
            </form>
        </div>
    </div>

    {{-- Bank Settings --}}
    <div style="background:white; border-radius:12px; border:1.5px solid #EDE7DC; overflow:hidden;">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #EDE7DC;">
            <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.2rem; font-weight:500; color:#1E3A2F;">Bank Account</h3>
            <p style="font-size:0.78rem; color:#7A7A6E; margin-top:0.25rem;">For receiving your earnings payouts</p>
        </div>
        <div style="padding:1.5rem;">
            <form method="POST" action="{{ route('host.settings.update') }}">
                @csrf
                @method('PUT')

                {{-- Bank Name --}}
                <div style="margin-bottom:1rem;">
                    <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Bank Name</label>
                    <select name="bank_name"
                        style="width:100%; padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.875rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; background:white;">
                        <option value="">Select bank...</option>
                        @foreach(['BCA','BNI','BRI','Mandiri','CIMB Niaga','Danamon','Permata','BTN'] as $bank)
                            <option value="{{ $bank }}" {{ $host->bank_name === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Account Name --}}
                <div style="margin-bottom:1rem;">
                    <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Account Holder Name</label>
                    <input type="text" name="bank_account_name" value="{{ $host->bank_account_name }}"
                        placeholder="As per bank records"
                        style="width:100%; padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.875rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; box-sizing:border-box;"
                        onfocus="this.style.borderColor='#1E3A2F'" onblur="this.style.borderColor='#EDE7DC'">
                </div>

                {{-- Account Number --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="font-size:0.65rem; font-weight:700; color:#7A7A6E; text-transform:uppercase; letter-spacing:0.08em; display:block; margin-bottom:0.4rem;">Account Number</label>
                    <input type="text" name="bank_account_number" value="{{ $host->bank_account_number }}"
                        placeholder="e.g. 1234567890"
                        style="width:100%; padding:0.75rem 1rem; border:1.5px solid #EDE7DC; border-radius:8px; font-size:0.875rem; color:#1E3A2F; outline:none; font-family:'DM Sans',sans-serif; box-sizing:border-box;"
                        onfocus="this.style.borderColor='#1E3A2F'" onblur="this.style.borderColor='#EDE7DC'">
                </div>

                <button type="submit"
                    style="padding:0.75rem 1.5rem; background:#1E3A2F; color:white; border:none; border-radius:8px; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all 0.2s;"
                    onmouseover="this.style.background='#2D4A32'"
                    onmouseout="this.style.background='#1E3A2F'">
                    Save Bank Info
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
