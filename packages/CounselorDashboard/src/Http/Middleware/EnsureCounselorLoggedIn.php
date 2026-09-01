<?php
namespace CounselorDashboard\Http\Middleware;

use Closure;

class EnsureCounselorLoggedIn
{
    public function handle($request, Closure $next)
    {
        if (!session('counselor_logged_in')) {
            return redirect()->route('counselor.login');
        }
        return $next($request);
    }
}