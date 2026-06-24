<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menjadi Host | CittaLoka</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green-dark: #1A2E1C;
            --green-mid: #1E3A2F;
            --terracotta: #C4783A;
            --cream: #F7F3ED;
            --cream-light: #FAFAF8;
            --gray-text: #6B7280;
            --gray-border: #E8E4DC;
            --success-bg: #EBF5EE;
            --success-text: #2D5240;
            --success-border: #B8DFC8;
            --warn-bg: #FDF6EE;
            --warn-text: #C4783A;
            --warn-border: #F0DFC0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream-light);
            color: #1C1C1C;
            margin: 0;
        }

        [x-cloak] {
            display: none !important;
        }

        /* ── Shell Layout ── */
        .onboarding-shell {
            display: grid;
            grid-template-columns: 380px 1fr;
            min-height: 100vh;
        }

        @media (max-width: 900px) {
            .onboarding-shell {
                grid-template-columns: 1fr;
            }
        }

        /* ── Left Panel ── */
        .left-panel {
            background: var(--cream);
            padding: 2.5rem 2.25rem;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        @media (max-width: 900px) {
            .left-panel {
                position: relative;
                height: auto;
                padding: 1.75rem 1.5rem;
            }
        }

        .left-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 2.5rem;
        }

        .left-logo-mark {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--green-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cream);
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .left-logo-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--green-dark);
            line-height: 1.1;
        }

        .left-logo-tagline {
            font-size: 0.68rem;
            color: var(--gray-text);
            letter-spacing: 0.03em;
        }

        .left-eyebrow {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--terracotta);
            margin-bottom: 0.6rem;
        }

        .left-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 500;
            color: var(--green-dark);
            line-height: 1.2;
            margin-bottom: 0.9rem;
        }

        .left-desc {
            font-size: 0.88rem;
            color: var(--gray-text);
            line-height: 1.65;
            margin-bottom: 1.75rem;
        }

        .left-image {
            border-radius: 14px;
            aspect-ratio: 3/4;
            background: linear-gradient(160deg, #1E3A2F 0%, #2D4A32 55%, #C4783A 130%);
            margin-bottom: 1.5rem;
            flex-grow: 1;
            min-height: 180px;
            display: flex;
            align-items: flex-end;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .left-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 20%, rgba(255, 255, 255, 0.08), transparent 60%);
        }

        .left-image-caption {
            color: rgba(255, 255, 255, 0.85);
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.95rem;
            font-style: italic;
            position: relative;
            z-index: 1;
        }

        .left-info-box {
            background: #fff;
            border: 1px solid var(--gray-border);
            border-radius: 12px;
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .left-info-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.95rem;
        }

        .left-info-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.2rem;
        }

        .left-info-text {
            font-size: 0.78rem;
            color: var(--gray-text);
            line-height: 1.5;
        }

        .left-help-box {
            background: #fff;
            border: 1px solid var(--gray-border);
            border-radius: 12px;
            padding: 1.1rem;
            margin-top: auto;
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .left-help-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .left-help-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.15rem;
        }

        .left-help-text {
            font-size: 0.74rem;
            color: var(--gray-text);
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }

        .btn-help {
            font-size: 0.74rem;
            font-weight: 600;
            color: var(--green-dark);
            background: transparent;
            border: 1.5px solid var(--gray-border);
            padding: 0.35rem 0.8rem;
            border-radius: 8px;
            cursor: pointer;
        }

        /* ── Right Panel ── */
        .right-panel {
            padding: 2rem 3rem 4rem;
            max-width: 980px;
        }

        @media (max-width: 900px) {
            .right-panel {
                padding: 1.5rem 1.25rem 3rem;
            }
        }

        /* ── Top Bar ── */
        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.75rem;
        }

        .btn-save-later {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--green-dark);
            background: #fff;
            border: 1.5px solid var(--gray-border);
            padding: 0.55rem 1.1rem;
            border-radius: 10px;
            cursor: pointer;
        }

        /* ── Step Indicator ── */
        .step-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 2.5rem;
            overflow-x: auto;
        }

        .step-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
            min-width: 90px;
        }

        .step-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            background: #fff;
            border: 2px solid var(--gray-border);
            color: var(--gray-text);
            transition: all 0.25s;
        }

        .step-circle.done {
            background: var(--green-dark);
            border-color: var(--green-dark);
            color: #fff;
        }

        .step-circle.active {
            background: var(--green-dark);
            border-color: var(--green-dark);
            color: #fff;
        }

        .step-label {
            font-size: 0.7rem;
            color: var(--gray-text);
            margin-top: 0.5rem;
            text-align: center;
            white-space: nowrap;
        }

        .step-label.active-label {
            color: var(--green-dark);
            font-weight: 700;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: var(--gray-border);
            margin: 0 0.25rem;
            margin-bottom: 1.6rem;
            min-width: 24px;
        }

        .step-line.done {
            background: var(--green-dark);
        }

        /* ── Form Card ── */
        .form-card {
            background: #fff;
            border: 1px solid var(--gray-border);
            border-radius: 18px;
            padding: 2.25rem 2.5rem;
        }

        @media (max-width: 640px) {
            .form-card {
                padding: 1.5rem 1.25rem;
            }
        }

        .form-header {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.75rem;
        }

        .form-header-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .form-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--green-dark);
            margin: 0 0 0.3rem;
        }

        .form-subtitle {
            font-size: 0.85rem;
            color: var(--gray-text);
            line-height: 1.5;
            margin: 0;
        }

        .field-required-note {
            font-size: 0.75rem;
            color: var(--gray-text);
            margin-bottom: 1.5rem;
        }

        .field-group {
            margin-bottom: 1.5rem;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        @media (max-width: 640px) {
            .field-row {
                grid-template-columns: 1fr;
            }
        }

        .field-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .field-label .optional {
            color: var(--gray-text);
            font-weight: 400;
        }

        .field-label .required {
            color: var(--terracotta);
        }

        .field-hint {
            font-size: 0.75rem;
            color: var(--gray-text);
            margin-top: 0.35rem;
        }

        .text-input,
        .textarea-input,
        .select-input {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 1.5px solid var(--gray-border);
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: 'DM Sans', sans-serif;
            color: #1C1C1C;
            outline: none;
            transition: border-color 0.15s;
            background: #fff;
        }

        .text-input:focus,
        .textarea-input:focus,
        .select-input:focus {
            border-color: var(--green-dark);
        }

        .textarea-input {
            resize: vertical;
            line-height: 1.6;
        }

        .char-count {
            text-align: right;
            font-size: 0.72rem;
            color: #9CA3AF;
            margin-top: 0.3rem;
        }

        /* ── Radio Cards (Bahasa) ── */
        .radio-card-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .radio-card-grid {
                grid-template-columns: 1fr;
            }
        }

        .radio-card {
            border: 1.5px solid var(--gray-border);
            border-radius: 12px;
            padding: 1.1rem;
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
        }

        .radio-card:hover {
            border-color: #C4BEB1;
        }

        .radio-card.selected {
            border-color: var(--green-dark);
            background: var(--cream-light);
        }

        .radio-card-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid var(--gray-border);
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .radio-card.selected .radio-card-dot {
            border-color: var(--green-dark);
        }

        .radio-card-dot-inner {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--green-dark);
        }

        .radio-card-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.35rem;
        }

        .radio-card-desc {
            font-size: 0.78rem;
            color: var(--gray-text);
            line-height: 1.45;
        }

        .badge-recommended {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--terracotta);
            background: var(--warn-bg);
            padding: 0.15rem 0.5rem;
            border-radius: 999px;
            margin-top: 0.5rem;
        }

        /* ── Expertise Grid (checkbox cards) ── */
        .expertise-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.85rem;
        }

        @media (max-width: 640px) {
            .expertise-grid {
                grid-template-columns: 1fr;
            }
        }

        .expertise-card {
            border: 1.5px solid var(--gray-border);
            border-radius: 12px;
            padding: 0.9rem 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            transition: all 0.15s;
            position: relative;
        }

        .expertise-card:hover {
            border-color: #C4BEB1;
        }

        .expertise-card.selected {
            border-color: var(--green-dark);
            background: var(--cream-light);
        }

        .expertise-icon {
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .expertise-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: #374151;
        }

        .expertise-check {
            position: absolute;
            top: 0.65rem;
            right: 0.65rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--green-dark);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
        }

        /* ── Info / Tip Boxes ── */
        .tip-box {
            background: var(--cream);
            border-radius: 12px;
            padding: 1rem 1.1rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .tip-box.success {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
        }

        .tip-box.warn {
            background: var(--warn-bg);
            border: 1px solid var(--warn-border);
        }

        .tip-box.info {
            background: #EBF1F5;
            border: 1px solid #C9DCE8;
        }

        .tip-icon {
            font-size: 1rem;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .tip-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.2rem;
        }

        .tip-text {
            font-size: 0.79rem;
            color: var(--gray-text);
            line-height: 1.5;
        }

        /* ── Avatar Upload ── */
        .avatar-upload-row {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .avatar-preview {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            border: 1.5px solid var(--gray-border);
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-upload-photo {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--green-dark);
            background: #fff;
            border: 1.5px solid var(--gray-border);
            padding: 0.5rem 1.1rem;
            border-radius: 9px;
            cursor: pointer;
        }

        /* ── KTP Upload Cards ── */
        .ktp-upload-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 640px) {
            .ktp-upload-grid {
                grid-template-columns: 1fr;
            }
        }

        .ktp-card {
            border: 1.5px solid var(--gray-border);
            border-radius: 12px;
            padding: 1.1rem;
        }

        .ktp-card-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.7rem;
        }

        .ktp-preview-box {
            aspect-ratio: 16/10;
            border-radius: 10px;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
            overflow: hidden;
            border: 1px dashed var(--gray-border);
        }

        .ktp-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ktp-placeholder-icon {
            font-size: 1.8rem;
            opacity: 0.4;
        }

        .btn-upload-ktp {
            width: 100%;
            padding: 0.65rem;
            border-radius: 9px;
            border: 1.5px solid var(--gray-border);
            background: #fff;
            color: var(--green-dark);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
        }

        .ktp-tips-card {
            background: var(--warn-bg);
            border: 1px solid var(--warn-border);
            border-radius: 12px;
            padding: 1.1rem;
        }

        .ktp-tips-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--terracotta);
            margin-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .ktp-tip-item {
            font-size: 0.78rem;
            color: #8A5A2A;
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .info-checklist {
            margin-bottom: 1.5rem;
        }

        .info-checklist-item {
            display: flex;
            gap: 0.55rem;
            font-size: 0.82rem;
            color: #374151;
            margin-bottom: 0.6rem;
            line-height: 1.5;
        }

        .check-mark {
            color: #16A34A;
            flex-shrink: 0;
        }

        /* ── Bank Status Box ── */
        .bank-status-box {
            border-radius: 12px;
            padding: 1rem 1.1rem;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .bank-status-box.verified {
            background: var(--success-bg);
            border: 1.5px solid var(--success-border);
        }

        .bank-status-box.needs-review {
            background: var(--warn-bg);
            border: 1.5px solid var(--warn-border);
        }

        .bank-status-box.checking {
            background: var(--cream);
            border: 1.5px solid var(--gray-border);
        }

        .bank-status-icon {
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .bank-status-title {
            font-size: 0.84rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .bank-status-box.verified .bank-status-title {
            color: var(--success-text);
        }

        .bank-status-box.needs-review .bank-status-title {
            color: var(--terracotta);
        }

        .bank-status-box.checking .bank-status-title {
            color: var(--green-dark);
        }

        .bank-status-text {
            font-size: 0.78rem;
            color: var(--gray-text);
            line-height: 1.5;
        }

        .spin {
            display: inline-block;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* ── Checkbox custom ── */
        .checkbox-row {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            cursor: pointer;
        }

        .checkbox-custom {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 1.5px solid var(--gray-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 0.1rem;
            transition: all 0.15s;
        }

        .checkbox-custom.checked {
            background: var(--green-dark);
            border-color: var(--green-dark);
            color: #fff;
        }

        .checkbox-text {
            font-size: 0.85rem;
            color: #374151;
            line-height: 1.5;
        }

        /* ── Footer Actions ── */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.7rem 1.4rem;
            border-radius: 10px;
            border: 1.5px solid var(--gray-border);
            background: #fff;
            color: #374151;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-next {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            border-radius: 10px;
            border: none;
            background: var(--green-dark);
            color: #fff;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-next:hover {
            background: var(--green-mid);
        }

        .btn-next:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ── Welcome step specifics ── */
        .welcome-benefit-list {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .welcome-benefit-item {
            display: flex;
            gap: 0.85rem;
            align-items: flex-start;
        }

        .welcome-benefit-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .welcome-benefit-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.15rem;
        }

        .welcome-benefit-desc {
            font-size: 0.78rem;
            color: var(--gray-text);
            line-height: 1.45;
        }

        /* ── Completion screen ── */
        .completion-wrap {
            text-align: center;
            padding: 1rem 0;
        }

        .completion-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .completion-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: var(--green-dark);
            margin-bottom: 0.6rem;
        }

        .completion-sub {
            font-size: 0.9rem;
            color: var(--gray-text);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        /* ── Floating WhatsApp Help ── */
        .floating-help {
            position: fixed;
            bottom: 1.75rem;
            right: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            border: 1.5px solid var(--gray-border);
            border-radius: 999px;
            padding: 0.65rem 1.1rem 0.65rem 0.65rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: var(--green-dark);
            font-size: 0.82rem;
            font-weight: 600;
            z-index: 40;
            transition: transform 0.15s;
        }

        .floating-help:hover {
            transform: translateY(-2px);
        }

        .floating-help-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #25D366;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        @media (max-width: 640px) {
            .floating-help span {
                display: none;
            }

            .floating-help {
                padding: 0.65rem;
            }
        }
    </style>
</head>

<body>
    <div class="onboarding-shell" x-data="onboardingHost()" x-cloak>

        {{-- ═══════════════════════════════════════════════
        LEFT PANEL — context per step
        ═══════════════════════════════════════════════ --}}
        <div class="left-panel">

            <div class="left-logo">
                <div class="left-logo-mark">C</div>
                <div>
                    <div class="left-logo-text">CittaLoka</div>
                    <div class="left-logo-tagline">Soulful Journeys, Rooted in Bali</div>
                </div>
            </div>

            {{-- Step 1 content --}}
            <template x-if="step === 1">
                <div style="display:flex; flex-direction:column; flex:1;">
                    <p class="left-eyebrow">Mengapa Bergabung</p>
                    <h1 class="left-title">Mengapa menjadi<br>Host di CittaLoka?</h1>
                    <p class="left-desc">CittaLoka membantu Anda berbagi pengetahuan dan budaya, sekaligus mendapatkan
                        penghasilan yang adil dari setiap pengalaman yang Anda bagikan.</p>

                    <div class="welcome-benefit-list">
                        <div class="welcome-benefit-item">
                            <div class="welcome-benefit-icon">💰</div>
                            <div>
                                <div class="welcome-benefit-title">90% dari setiap booking</div>
                                <div class="welcome-benefit-desc">Kami hanya mengambil 10% komisi untuk operasional
                                    platform.</div>
                            </div>
                        </div>
                        <div class="welcome-benefit-item">
                            <div class="welcome-benefit-icon">⚡</div>
                            <div>
                                <div class="welcome-benefit-title">Payout otomatis</div>
                                <div class="welcome-benefit-desc">Dana dari booking dicairkan otomatis ke rekening Anda.
                                </div>
                            </div>
                        </div>
                        <div class="welcome-benefit-item">
                            <div class="welcome-benefit-icon">🛡️</div>
                            <div>
                                <div class="welcome-benefit-title">Kurasi & dukungan tim</div>
                                <div class="welcome-benefit-desc">Kami memastikan setiap host berkualitas dan siap
                                    dibantu.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Step 2 content --}}
            <template x-if="step === 2">
                <div style="display:flex; flex-direction:column; flex:1;">
                    <p class="left-eyebrow">Langkah 2 dari 5</p>
                    <h1 class="left-title">Bahasa & lokasi pengalaman Anda</h1>
                    <p class="left-desc">Informasi ini membantu kami menerjemahkan konten Anda dan menampilkan
                        pengalaman Anda kepada wisatawan yang tepat.</p>

                    <div class="left-info-box">
                        <div class="left-info-icon">💡</div>
                        <div>
                            <div class="left-info-title">Kenapa informasi ini penting?</div>
                            <div class="left-info-text">Bahasa dan lokasi membantu kami menerjemahkan cerita Anda secara
                                akurat, serta menampilkan pengalaman Anda ke wisatawan yang relevan.</div>
                        </div>
                    </div>

                    <div class="left-image" style="flex-grow:1;">
                        <span class="left-image-caption">Setiap desa punya cerita untuk dibagikan.</span>
                    </div>
                </div>
            </template>

            {{-- Step 3 content --}}
            <template x-if="step === 3">
                <div style="display:flex; flex-direction:column; flex:1;">
                    <p class="left-eyebrow">Langkah 3 dari 5</p>
                    <h1 class="left-title">Ceritakan tentang diri Anda</h1>
                    <p class="left-desc">Profil yang lengkap membantu wisatawan mengenal Anda dan terinspirasi dengan
                        pengalaman yang Anda bagikan.</p>

                    <div class="left-info-box">
                        <div class="left-info-icon">❤️</div>
                        <div>
                            <div class="left-info-title">Tips</div>
                            <div class="left-info-text">Keaslian cerita Anda adalah kekuatan utama. Bagikan dengan jujur
                                dan autentik tentang diri dan keahlian Anda.</div>
                        </div>
                    </div>

                    <div class="left-image" style="flex-grow:1;">
                        <span class="left-image-caption">Cerita Anda, warisan budaya yang hidup.</span>
                    </div>
                </div>
            </template>

            {{-- Step 4 content --}}
            <template x-if="step === 4">
                <div style="display:flex; flex-direction:column; flex:1;">
                    <p class="left-eyebrow">Langkah 4 dari 5</p>
                    <h1 class="left-title">Verifikasi identitas untuk keamanan bersama</h1>
                    <p class="left-desc">Kami melakukan verifikasi identitas untuk memastikan keamanan komunitas
                        CittaLoka dan membangun kepercayaan dengan wisatawan.</p>

                    <div class="left-info-box">
                        <div class="left-info-icon">🛡️</div>
                        <div>
                            <div class="left-info-title">Data Anda aman</div>
                            <div class="left-info-text">Informasi identitas akan dienkripsi dan hanya digunakan untuk
                                keperluan verifikasi. Kami tidak akan membagikannya ke pihak lain.</div>
                        </div>
                    </div>

                    <div class="left-image" style="flex-grow:1;">
                        <span class="left-image-caption">Kepercayaan dibangun dari transparansi.</span>
                    </div>
                </div>
            </template>

            {{-- Step 5 content --}}
            <template x-if="step === 5">
                <div style="display:flex; flex-direction:column; flex:1;">
                    <p class="left-eyebrow">Langkah 5 dari 5</p>
                    <h1 class="left-title">Informasi rekening untuk pencairan dana</h1>
                    <p class="left-desc">Rekening ini digunakan untuk menerima pembayaran dari setiap booking yang Anda
                        terima. Pastikan datanya benar.</p>

                    <div class="left-info-box">
                        <div class="left-info-icon">⚙️</div>
                        <div>
                            <div class="left-info-title">Bagaimana cara kerjanya?</div>
                            <div class="left-info-text">Kami otomatis memverifikasi nama pemilik rekening Anda begitu
                                disubmit. Jika ada ketidaksesuaian, tim kami akan meninjau secara singkat — Anda tidak
                                perlu menunggu untuk lanjut.</div>
                        </div>
                    </div>

                    <div class="left-image" style="flex-grow:1;">
                        <span class="left-image-caption">Pengalaman yang adil, dimulai dari kepercayaan.</span>
                    </div>
                </div>
            </template>

            {{-- Completion --}}
            <template x-if="step === 6">
                <div style="display:flex; flex-direction:column; flex:1;">
                    <p class="left-eyebrow">Selesai</p>
                    <h1 class="left-title">Selamat datang di komunitas CittaLoka</h1>
                    <p class="left-desc">Anda sudah menjadi bagian dari host yang membagikan budaya Bali kepada dunia.
                    </p>
                    <div class="left-image" style="flex-grow:1;">
                        <span class="left-image-caption">Perjalanan baru dimulai dari sini.</span>
                    </div>
                </div>
            </template>

        </div>


        {{-- ═══════════════════════════════════════════════
        RIGHT PANEL — form per step
        ═══════════════════════════════════════════════ --}}
        <div class="right-panel">

            <div class="top-bar">
                <button class="btn-save-later" type="button" x-on:click="saveAndExit()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
                    </svg>
                    Simpan & Lanjutkan Nanti
                </button>
            </div>

            {{-- Step Indicator --}}
            <div class="step-indicator">
                <template x-for="(label, index) in stepLabels" :key="index">

                    <div style="display:flex; align-items:center; flex:1;">

                        <!-- Garis sebelum node selain node pertama -->
                        <div x-show="index > 0" class="step-line" :class="{ done: step > index }">
                        </div>

                        <!-- Bulatan -->
                        <div class="step-node">
                            <div class="step-circle" :class="{ done: step > index + 1, active: step === index + 1 }">

                                <span x-show="step > index + 1">✓</span>
                                <span x-show="step <= index + 1" x-text="index + 1"></span>

                            </div>

                            <div class="step-label" :class="{ 'active-label': step === index + 1 }" x-text="label">
                            </div>
                        </div>

                    </div>

                </template>
            </div>

            {{-- ═══════════════════ STEP 1: WELCOME ═══════════════════ --}}
            <div x-show="step === 1" class="form-card">
                <h2 class="form-title">Selamat datang, calon Host! 👋</h2>
                <p class="form-subtitle" style="margin-bottom:1.75rem;">Sebelum memulai, mari pahami bagaimana CittaLoka
                    bekerja dan keuntungan menjadi bagian dari kami.</p>

                <div class="welcome-benefit-list" style="margin-bottom:1.75rem;">
                    <div class="welcome-benefit-item">
                        <div class="welcome-benefit-icon">💰</div>
                        <div>
                            <div class="welcome-benefit-title">90% dari setiap booking untuk Anda</div>
                            <div class="welcome-benefit-desc">Kami hanya mengambil 10% komisi yang digunakan untuk
                                operasional platform.</div>
                        </div>
                    </div>
                    <div class="welcome-benefit-item">
                        <div class="welcome-benefit-icon">⚡</div>
                        <div>
                            <div class="welcome-benefit-title">Payout otomatis ke rekening Anda</div>
                            <div class="welcome-benefit-desc">Dana dari booking yang sudah selesai akan dicairkan
                                otomatis tanpa perlu menunggu lama.</div>
                        </div>
                    </div>
                    <div class="welcome-benefit-item">
                        <div class="welcome-benefit-icon">🛡️</div>
                        <div>
                            <div class="welcome-benefit-title">Kurasi & dukungan tim</div>
                            <div class="welcome-benefit-desc">Kami memastikan setiap host berkualitas dan siap
                                memberikan pengalaman terbaik.</div>
                        </div>
                    </div>
                    <div class="welcome-benefit-item">
                        <div class="welcome-benefit-icon">👥</div>
                        <div>
                            <div class="welcome-benefit-title">Komunitas & promosi</div>
                            <div class="welcome-benefit-desc">Bergabung dengan komunitas host lokal dan dapatkan promosi
                                dari CittaLoka.</div>
                        </div>
                    </div>
                </div>

                <div class="tip-box" style="margin-bottom:1.75rem;">
                    <span class="tip-icon">💡</span>
                    <div>
                        <div class="tip-title">Kami percaya setiap pengalaman punya nilai</div>
                        <div class="tip-text">Dari memasak makanan tradisional, membuat kerajinan tangan, hingga upacara
                            adat — semua bisa menjadi pengalaman berharga bagi wisatawan yang mencari makna.</div>
                    </div>
                </div>

                <label class="checkbox-row" style="margin-bottom:1.75rem;">
                    <div class="checkbox-custom" :class="{ checked: form.agreeIntro }"
                        x-on:click="form.agreeIntro = !form.agreeIntro">
                        <span x-show="form.agreeIntro">✓</span>
                    </div>
                    <span class="checkbox-text">Saya sudah memahami informasi di atas dan siap menjadi Host di
                        CittaLoka</span>
                </label>

                <div class="form-actions">
                    <div></div>
                    <button class="btn-next" type="button" :disabled="!form.agreeIntro" x-on:click="step = 2">
                        Lanjutkan
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </div>
            </div>
            {{-- ═══════════════════ STEP 2: BAHASA & LOKASI ═══════════════════ --}}
            <div x-show="step === 2" class="form-card">
                <div class="form-header">
                    <div class="form-header-icon">🌐</div>
                    <div>
                        <h2 class="form-title">Bahasa & Lokasi</h2>
                        <p class="form-subtitle">Informasi ini membantu kami menerjemahkan konten Anda dan menampilkan
                            pengalaman Anda kepada wisatawan yang tepat.</p>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Bahasa Utama <span class="required">*</span></label>
                    <p class="field-hint" style="margin-top:-0.2rem; margin-bottom:0.85rem;">Bahasa yang paling sering
                        Anda gunakan saat berkomunikasi dan membuat konten.</p>

                    <div class="radio-card-grid">
                        <div class="radio-card" :class="{ selected: form.locale === 'id' }"
                            x-on:click="form.locale = 'id'">
                            <div class="radio-card-dot">
                                <div class="radio-card-dot-inner" x-show="form.locale === 'id'"></div>
                            </div>
                            <div class="radio-card-title">Bahasa Indonesia</div>
                            <div class="radio-card-desc">Saya lebih nyaman berkomunikasi dan membuat konten dalam Bahasa
                                Indonesia.</div>
                            <span class="badge-recommended">Disarankan</span>
                        </div>
                        <div class="radio-card" :class="{ selected: form.locale === 'en' }"
                            x-on:click="form.locale = 'en'">
                            <div class="radio-card-dot">
                                <div class="radio-card-dot-inner" x-show="form.locale === 'en'"></div>
                            </div>
                            <div class="radio-card-title">English</div>
                            <div class="radio-card-desc">I am more comfortable communicating and creating content in
                                English.</div>
                        </div>
                        <div class="radio-card" :class="{ selected: form.locale === 'mix' }"
                            x-on:click="form.locale = 'mix'">
                            <div class="radio-card-dot">
                                <div class="radio-card-dot-inner" x-show="form.locale === 'mix'"></div>
                            </div>
                            <div class="radio-card-title">Keduanya (ID & EN)</div>
                            <div class="radio-card-desc">Saya menggunakan Bahasa Indonesia dan English secara
                                bergantian.</div>
                        </div>
                    </div>
                </div>

                <div class="field-group" style="margin-top:1.75rem;">
                    <label class="field-label">Lokasi Anda <span class="required">*</span></label>
                    <p class="field-hint" style="margin-top:-0.2rem; margin-bottom:0.85rem;">Desa atau wilayah tempat
                        Anda tinggal dan berbagi pengalaman.</p>

                    <input type="text" class="text-input" placeholder="Contoh: Desa Mas, Ubud, Gianyar"
                        x-model="form.village" maxlength="100">
                    <p class="field-hint">Tuliskan nama desa, kecamatan, dan kabupaten tempat Anda berada.</p>
                </div>

                <div class="form-actions">
                    <button class="btn-back" type="button" x-on:click="step = 1">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </button>
                    <button class="btn-next" type="button" :disabled="!form.locale || !form.village"
                        x-on:click="saveStep(2)">
                        Lanjutkan
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════ STEP 3: PROFIL HOST ═══════════════════ --}}
            <div x-show="step === 3" class="form-card">
                <div class="form-header">
                    <div class="form-header-icon">👤</div>
                    <div>
                        <h2 class="form-title">Profil Host (Story)</h2>
                        <p class="form-subtitle">Ceritakan siapa Anda dan apa yang ingin Anda bagikan kepada wisatawan.
                        </p>
                    </div>
                </div>
                <p class="field-required-note">Field dengan tanda <span class="required">*</span> wajib diisi.</p>

                <div class="field-row field-group">
                    <div>
                        <label class="field-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" class="text-input" x-model="form.fullName" placeholder="Made Sari">
                    </div>
                    <div>
                        <label class="field-label">Nomor Telepon <span class="required">*</span></label>
                        <input type="text" class="text-input" x-model="form.phoneNumber" placeholder="0812 3456 7890">
                    </div>
                </div>

                <div class="field-row field-group">
                    <div>
                        <label class="field-label">Usia <span class="optional">(Opsional)</span></label>
                        <input type="number" class="text-input" x-model="form.age" placeholder="38" min="17" max="100">
                    </div>
                    <div>
                        <label class="field-label">Foto Profil <span class="optional">(Opsional)</span></label>
                        <div class="avatar-upload-row">
                            <div class="avatar-preview">
                                <img x-show="avatarPreview" :src="avatarPreview" alt="Preview">
                                <span x-show="!avatarPreview" style="font-size:1.5rem;">🙂</span>
                            </div>
                            <div>
                                <button type="button" class="btn-upload-photo"
                                    x-on:click="$refs.avatarInput.click()">Pilih Foto</button>
                                <p class="field-hint" style="margin-top:0.4rem;">JPG/PNG, maks 2MB</p>
                                <input type="file" x-ref="avatarInput" accept="image/jpeg,image/png"
                                    style="display:none;" x-on:change="previewAvatar($event)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Keahlian Utama <span class="required">*</span></label>
                    <p class="field-hint" style="margin-top:-0.2rem; margin-bottom:0.85rem;">Pilih semua keahlian yang
                        Anda miliki atau bagikan.</p>

                    <div class="expertise-grid">
                        <template x-for="opt in expertiseOptions" :key="opt.value">
                            <div class="expertise-card" :class="{ selected: form.expertise.includes(opt.value) }"
                                x-on:click="toggleExpertise(opt.value)">
                                <span class="expertise-icon" x-text="opt.icon"></span>
                                <span class="expertise-label" x-text="opt.label"></span>
                                <span class="expertise-check" x-show="form.expertise.includes(opt.value)">✓</span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Bio Singkat <span class="optional">(Opsional)</span></label>
                    <p class="field-hint" style="margin-top:-0.2rem; margin-bottom:0.6rem;">Perkenalkan diri Anda dalam
                        1–2 kalimat.</p>
                    <textarea class="textarea-input" rows="3" maxlength="300" x-model="form.bio"
                        placeholder="Saya Made Sari, pengrajin anyaman bambu dari Desa Mas, Ubud..."></textarea>
                    <div class="char-count" x-text="`${form.bio.length}/300`"></div>
                </div>

                <div class="field-group">
                    <label class="field-label">Cerita Ringkas <span class="optional">(Opsional)</span></label>
                    <p class="field-hint" style="margin-top:-0.2rem; margin-bottom:0.6rem;">Bagikan perjalanan atau
                        pengalaman yang menginspirasi Anda.</p>
                    <textarea class="textarea-input" rows="4" maxlength="500" x-model="form.story"
                        placeholder="Sejak kecil saya belajar dari ibu dan nenek membuat anyaman bambu..."></textarea>
                    <div class="char-count" x-text="`${form.story.length}/500`"></div>
                </div>

                <div class="tip-box">
                    <span class="tip-icon">🌿</span>
                    <div>
                        <div class="tip-title">Mengapa profil ini penting?</div>
                        <div class="tip-text">Profil Anda akan ditampilkan kepada wisatawan dan membantu mereka memilih
                            pengalaman yang tepat.</div>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn-back" type="button" x-on:click="step = 2">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </button>
                    <button class="btn-next" type="button"
                        :disabled="!form.fullName || !form.phoneNumber || form.expertise.length === 0"
                        x-on:click="saveStep(3)">
                        Lanjutkan
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </div>
            </div>
            {{-- ═══════════════════ STEP 4: VERIFIKASI IDENTITAS ═══════════════════ --}}
            <div x-show="step === 4" class="form-card">
                <div class="form-header">
                    <div class="form-header-icon">🛡️</div>
                    <div>
                        <h2 class="form-title">Verifikasi Identitas</h2>
                        <p class="form-subtitle">Unggah foto KTP Anda untuk verifikasi akun Host. Proses ini membantu
                            kami menjaga keamanan platform dan kepercayaan wisatawan.</p>
                    </div>
                </div>

                <div class="tip-box info">
                    <span class="tip-icon">🕐</span>
                    <div>
                        <div class="tip-title" style="color:#2563A8;">Proses verifikasi: 1 x 24 jam (hari kerja)</div>
                        <div class="tip-text">Tim kami akan memeriksa dokumen Anda dan mengirimkan hasilnya melalui
                            email.</div>
                    </div>
                </div>

                <label class="field-label" style="display:block; margin-bottom:0.3rem;">Unggah KTP</label>
                <p class="field-hint" style="margin-bottom:1rem;">Pastikan foto KTP jelas, tidak buram, dan semua
                    informasi terlihat.</p>

                <div class="ktp-upload-grid">
                    <div class="ktp-card">
                        <div class="ktp-card-label">Foto KTP Bagian Depan <span class="required">*</span></div>
                        <div class="ktp-preview-box">
                            <img x-show="ktpFrontPreview" :src="ktpFrontPreview" alt="KTP depan">
                            <span class="ktp-placeholder-icon" x-show="!ktpFrontPreview">🪪</span>
                        </div>
                        <button type="button" class="btn-upload-ktp" x-on:click="$refs.ktpFrontInput.click()">↑ Upload
                            Foto Depan</button>
                        <input type="file" x-ref="ktpFrontInput" accept="image/jpeg,image/png" style="display:none;"
                            x-on:change="previewKtp($event, 'front')">
                        <p class="field-hint" style="text-align:center;">JPG, PNG maks 2MB</p>
                    </div>

                    <div class="ktp-card">
                        <div class="ktp-card-label">Foto Selfie dengan KTP <span class="required">*</span></div>
                        <div class="ktp-preview-box">
                            <img x-show="ktpSelfiePreview" :src="ktpSelfiePreview" alt="Selfie dengan KTP">
                            <span class="ktp-placeholder-icon" x-show="!ktpSelfiePreview">🤳</span>
                        </div>
                        <button type="button" class="btn-upload-ktp" x-on:click="$refs.ktpSelfieInput.click()">↑ Upload
                            Foto Selfie</button>
                        <input type="file" x-ref="ktpSelfieInput" accept="image/jpeg,image/png" style="display:none;"
                            x-on:change="previewKtp($event, 'selfie')">
                        <p class="field-hint" style="text-align:center;">Wajah & KTP terlihat jelas, JPG/PNG maks 2MB
                        </p>
                    </div>
                </div>

                <div class="info-checklist">
                    <div class="info-checklist-item"><span class="check-mark">✓</span> Nama pada KTP harus sesuai dengan
                        nama di profil Anda.</div>
                    <div class="info-checklist-item"><span class="check-mark">✓</span> Pada foto selfie, pastikan wajah
                        dan KTP yang Anda pegang terlihat jelas dan tidak terpotong.</div>
                    <div class="info-checklist-item"><span class="check-mark">✓</span> Verifikasi ini tidak akan
                        mempengaruhi privasi data Anda.</div>
                    <div class="info-checklist-item"><span class="check-mark">✓</span> Anda tetap dapat melanjutkan
                        onboarding — proses verifikasi dilakukan setelah Anda menyelesaikan semua langkah.</div>
                </div>

                <div class="tip-box info" style="margin-bottom:0;">
                    <span class="tip-icon">ℹ️</span>
                    <div style="flex:1;">
                        <div class="tip-title" style="color:#2563A8;">Belum punya KTP?</div>
                        <div class="tip-text">Jika Anda belum memiliki KTP, silakan hubungi tim kami untuk alternatif
                            verifikasi lainnya.</div>
                    </div>
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn-help"
                        style="flex-shrink:0; text-decoration:none; display:inline-flex; align-items:center;">WhatsApp
                        Kami</a>
                </div>

                <div class="form-actions">
                    <button class="btn-back" type="button" x-on:click="step = 3">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </button>
                    <button class="btn-next" type="button" :disabled="!ktpFrontFile || !ktpSelfieFile"
                        x-on:click="saveStep(4)">
                        Lanjutkan
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════ STEP 5: INFORMASI BANK ═══════════════════ --}}
            <div x-show="step === 5" class="form-card">
                <div class="form-header">
                    <div class="form-header-icon">🏦</div>
                    <div>
                        <h2 class="form-title">Informasi Bank</h2>
                        <p class="form-subtitle">Rekening ini digunakan untuk menerima pencairan dana dari setiap
                            booking yang Anda terima.</p>
                    </div>
                </div>

                <div class="field-row field-group">
                    <div>
                        <label class="field-label">Nama Bank <span class="required">*</span></label>
                        <select class="select-input" x-model="form.bankName">
                            <option value="">Pilih bank</option>
                            <option value="BCA">BCA</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BNI">BNI</option>
                            <option value="BRI">BRI</option>
                            <option value="BSI">BSI</option>
                            <option value="CIMB Niaga">CIMB Niaga</option>
                            <option value="Danamon">Danamon</option>
                            <option value="Permata">Permata</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Nomor Rekening <span class="required">*</span></label>
                        <input type="text" class="text-input" x-model="form.bankAccountNumber" placeholder="1234567890"
                            inputmode="numeric">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Nama Pemilik Rekening <span class="required">*</span></label>
                    <input type="text" class="text-input" x-model="form.bankAccountName"
                        placeholder="Sesuai dengan nama di KTP">
                    <p class="field-hint">Pastikan nama ini sesuai dengan nama di profil Anda untuk proses verifikasi
                        yang lebih cepat.</p>
                </div>

                {{-- Bank verification status box --}}
                <div class="bank-status-box checking" x-show="bankCheckState === 'checking'">
                    <span class="bank-status-icon spin">⏳</span>
                    <div>
                        <div class="bank-status-title">Memverifikasi rekening...</div>
                        <div class="bank-status-text">Mohon tunggu sebentar.</div>
                    </div>
                </div>

                <div class="bank-status-box verified" x-show="bankCheckState === 'verified'">
                    <span class="bank-status-icon">✅</span>
                    <div>
                        <div class="bank-status-title">Rekening terverifikasi!</div>
                        <div class="bank-status-text" x-text="bankCheckMessage"></div>
                    </div>
                </div>

                <div class="bank-status-box needs-review" x-show="bankCheckState === 'needs_review'">
                    <span class="bank-status-icon">⏳</span>
                    <div>
                        <div class="bank-status-title">Rekening tersimpan, perlu peninjauan singkat</div>
                        <div class="bank-status-text" x-text="bankCheckMessage"></div>
                    </div>
                </div>

                <label class="checkbox-row" style="margin-bottom:0.5rem; margin-top:0.5rem;">
                    <div class="checkbox-custom" :class="{ checked: form.confirmBank }"
                        x-on:click="form.confirmBank = !form.confirmBank">
                        <span x-show="form.confirmBank">✓</span>
                    </div>
                    <span class="checkbox-text">Saya menyatakan data rekening di atas sudah benar dan dapat
                        dipertanggungjawabkan.</span>
                </label>

                <div class="form-actions">
                    <button class="btn-back" type="button" x-on:click="step = 4">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </button>
                    <button class="btn-next" type="button"
                        :disabled="!form.bankName || !form.bankAccountNumber || !form.bankAccountName || !form.confirmBank || bankCheckState === 'checking'"
                        x-on:click="saveStep(5)">
                        <span x-show="bankCheckState !== 'checking'">Selesaikan Pendaftaran</span>
                        <span x-show="bankCheckState === 'checking'">Memverifikasi...</span>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════ STEP 6: COMPLETION ═══════════════════ --}}
            <div x-show="step === 6" class="form-card completion-wrap">
                <div class="completion-icon">🏡</div>
                <h2 class="completion-title">Selamat, Anda resmi menjadi Host!</h2>
                <p class="completion-sub">Selamat bergabung dengan komunitas host CittaLoka. KTP Anda sedang ditinjau —
                    kami akan memberi tahu hasilnya dalam 24 jam melalui email.</p>

                <div class="bank-status-box verified" style="text-align:left;" x-show="finalBankStatus === 'verified'">
                    <span class="bank-status-icon">✅</span>
                    <div>
                        <div class="bank-status-title">Rekening terverifikasi!</div>
                        <div class="bank-status-text">Anda siap menerima pencairan dana dari booking pertama Anda.</div>
                    </div>
                </div>

                <div class="bank-status-box needs-review" style="text-align:left;"
                    x-show="finalBankStatus === 'needs_review'">
                    <span class="bank-status-icon">⏳</span>
                    <div>
                        <div class="bank-status-title">Rekening tersimpan</div>
                        <div class="bank-status-text">Tim kami akan meninjau secara singkat sebelum pencairan dana
                            pertama. Anda tidak perlu mengulang langkah ini.</div>
                    </div>
                </div>

                <p class="field-hint" style="margin-bottom:1.5rem;">Anda sudah dapat membuka dashboard sambil menunggu
                    verifikasi KTP selesai.</p>

                <button class="btn-next" type="button" style="margin:0 auto;"
                    x-on:click="window.location.href = dashboardUrl">
                    Buka Dashboard
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Floating WhatsApp Help — satu tempat untuk semua step --}}
    <a href="https://wa.me/6281234567890" target="_blank" class="floating-help">
        <span class="floating-help-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#fff">
                <path
                    d="M17.6 6.32A8.86 8.86 0 0 0 12 4a8.94 8.94 0 0 0-7.92 13.08L3 21l3.92-1.04A8.94 8.94 0 0 0 12 21a8.94 8.94 0 0 0 8.94-9 8.86 8.86 0 0 0-3.34-5.68zM12 19.4a7.4 7.4 0 0 1-3.78-1.04l-.27-.16-2.32.62.62-2.26-.18-.28A7.43 7.43 0 1 1 19.4 12 7.43 7.43 0 0 1 12 19.4z" />
            </svg>
        </span>
        <span>Butuh bantuan?</span>
    </a>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function onboardingHost() {
            return {
                step: 1,
                stepLabels: ['Welcome', 'Bahasa & Lokasi', 'Profil Host', 'Verifikasi Identitas', 'Informasi Bank'],
                dashboardUrl: '{{ route("host.dashboard") }}',

                form: {
                    agreeIntro: false,
                    locale: 'id',
                    village: '',
                    fullName: '{{ auth()->user()->name ?? "" }}',
                    phoneNumber: '',
                    age: '',
                    expertise: [],
                    bio: '',
                    story: '',
                    bankName: '',
                    bankAccountNumber: '',
                    bankAccountName: '',
                    confirmBank: false,
                },

                expertiseOptions: [
                    { value: 'craft', icon: '🧺', label: 'Traditional Crafts' },
                    { value: 'cooking', icon: '🍲', label: 'Cooking & Culinary' },
                    { value: 'spiritual', icon: '🕉️', label: 'Spiritual & Meditation' },
                    { value: 'farming', icon: '🌾', label: 'Farming & Agriculture' },
                    { value: 'dance', icon: '💃', label: 'Dance & Performance' },
                    { value: 'ceremony', icon: '🛕', label: 'Ceremony & Ritual' },
                    { value: 'others', icon: '✨', label: 'Others (Lainnya)' },
                ],

                avatarPreview: null,
                avatarFile: null,
                ktpFrontPreview: null,
                ktpFrontFile: null,
                ktpSelfiePreview: null,
                ktpSelfieFile: null,

                bankCheckState: '',     // '' | 'checking' | 'verified' | 'needs_review'
                bankCheckMessage: '',
                finalBankStatus: '',

                toggleExpertise(value) {
                    const idx = this.form.expertise.indexOf(value);
                    if (idx === -1) {
                        this.form.expertise.push(value);
                    } else {
                        this.form.expertise.splice(idx, 1);
                    }
                },

                previewAvatar(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.avatarFile = file;
                    const reader = new FileReader();
                    reader.onload = (e) => this.avatarPreview = e.target.result;
                    reader.readAsDataURL(file);
                },

                previewKtp(event, side) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    if (side === 'front') {
                        this.ktpFrontFile = file;
                        reader.onload = (e) => this.ktpFrontPreview = e.target.result;
                    } else if (side === 'selfie') {
                        this.ktpSelfieFile = file;
                        reader.onload = (e) => this.ktpSelfiePreview = e.target.result;
                    }
                    reader.readAsDataURL(file);
                },

                saveAndExit() {
                    // Simpan progress saat ini lalu kembali ke halaman utama / dashboard
                    window.location.href = '/';
                },

                async saveStep(stepNumber) {
                    const formData = new FormData();
                    formData.append('step', stepNumber);
                    formData.append('_token', '{{ csrf_token() }}');

                    if (stepNumber === 2) {
                        formData.append('locale', this.form.locale);
                        formData.append('village', this.form.village);
                    }

                    if (stepNumber === 3) {
                        formData.append('full_name', this.form.fullName);
                        formData.append('phone_number', this.form.phoneNumber);
                        formData.append('age', this.form.age);
                        formData.append('bio', this.form.bio);
                        formData.append('story', this.form.story);
                        formData.append('expertise', JSON.stringify(this.form.expertise));
                        if (this.avatarFile) formData.append('avatar', this.avatarFile);
                    }

                    if (stepNumber === 4) {
                        if (this.ktpFrontFile) formData.append('ktp_photo', this.ktpFrontFile);
                        if (this.ktpSelfieFile) formData.append('ktp_selfie', this.ktpSelfieFile);
                    }

                    if (stepNumber === 5) {
                        formData.append('bank_name', this.form.bankName);
                        formData.append('bank_account_name', this.form.bankAccountName);
                        formData.append('bank_account_number', this.form.bankAccountNumber);
                        formData.append('confirm_bank', this.form.confirmBank ? '1' : '0');
                        this.bankCheckState = 'checking';
                    }

                    try {
                        const response = await fetch('{{ route("onboarding.host.save") }}', {
                            method: 'POST',
                            headers: { 'Accept': 'application/json' },
                            body: formData,
                        });

                        const data = await response.json();

                        if (data.success) {
                            if (data.redirect) {
                                this.finalBankStatus = data.bank_status ?? '';
                                this.bankCheckState = data.bank_status === 'verified' ? 'verified' : 'needs_review';
                                this.bankCheckMessage = data.bank_message ?? '';
                                this.step = 6;
                            } else {
                                this.step++;
                            }
                        } else {
                            alert('Terjadi kesalahan. Coba lagi.');
                            this.bankCheckState = '';
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan koneksi. Coba lagi.');
                        this.bankCheckState = '';
                    }
                },
            }
        }
    </script>

</body>

</html>