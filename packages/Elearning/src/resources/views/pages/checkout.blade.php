@extends('elearning::layout')

@section('elearning-body')
@php
    $selected_plan = $selected_plan ?? ['name' => 'Pro', 'price' => 'RM 49', 'period' => 'per month'];
    $plan_amount = (float) preg_replace('/[^0-9.]/', '', $selected_plan['price'] ?? '0');
    $plan_tax = round($plan_amount * 0.06, 2);
    $plan_total = $plan_amount + $plan_tax;
@endphp
<section class="el-section bg-cream-light" style="padding-top:3rem;padding-bottom:4rem;">
    <div class="el-container">
        
        <div class="mb-4 text-center">
            <h2 class="el-heading">{{ __('Choose Your Plan') }}</h2>
            <p class="el-subtext">{{ __('Confirm') }} {{ \App\Support\SiteText::translate($selected_plan['name']) }} {{ __('for this browser-based learning demo.') }}</p>
        </div>

        <div class="row g-4 justify-content-center">
            {{-- Payment Form --}}
            <div class="col-lg-6">
                <div class="el-card">
                    <h5 style="font-weight:700;margin-bottom:1rem;border-bottom:2px solid var(--cream);padding-bottom:1rem;">{{ __('Checkout details') }}</h5>
                    <p style="color:var(--muted);font-size:var(--text-sm);margin-bottom:1.5rem;">{{ __('This demo saves your plan choice in this browser. It does not collect payment details or charge you.') }}</p>
                    
                    <form id="el-demo-checkout">
                        <button type="submit" class="el-btn-primary" style="width:100%;padding:0.85rem;font-size:var(--text-md);">{{ __('Save plan selection') }}</button>
                        <p id="el-checkout-status" role="status" aria-live="polite" style="margin:1rem 0 0;color:var(--muted);font-size:var(--text-sm);"></p>
                        <div id="el-checkout-success" hidden style="margin-top:1rem;">
                            <a href="{{ route('elearning.courses') }}" class="el-btn-outline">{{ __('Browse courses') }}</a>
                            <a href="{{ route('elearning.my-courses') }}" class="el-btn-primary">{{ __('Open My Courses') }}</a>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="el-card" style="background:var(--dark);color:#fff;">
                    <h5 style="font-weight:700;margin-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,0.1);padding-bottom:1rem;color:var(--gold);">{{ __('Order Summary') }}</h5>
                    
                    <div style="display:flex;justify-content:space-between;margin-bottom:1rem;font-size:var(--text-base-lg);">
                        <span>{{ \App\Support\SiteText::translate($selected_plan['name']) }} {{ __('Subscription') }} <small style="display:block;color:rgba(255,255,255,.55);">{{ \App\Support\SiteText::translate($selected_plan['period']) }}</small></span>
                        <span>{{ \App\Support\SiteText::translate($selected_plan['price']) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:1rem;font-size:var(--text-base-lg);">
                        <span style="color:rgba(255,255,255,0.6);">{{ __('Tax (6%)') }}</span>
                        <span style="color:rgba(255,255,255,0.6);">RM {{ number_format($plan_tax, 2) }}</span>
                    </div>
                    
                    <div style="display:flex;justify-content:space-between;margin-top:1.5rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,0.1);font-weight:700;font-size:1.1rem;">
                        <span>{{ __('Total') }}</span>
                        <span style="color:var(--gold);">RM {{ number_format($plan_total, 2) }}</span>
                    </div>
                    
                    <div style="margin-top:2rem;font-size:var(--text-sm);color:rgba(255,255,255,0.5);text-align:center;">
                        <i class="bi bi-info-circle-fill"></i> {{ __('No payment is collected in this demo') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function initElearningCheckout() {
        const form = document.getElementById('el-demo-checkout');
        if (!form) return;
        const selectedPlan = @json($selected_plan['name']);
        try {
            const savedPlan = JSON.parse(localStorage.getItem('werta-elearning-selected-plan') || 'null');
            if (savedPlan && savedPlan.name === selectedPlan) {
                document.getElementById('el-checkout-status').textContent = selectedPlan + ' is saved as your selected plan in this browser. No payment was taken.';
                document.getElementById('el-checkout-success').hidden = false;
            }
        } catch (error) {
            localStorage.removeItem('werta-elearning-selected-plan');
        }
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const plan = @json(['name' => $selected_plan['name'], 'price' => $selected_plan['price'], 'period' => $selected_plan['period']]);
            localStorage.setItem('werta-elearning-selected-plan', JSON.stringify({ ...plan, selectedAt: new Date().toISOString() }));
            document.getElementById('el-checkout-status').textContent = plan.name + ' is saved as your selected plan in this browser. No payment was taken.';
            document.getElementById('el-checkout-success').hidden = false;
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initElearningCheckout);
    else initElearningCheckout();
</script>
@endsection
