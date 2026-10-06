{{-- Pricing Plans Section --}}
<section class="el-section bg-cream">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">{{ __('Plans') }}</div>
            <h2 class="el-heading">{{ __('Choose Your Plan') }}</h2>
            <p class="el-subtext mx-auto">{{ __('Flexible pricing options designed to fit every learner\'s needs and budget.') }}</p>
        </div>

        @if(count($pricing_plans ?? []) > 0)
            <div class="row g-4 justify-content-center">
                @foreach($pricing_plans as $plan)
                    <div class="col-md-6 col-lg-4">
                        <div class="el-pricing-card {{ !empty($plan['recommended']) ? 'recommended' : '' }}">
                            @if(!empty($plan['recommended']))
                                <span class="el-pricing-badge">{{ __('Recommended') }}</span>
                            @endif
                            <h5 style="font-weight:700;font-size:1.1rem;color:var(--dark);margin-bottom:0;">{{ \App\Support\SiteText::translate($plan['name']) }}</h5>
                            <div class="el-pricing-price">{{ \App\Support\SiteText::translate($plan['price']) }}</div>
                            <div class="el-pricing-period">{{ \App\Support\SiteText::translate($plan['period']) }}</div>
                            <ul class="el-pricing-features">
                                @foreach($plan['features'] as $feature)
                                    <li><i class="bi bi-check-circle-fill"></i> {{ \App\Support\SiteText::translate($feature) }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ route('elearning.checkout') }}" class="{{ !empty($plan['recommended']) ? 'el-btn-primary' : 'el-btn-outline' }}" style="width:100%;text-align:center;">
                                {{ __($plan['name'] === 'Free' ? 'Get Started' : 'Subscribe Now') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="el-empty-state">
                <i class="bi bi-credit-card"></i>
                <h5>{{ __('Pricing Coming Soon') }}</h5>
                <p>{{ __('We are finalising our pricing plans. Check back soon for flexible options.') }}</p>
            </div>
        @endif
    </div>
</section>
