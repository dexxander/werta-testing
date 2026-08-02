<?php

namespace ClientDashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ClientDashboardServiceProvider extends ServiceProvider
{
    public function boot(\Illuminate\Contracts\Http\Kernel $kernel)
    {
        // Load Routes
        Route::middleware('web')
            ->group(__DIR__.'/../routes/web.php');

        // Load Views (accessible via 'clientdashboard::viewname')
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'clientdashboard');

        // Push middleware to the 'web' group so it runs AFTER session start
        $this->app['router']->pushMiddlewareToGroup('web', AuthNavbarInjector::class);
    }

    public function register()
    {
        //
    }
}

class AuthNavbarInjector
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        // Only process if it's an HTML response
        if (
            $response instanceof \Illuminate\Http\Response &&
            strpos($response->headers->get('Content-Type'), 'text/html') !== false
        ) {
            $isClient = session('client_logged_in', false);
            $isParent = session('parent_logged_in', false);

            if ($isClient || $isParent) {
                $role = $isClient ? 'Client' : 'Parent';
                $dashboardUrl = $isClient ? '/client/dashboard' : '/parent/dashboard';
                $logoutUrl = $isClient ? '/client/logout' : '/parent/logout';
                $icon = $isClient ? 'person' : 'person-heart';
                
                // Get updated profile info if edited, otherwise defaults
                $profile = session(strtolower($role) . '_profile', [
                    'username' => $role . ' User',
                    'picture' => 'bi-person-circle'
                ]);
                $username = $profile['username'];
                $userIcon = $profile['picture'];

                $script = "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const accountBtn = document.querySelector('.btn-account');
                        if (accountBtn) {
                            const dropdownInner = document.querySelector('.account-dropdown-menu-inner');
                            if(dropdownInner) {
                                // Replace button content
                                accountBtn.innerHTML = '<i class=\"bi " . $userIcon . "\"></i> " . $username . " (' . $role . ') <i class=\"bi bi-chevron-down\" style=\"font-size:0.7rem;\"></i>';
                                
                                // Replace dropdown menu
                                dropdownInner.innerHTML = `
                                    <a href=\"" . $dashboardUrl . "\"><i class=\"bi bi-speedometer2\"></i> Dashboard</a>
                                    <hr class=\"divider\">
                                    <a href=\"" . $logoutUrl . "\"><i class=\"bi bi-box-arrow-right\"></i> Logout</a>
                                `;
                            }
                        }

                        // Also redirect all register links to our custom register route
                        document.querySelectorAll('a[href=\"#\"]').forEach(function(link) {
                            if (link.innerHTML.includes('Register')) {
                                link.href = '/auth/register';
                            }
                        });
                    });
                </script>";

                $content = $response->getContent();
                $content = str_replace('</body>', $script . '</body>', $content);
                $response->setContent($content);
            } else {
                // If not logged in, just rewrite the register link
                $script = "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('a[href=\"#\"]').forEach(function(link) {
                            if (link.innerHTML.includes('Register')) {
                                link.href = '/auth/register';
                            }
                        });
                    });
                </script>";
                $content = $response->getContent();
                $content = str_replace('</body>', $script . '</body>', $content);
                $response->setContent($content);
            }
        }

        return $response;
    }
}
