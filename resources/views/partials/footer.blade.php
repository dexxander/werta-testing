<footer>
    <div class="footer-top">
        <div class="footer-brand">
            <div style="display:flex; align-items:center; gap:6px;">
                <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta" style="height: 28px; width: auto;">
                <span style="display:flex; align-items:baseline; gap:1px;">
                    <span style="font-family:'Great Vibes',cursive; font-size:2rem; color:var(--gold); line-height:1;">W</span>
                    <span style="font-family:'Lato',sans-serif; font-size:1rem; font-weight:700; letter-spacing:4px; color:var(--primary);">ERTA</span>
                </span>
            </div>
            <p>A professional digital mental health platform supporting users across Malaysia with evidence-based care.</p>
            <span><i class="bi bi-globe2"></i> Available in EN, BM, 中文</span>
        </div>
        <div class="footer-col">
            <h4>Platform</h4>
            <a href="#">Find a Counselor</a>
            <a href="{{ url('/assessment') }}">Take an Assessment</a>
            <a href="#">Community Forum</a>
            <a href="#">Guidance Center</a>
        </div>
        <div class="footer-col">
            <h4>Resources</h4>
            <a href="#">Articles &amp; Guides</a>
            <a href="{#">Guidances Center</a>
            <a href="#">For Parents &amp; Guardians</a>
            <a href="#">For Counselors</a>
        </div>
        <div class="footer-col">
            <h4>Legal</h4>
            <a href="#">Privacy Policy (PDPA)</a>
            <a href="#">Terms of Service</a>
            <a href="#">Counselor Verification</a>
            <a href="#">Contact Us</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Werta. All rights reserved.</p>
        <div>
            <a href="#">Twitter</a>
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">LinkedIn</a>
        </div>
    </div>
</footer>
