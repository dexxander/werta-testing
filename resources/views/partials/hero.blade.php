<section class="hero">
    <video class="hero-video" autoplay muted loop playsinline>
        <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <h1 class="hero-heading">{{ __('Mental Wellbeing,') }}<br>{{ __('With You Every Step') }}</h1>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ url('/counselors') }}" class="btn-cta-filled">{{ __('Find a Counselor') }}</a>
                    <a href="{{ url('/assessment') }}" class="btn-cta-outline">{{ __('Take an Assessment') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
