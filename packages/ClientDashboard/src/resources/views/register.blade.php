@extends('clientdashboard::layout')

@section('content')
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background: #fdfbf7;">
    <div style="background: #fff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 500px;">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="font-family: 'IM Fell English', serif; font-weight: 700; color: #2c2416; margin-bottom: 0.5rem;">Join Werta</h2>
            <p style="color: #6c757d; font-size: 0.95rem;">Create an account to get started.</p>
        </div>

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                {{ session('error') }}
            </div>
        @endif

        <form action="/auth/register" method="POST" id="registerForm">
            @csrf
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; color: #2c2416;">I am registering as a:</label>
                <div style="display: flex; gap: 1rem;">
                    <label style="flex: 1; border: 1px solid #ddd; border-radius: 6px; padding: 0.75rem; text-align: center; cursor: pointer; transition: all 0.2s;" id="label-parent">
                        <input type="radio" name="role" value="parent" style="display: none;" onchange="toggleRole('parent')">
                        <i class="bi bi-person-heart" style="font-size: 1.5rem; display: block; margin-bottom: 0.3rem;"></i>
                        Parent
                    </label>
                    <label style="flex: 1; border: 1px solid #c4a840; background: rgba(196,168,64,0.1); border-radius: 6px; padding: 0.75rem; text-align: center; cursor: pointer; transition: all 0.2s;" id="label-client">
                        <input type="radio" name="role" value="client" checked style="display: none;" onchange="toggleRole('client')">
                        <i class="bi bi-person" style="font-size: 1.5rem; display: block; margin-bottom: 0.3rem; color: #c4a840;"></i>
                        <span style="color: #c4a840; font-weight: 600;">Client</span>
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; color: #2c2416;">Full Name</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; color: #2c2416;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
            </div>

            <div style="margin-bottom: 1.5rem;" id="ic-container">
                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; color: #2c2416;">Malaysian IC Number <span style="color: #c4a840;">*</span></label>
                <input type="text" name="ic_number" id="ic_number" placeholder="YYMMDD-XX-XXXX" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                <p style="font-size: 0.75rem; color: #6c757d; margin-top: 0.4rem;">Used to verify age. Clients must be at least 17 years old.</p>
                <div id="ic-error" style="color: #dc3545; font-size: 0.8rem; margin-top: 0.4rem; display: none;"></div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; color: #2c2416;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
            </div>

            <button type="submit" style="width: 100%; padding: 0.85rem; background: #c4a840; color: #fff; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#b09638'" onmouseout="this.style.background='#c4a840'">
                Create Account
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
            Already have an account? 
            <a href="{{ url('/client/login') }}" style="color: #c4a840; text-decoration: none; font-weight: 600;">Sign In</a>
        </div>
    </div>
</div>

<script>
    function toggleRole(role) {
        const parentLabel = document.getElementById('label-parent');
        const clientLabel = document.getElementById('label-client');
        const icContainer = document.getElementById('ic-container');
        const icInput = document.getElementById('ic_number');
        
        if (role === 'client') {
            clientLabel.style.border = '1px solid #c4a840';
            clientLabel.style.background = 'rgba(196,168,64,0.1)';
            clientLabel.querySelector('i').style.color = '#c4a840';
            clientLabel.querySelector('span').style.color = '#c4a840';
            clientLabel.querySelector('span').style.fontWeight = '600';
            
            parentLabel.style.border = '1px solid #ddd';
            parentLabel.style.background = 'transparent';
            parentLabel.style.color = 'inherit';
            
            icContainer.style.display = 'block';
            icInput.setAttribute('required', 'required');
        } else {
            parentLabel.style.border = '1px solid #c4a840';
            parentLabel.style.background = 'rgba(196,168,64,0.1)';
            parentLabel.style.color = '#c4a840';
            parentLabel.style.fontWeight = '600';
            
            clientLabel.style.border = '1px solid #ddd';
            clientLabel.style.background = 'transparent';
            clientLabel.querySelector('i').style.color = 'inherit';
            clientLabel.querySelector('span').style.color = 'inherit';
            clientLabel.querySelector('span').style.fontWeight = '400';
            
            icContainer.style.display = 'none';
            icInput.removeAttribute('required');
            document.getElementById('ic-error').style.display = 'none';
        }
    }

    // Client-side validation for age
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const role = document.querySelector('input[name="role"]:checked').value;
        if (role === 'client') {
            const ic = document.getElementById('ic_number').value.replace(/-/g, '');
            if (ic.length >= 2) {
                const yearStr = ic.substring(0, 2);
                let year = parseInt(yearStr);
                year = year > 50 ? 1900 + year : 2000 + year;
                const currentYear = new Date().getFullYear();
                
                if (currentYear - year < 17) {
                    e.preventDefault();
                    document.getElementById('ic-error').innerText = 'You must be at least 17 years old to register as a client.';
                    document.getElementById('ic-error').style.display = 'block';
                }
            } else {
                e.preventDefault();
                document.getElementById('ic-error').innerText = 'Please enter a valid IC number.';
                document.getElementById('ic-error').style.display = 'block';
            }
        }
    });
</script>
@endsection
