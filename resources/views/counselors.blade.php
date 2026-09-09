@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <style>
        .counselors-page-sec {
            padding: 80px 0 100px;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }

        /* NOTE: These .el- prefixed classes are a local copy of the Elearning package's empty-state component.
           Consolidating them belongs with the deferred design-system merge (Design System Flag) rather than this token pass. */
        .el-empty-state {
            text-align: center;
            padding: 4rem 2rem;
            border: 1.5px dashed rgba(196, 168, 64, 0.35);
            border-radius: var(--radius-xl);
            background: rgba(196, 168, 64, 0.04);
            max-width: 680px;
            margin: 0 auto;
        }

        .el-empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: var(--radius-full);
            background: rgba(196, 168, 64, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 2.25rem;
        }

        .el-empty-state h2 {
            font-family: 'IM Fell English', serif;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
            font-size: var(--text-3xl);
        }

        .el-empty-state p {
            font-size: var(--text-md);
            color: var(--muted);
            max-width: 500px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .el-empty-state-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>

    <section class="counselors-page-sec bg-cream">
        <div class="container">
            <div class="el-empty-state">
                <div class="el-empty-state-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
                <p class="pill-label mb-2">Find a Counselor</p>
                <h2>Directory Coming Soon</h2>
                <p>We are currently onboarding and verifying licensed mental health practitioners across Malaysia. Our certified counselor matching directory will be available soon.</p>
                <div class="el-empty-state-actions">
                    <a href="{{ url('/assessment') }}" class="btn-cta-filled">Take the Assessment</a>
                    <a href="{{ url('/') }}" class="btn-cta-outline">Return to Home</a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
@endsection
