@extends('elearning::layout')

@section('elearning-body')
<section class="el-section bg-cream-light" style="padding-top:3rem;padding-bottom:4rem;">
    <div class="el-container">
        
        <div class="mb-4 text-center">
            <h2 class="el-heading">Secure Checkout</h2>
            <p class="el-subtext">Complete your enrollment to unlock all premium features.</p>
        </div>

        <div class="row g-4 justify-content-center">
            {{-- Payment Form --}}
            <div class="col-lg-6">
                <div class="el-card">
                    <h5 style="font-weight:700;margin-bottom:1.5rem;border-bottom:2px solid var(--cream);padding-bottom:1rem;">Payment Method</h5>
                    
                    <form action="#" method="POST" onsubmit="alert('Payment processed successfully!'); return false;">
                        <div class="mb-3">
                            <label style="font-weight:600;font-size:0.85rem;margin-bottom:0.5rem;">Cardholder Name</label>
                            <input type="text" class="form-control" placeholder="John Doe" style="padding:0.75rem;border:1px solid #ddd;border-radius:6px;" required>
                        </div>
                        
                        <div class="mb-3">
                            <label style="font-weight:600;font-size:0.85rem;margin-bottom:0.5rem;">Card Number</label>
                            <div style="position:relative;">
                                <input type="text" class="form-control" placeholder="0000 0000 0000 0000" style="padding:0.75rem;padding-left:2.5rem;border:1px solid #ddd;border-radius:6px;" required>
                                <i class="bi bi-credit-card" style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--muted);"></i>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-4">
                                <label style="font-weight:600;font-size:0.85rem;margin-bottom:0.5rem;">Expiry Date</label>
                                <input type="text" class="form-control" placeholder="MM/YY" style="padding:0.75rem;border:1px solid #ddd;border-radius:6px;" required>
                            </div>
                            <div class="col-6 mb-4">
                                <label style="font-weight:600;font-size:0.85rem;margin-bottom:0.5rem;">CVV</label>
                                <input type="text" class="form-control" placeholder="123" style="padding:0.75rem;border:1px solid #ddd;border-radius:6px;" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="el-btn-primary" style="width:100%;padding:0.85rem;font-size:1.05rem;">Confirm Payment</button>
                    </form>
                </div>
            </div>
            
            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="el-card" style="background:var(--dark);color:#fff;">
                    <h5 style="font-weight:700;margin-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,0.1);padding-bottom:1rem;color:var(--gold);">Order Summary</h5>
                    
                    <div style="display:flex;justify-content:space-between;margin-bottom:1rem;font-size:0.95rem;">
                        <span>Pro Subscription</span>
                        <span>RM 49.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:1rem;font-size:0.95rem;">
                        <span style="color:rgba(255,255,255,0.6);">Tax (6%)</span>
                        <span style="color:rgba(255,255,255,0.6);">RM 2.94</span>
                    </div>
                    
                    <div style="display:flex;justify-content:space-between;margin-top:1.5rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,0.1);font-weight:700;font-size:1.1rem;">
                        <span>Total</span>
                        <span style="color:var(--gold);">RM 51.94</span>
                    </div>
                    
                    <div style="margin-top:2rem;font-size:0.75rem;color:rgba(255,255,255,0.5);text-align:center;">
                        <i class="bi bi-shield-lock-fill"></i> Secure SSL Encrypted Checkout
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
