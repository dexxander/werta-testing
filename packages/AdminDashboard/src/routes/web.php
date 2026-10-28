<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use AdminDashboard\Http\Controllers\DashboardController;
use AdminDashboard\Http\Controllers\CounselorApprovalController;
use AdminDashboard\Http\Controllers\UserManagementController;
use AdminDashboard\Http\Controllers\AdministratorController;

/*
|--------------------------------------------------------------------------
| Login (shared view, separate routes per role)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', function () {
    if (session('staff_role') === 'admin') {
        return redirect('/admin/dashboard');
    }
    return view('admindashboard::login', ['role' => 'admin']);
});

Route::post('/admin/login', function (Request $request) {
    /* DEV BYPASS: Development-only one-click authentication bypass */
    if (\App\Support\DevAuth::isBypassActive() && $request->has('dev_bypass')) {
        \App\Support\SessionRoles::clearAllRoles();
        session()->regenerate();
        session([
            'staff_role' => 'admin',
            'dev_bypass_session' => true,
        ]);
        return redirect('/admin/dashboard');
    }

    if ($request->input('username') === 'admin' && $request->input('password') === 'admin') {
        \App\Support\SessionRoles::clearAllRoles();
        session()->regenerate();
        session(['staff_role' => 'admin']);
        return redirect('/admin/dashboard');
    }
    return redirect('/admin/login')->with('error', 'Invalid username or password.');
});

Route::get('/superadmin/login', function () {
    if (session('staff_role') === 'superadmin') {
        return redirect('/superadmin/dashboard');
    }
    return view('admindashboard::login', ['role' => 'superadmin']);
});

Route::post('/superadmin/login', function (Request $request) {
    /* DEV BYPASS: Development-only one-click authentication bypass */
    if (\App\Support\DevAuth::isBypassActive() && $request->has('dev_bypass')) {
        \App\Support\SessionRoles::clearAllRoles();
        session()->regenerate();
        session([
            'staff_role' => 'superadmin',
            'dev_bypass_session' => true,
        ]);
        return redirect('/superadmin/dashboard');
    }

    if ($request->input('username') === 'superadmin' && $request->input('password') === 'superadmin') {
        \App\Support\SessionRoles::clearAllRoles();
        session()->regenerate();
        session(['staff_role' => 'superadmin']);
        return redirect('/superadmin/dashboard');
    }
    return redirect('/superadmin/login')->with('error', 'Invalid username or password.');
});

Route::get('/admin/logout', function () {
    \App\Support\SessionRoles::clearAllRoles();
    session()->regenerate();
    return redirect('/');
});

Route::get('/superadmin/logout', function () {
    \App\Support\SessionRoles::clearAllRoles();
    session()->regenerate();
    return redirect('/');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::middleware('web')->prefix('admin')->group(function () {
    $requireAdmin = function () {
        if (session('staff_role') !== 'admin') {
            return redirect('/admin/login');
        }
        return null;
    };

    Route::get('/dashboard', function () use ($requireAdmin) {
        return $requireAdmin() ?? app(DashboardController::class)->index('admin');
    })->name('admin.dashboard');

    Route::get('/counselor-approvals', function () use ($requireAdmin) {
        return $requireAdmin() ?? app(CounselorApprovalController::class)->index('admin');
    })->name('admin.counselor-approvals');
    Route::post('/counselor-approvals/{counselor}/approve', function ($counselor) use ($requireAdmin) {
        return $requireAdmin() ?? app(CounselorApprovalController::class)->approve(request(), \AdminDashboard\Models\Counselor::findOrFail($counselor));
    });
    Route::post('/counselor-approvals/{counselor}/reject', function ($counselor) use ($requireAdmin) {
        return $requireAdmin() ?? app(CounselorApprovalController::class)->reject(request(), \AdminDashboard\Models\Counselor::findOrFail($counselor));
    });

    Route::get('/user-management', function () use ($requireAdmin) {
        return $requireAdmin() ?? app(UserManagementController::class)->index('admin');
    })->name('admin.user-management');
    Route::delete('/user-management/{client}', function ($client) use ($requireAdmin) {
        return $requireAdmin() ?? app(UserManagementController::class)->destroy(\AdminDashboard\Models\Client::findOrFail($client));
    });

    Route::get('/analytics', function () use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\AnalyticsController::class)->index('admin');
    })->name('admin.analytics');
    Route::get('/revenue', function () use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\RevenueController::class)->index('admin');
    })->name('admin.revenue');

    // Editorial review workflow and reviewer verification
    Route::get('/reviews', function () use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\ArticleReviewController::class)->index('admin', request());
    })->name('admin.reviews');
    Route::post('/reviews/{submission}/assign', function ($submission) use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\ArticleReviewController::class)->assign('admin', request(), \Submissions\Models\Submission::findOrFail($submission));
    })->name('admin.reviews.assign');
    Route::post('/reviews/{submission}/decision', function ($submission) use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\ArticleReviewController::class)->decide('admin', request(), \Submissions\Models\Submission::findOrFail($submission));
    })->name('admin.reviews.decision');
    Route::post('/reviews/{submission}/publish', function ($submission) use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\ArticleReviewController::class)->publish('admin', \Submissions\Models\Submission::findOrFail($submission));
    })->name('admin.reviews.publish');
    Route::get('/reviewers', function () use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\ReviewerManagementController::class)->index('admin', request());
    })->name('admin.reviewers');
    Route::post('/reviewers/{reviewer}/approve', function ($reviewer) use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\ReviewerManagementController::class)->approve(\AdminDashboard\Models\Reviewer::findOrFail($reviewer));
    })->name('admin.reviewers.approve');
    Route::post('/reviewers/{reviewer}/reject', function ($reviewer) use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\ReviewerManagementController::class)->reject(\AdminDashboard\Models\Reviewer::findOrFail($reviewer));
    })->name('admin.reviewers.reject');
    Route::post('/reviewers/{reviewer}/suspend', function ($reviewer) use ($requireAdmin) {
        return $requireAdmin() ?? app(\AdminDashboard\Http\Controllers\ReviewerManagementController::class)->suspend(\AdminDashboard\Models\Reviewer::findOrFail($reviewer));
    })->name('admin.reviewers.suspend');
    Route::get('/content', function () use ($requireAdmin) {
        return $requireAdmin() ?? view('admindashboard::content', ['role' => 'admin']);
    })->name('admin.content');
    Route::get('/settings', function () use ($requireAdmin) {
        return $requireAdmin() ?? view('admindashboard::settings', ['role' => 'admin']);
    })->name('admin.settings');
});

/*
|--------------------------------------------------------------------------
| Superadmin routes
|--------------------------------------------------------------------------
*/

Route::middleware('web')->prefix('superadmin')->group(function () {
    $requireSuperadmin = function () {
        if (session('staff_role') !== 'superadmin') {
            return redirect('/superadmin/login');
        }
        return null;
    };

    Route::get('/dashboard', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(DashboardController::class)->index('superadmin');
    })->name('superadmin.dashboard');

    Route::get('/administrators', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(AdministratorController::class)->index('superadmin');
    })->name('superadmin.administrators');
    Route::post('/administrators', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(AdministratorController::class)->store(request());
    });
    Route::delete('/administrators/{administrator}', function ($administrator) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(AdministratorController::class)->destroy(\AdminDashboard\Models\Administrator::findOrFail($administrator));
    });

    Route::get('/counselor-approvals', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(CounselorApprovalController::class)->index('superadmin');
    })->name('superadmin.counselor-approvals');
    Route::post('/counselor-approvals/{counselor}/approve', function ($counselor) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(CounselorApprovalController::class)->approve(request(), \AdminDashboard\Models\Counselor::findOrFail($counselor));
    });
    Route::post('/counselor-approvals/{counselor}/reject', function ($counselor) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(CounselorApprovalController::class)->reject(request(), \AdminDashboard\Models\Counselor::findOrFail($counselor));
    });

    Route::get('/user-management', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(UserManagementController::class)->index('superadmin');
    })->name('superadmin.user-management');
    Route::delete('/user-management/{client}', function ($client) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(UserManagementController::class)->destroy(\AdminDashboard\Models\Client::findOrFail($client));
    });

    Route::get('/analytics', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\AnalyticsController::class)->index('superadmin');
    })->name('superadmin.analytics');
    Route::get('/revenue', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\RevenueController::class)->index('superadmin');
    })->name('superadmin.revenue');

    // Editorial review workflow and reviewer verification
    Route::get('/reviews', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\ArticleReviewController::class)->index('superadmin', request());
    })->name('superadmin.reviews');
    Route::post('/reviews/{submission}/assign', function ($submission) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\ArticleReviewController::class)->assign('superadmin', request(), \Submissions\Models\Submission::findOrFail($submission));
    })->name('superadmin.reviews.assign');
    Route::post('/reviews/{submission}/decision', function ($submission) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\ArticleReviewController::class)->decide('superadmin', request(), \Submissions\Models\Submission::findOrFail($submission));
    })->name('superadmin.reviews.decision');
    Route::post('/reviews/{submission}/publish', function ($submission) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\ArticleReviewController::class)->publish('superadmin', \Submissions\Models\Submission::findOrFail($submission));
    })->name('superadmin.reviews.publish');
    Route::get('/reviewers', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\ReviewerManagementController::class)->index('superadmin', request());
    })->name('superadmin.reviewers');
    Route::post('/reviewers/{reviewer}/approve', function ($reviewer) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\ReviewerManagementController::class)->approve(\AdminDashboard\Models\Reviewer::findOrFail($reviewer));
    })->name('superadmin.reviewers.approve');
    Route::post('/reviewers/{reviewer}/reject', function ($reviewer) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\ReviewerManagementController::class)->reject(\AdminDashboard\Models\Reviewer::findOrFail($reviewer));
    })->name('superadmin.reviewers.reject');
    Route::post('/reviewers/{reviewer}/suspend', function ($reviewer) use ($requireSuperadmin) {
        return $requireSuperadmin() ?? app(\AdminDashboard\Http\Controllers\ReviewerManagementController::class)->suspend(\AdminDashboard\Models\Reviewer::findOrFail($reviewer));
    })->name('superadmin.reviewers.suspend');
    Route::get('/content', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? view('admindashboard::content', ['role' => 'superadmin']);
    })->name('superadmin.content');
    Route::get('/settings', function () use ($requireSuperadmin) {
        return $requireSuperadmin() ?? view('admindashboard::settings', ['role' => 'superadmin']);
    })->name('superadmin.settings');
});
