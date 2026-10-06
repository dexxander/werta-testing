{{-- FAQ Section --}}
<section class="el-section bg-cream-light">
    <div class="el-container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="el-pill">{{ __('Support') }}</div>
                <h2 class="el-heading">{{ __('Frequently Asked Questions') }}</h2>
                <p class="el-subtext">{{ __('Find answers to common questions about our platform, courses, and subscriptions.') }}</p>
            </div>
            <div class="col-lg-8">
                @if(count($faqs ?? []) > 0)
                    @foreach($faqs as $index => $faq)
                        <div class="el-faq-item">
                            <button class="el-faq-question" type="button">
                                {{ \App\Support\SiteText::translate($faq['question']) }}
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <div class="el-faq-answer">
                                <p>{{ \App\Support\SiteText::translate($faq['answer']) }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="el-empty-state">
                        <i class="bi bi-question-circle"></i>
                        <h5>{{ __('No FAQs Available Yet') }}</h5>
                        <p>{{ __('We are preparing helpful answers. Please contact support for any questions in the meantime.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
