<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batalkan Booking | CittaLoka</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600&family=DM+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green-dark: #1A2E1C;
            --terracotta: #C4783A;
            --cream: #F7F3ED;
            --gray-text: #6B7280;
            --gray-border: #E8E4DC;
            --danger-bg: #FEF2F2;
            --danger-border: #FECACA;
            --danger-text: #C0392B;
            --success-bg: #EBF5EE;
            --success-border: #B8DFC8;
            --success-text: #2D5240;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #FAFAF8;
            color: #1C1C1C;
        }

        .container-wrap {
            max-width: 600px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        .card {
            background: #fff;
            border: 1px solid var(--gray-border);
            border-radius: 16px;
            padding: 2rem;
        }

        .icon-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--danger-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.65rem;
            font-weight: 600;
            color: var(--green-dark);
            margin-bottom: 0.5rem;
        }

        .subtitle {
            font-size: 0.875rem;
            color: var(--gray-text);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .booking-summary {
            background: var(--cream);
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .booking-summary-title {
            font-weight: 700;
            color: var(--green-dark);
            font-size: 0.95rem;
            margin-bottom: 0.3rem;
        }

        .booking-summary-detail {
            font-size: 0.82rem;
            color: var(--gray-text);
        }

        .refund-box {
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            margin-bottom: 1.5rem;
            border: 1.5px solid;
        }

        .refund-box.has-refund {
            background: var(--success-bg);
            border-color: var(--success-border);
        }

        .refund-box.no-refund {
            background: var(--danger-bg);
            border-color: var(--danger-border);
        }

        .refund-title {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .refund-box.has-refund .refund-title {
            color: var(--success-text);
        }

        .refund-box.no-refund .refund-title {
            color: var(--danger-text);
        }

        .refund-text {
            font-size: 0.82rem;
            color: #4A4A4A;
            line-height: 1.5;
        }

        .field-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .textarea-input {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 1.5px solid var(--gray-border);
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            resize: vertical;
            box-sizing: border-box;
            margin-bottom: 1.5rem;
        }

        .actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn-cancel-action {
            flex: 1;
            padding: 0.75rem;
            border-radius: 10px;
            border: none;
            background: var(--danger-text);
            color: #fff;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-back {
            flex: 1;
            padding: 0.75rem;
            border-radius: 10px;
            border: 1.5px solid var(--gray-border);
            background: #fff;
            color: #374151;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
    </style>
</head>

<body>

    @include('components.shared.navbar')

    <div class="container-wrap">
        <div class="card">
            <div class="icon-circle">⚠️</div>
            <h1 class="title">Batalkan Booking?</h1>
            <p class="subtitle">Tindakan ini tidak dapat dibatalkan. Pastikan Anda sudah membaca kebijakan refund di
                bawah ini.</p>

            <div class="booking-summary">
                <div class="booking-summary-title">{{ $booking->experience_title_snapshot }}</div>
                <div class="booking-summary-detail">
                    {{ \Carbon\Carbon::parse($booking->tanggal_experience)->locale('id')->isoFormat('D MMMM YYYY') }} ·
                    {{ \Carbon\Carbon::parse($booking->jam_experience)->format('H:i') }} WITA ·
                    {{ $booking->jumlah_peserta }} peserta
                </div>
            </div>

            <div class="refund-box {{ $refundAmount > 0 ? 'has-refund' : 'no-refund' }}">
                <div class="refund-title">
                    {{ $refundAmount > 0 ? '✓ Refund ' . $refundPercentage . '%' : '✕ Tidak Ada Refund' }}
                </div>
                <div class="refund-text">{{ $policyDescription }}</div>
            </div>

            <form method="POST" action="{{ route('bookings.cancel', $booking->kode_booking) }}">
                @csrf
                @method('PATCH')

                <label class="field-label">Alasan pembatalan (opsional)</label>
                <textarea name="reason" class="textarea-input" rows="3"
                    placeholder="Tell us why you're canceling this booking..."></textarea>

                <div class="actions">
                    <a href="{{ route('bookings.show', $booking->kode_booking) }}" class="btn-back">Batal, Kembali</a>
                    <button type="submit" class="btn-cancel-action">Ya, Batalkan Booking</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.shared.footer')

</body>

</html>