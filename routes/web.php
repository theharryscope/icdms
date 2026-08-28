<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Programs\Index as ProgramsIndex;
use App\Livewire\Programs\Create as ProgramsCreate;
use App\Livewire\Projects\Index as ProjectsIndex;
use App\Livewire\Projects\Create as ProjectsCreate;
use App\Livewire\Communities\Index as CommunitiesIndex;
use App\Livewire\Communities\Create as CommunitiesCreate;
use App\Livewire\Beneficiaries\Index as BeneficiariesIndex;
use App\Livewire\Beneficiaries\Create as BeneficiariesCreate;
use App\Livewire\Me\Dashboard as MeDashboard;
use App\Livewire\Me\CreateFieldVisit as CreateFieldVisit;
use App\Livewire\Admin\RegionalCommand;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Public\LandingPage;
use App\Livewire\Public\RegisterPortal;
use App\Livewire\Dashboard\CoordinatorDashboard;
use App\Http\Middleware\RedirectByRole;
use App\Livewire\Dashboard\VolunteerDashboard;
use App\Livewire\User\ProfileSettings;
use App\Livewire\Dashboard\StudentDashboard;
use App\Livewire\Admin\StudentManagement;
use App\Livewire\Admin\GrantTracker;
use App\Livewire\Frontend\DonatePage;
use App\Livewire\Dashboard\EmployerDashboard;
use App\Livewire\Dashboard\DonorDashboard;
use App\Livewire\Reports\SubmitReport;
use App\Livewire\Admin\ReportInbox;
use App\Livewire\Admin\NotificationComposer;
use App\Livewire\User\Notifications;
use App\Livewire\Admin\SiteSettings;
use App\Livewire\Admin\PageManagement;
use App\Livewire\Public\ShowPage;
use App\Livewire\Admin\BlogManagement;
use App\Livewire\Public\BlogIndex;
use App\Livewire\Public\BlogShow;
/*
|--------------------------------------------------------------------------
| Public Foundation Website Routes
|--------------------------------------------------------------------------
*/
// Public Front Page (the real, fully-built landing page)
Route::get('/', LandingPage::class)->name('landing');

// Public Registration Portal
Route::get('/register-portal', RegisterPortal::class)->name('public.register');

// Custom Admin-Authored Pages
Route::get('/page/{page:slug}', ShowPage::class)->name('public.page');

// Public Blog
Route::get('/blog', BlogIndex::class)->name('public.blog');
Route::get('/blog/{post:slug}', BlogShow::class)->name('public.blog.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Secure ICDMS Management Portal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('icdms')->middleware(['auth'])->group(function () {
    
    // Core Admin Dashboard — role redirect middleware runs first and sends
    // non-Super-Admins to their own dashboard; Super Admins fall through to Overview.
    Route::get('/dashboard', Overview::class)->middleware(RedirectByRole::class)->name('dashboard');

    // Programs Management
    Route::get('/programs', ProgramsIndex::class)->name('programs.index');
    Route::get('/programs/create', ProgramsCreate::class)->name('programs.create');

    // Projects Workspace
    Route::get('/projects', ProjectsIndex::class)->name('projects.index');
    Route::get('/projects/create', ProjectsCreate::class)->name('projects.create');

    // Communities
    Route::get('/communities', CommunitiesIndex::class)->name('communities.index');
    Route::get('/communities/create', CommunitiesCreate::class)->name('communities.create');

    // Beneficiaries
    Route::get('/beneficiaries', BeneficiariesIndex::class)->name('beneficiaries.index');
    Route::get('/beneficiaries/create', BeneficiariesCreate::class)->name('beneficiaries.create');

    // M&E Engine (v9)
    Route::get('/me-dashboard', MeDashboard::class)->name('me.dashboard');
    Route::get('/field-visits/create', CreateFieldVisit::class)->name('me.field-visits.create');
    
    //Regional Command Management
    Route::get('/regional-command', RegionalCommand::class)->name('admin.regional-command');

    // User Management
    Route::get('/users', UserManagement::class)->name('admin.users');

    // Site Settings — branding, contact info, social links (Super Admin only)
    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/settings/site', SiteSettings::class)->name('admin.site-settings');
        Route::get('/pages', PageManagement::class)->name('admin.pages');
        Route::get('/blog', BlogManagement::class)->name('admin.blog');
    });

    // Dedicated Coordinator Dashboard
    Route::get('/coordinator-dashboard', CoordinatorDashboard::class)->name('coordinator.dashboard');
    Route::get('/volunteer-dashboard', VolunteerDashboard::class)->name('volunteer.dashboard');
    Route::get('/employer-dashboard', EmployerDashboard::class)->name('employer.dashboard');
    Route::get('/donor-dashboard', DonorDashboard::class)->name('donor.dashboard');
    Route::get('/reports/create', SubmitReport::class)->name('reports.create');
    Route::get('/notifications', Notifications::class)->name('notifications.index');
    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/reports', ReportInbox::class)->name('admin.reports');
        Route::get('/notifications/create', NotificationComposer::class)->name('admin.notifications.create');
    });
    Route::get('/profile', ProfileSettings::class)->name('user.profile');
    Route::get('/student-dashboard', StudentDashboard::class)->name('student.dashboard');
    Route::get('/students', StudentManagement::class)->name('admin.students');
    Route::get('/grants', GrantTracker::class)->name('admin.grants');
    Route::get('/donate', DonatePage::class)->name('public.donate');
});