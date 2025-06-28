<?php

use App\Models\Warehouse;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\UserController as Users;
use App\Http\Controllers\AlertController as Alert;
use App\Http\Controllers\TypeAlertController as TypeAlerts;
use App\Http\Controllers\HistoriqueAlertController as HistoriqueAlert;
use App\Http\Controllers\Auth\LoginController as Login;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\RecapitulatifController;
use App\Http\Controllers\RecapsController;
use App\Http\Controllers\TypeReportsController;
use App\Http\Controllers\HistoriqueReportController;
use App\Mail\email;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrgDashboardController;
use App\Http\Controllers\TestMailController;




Route::get('/test/send-email/{id}/{type}', [App\Http\Controllers\EmailTemplateController::class, 'sendEmail']);
// Main Page Route
//Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');

Route::get('/', [Login::class, 'index'])->name('auth-login');
Route::get('/test_email', function(){$quantity="10000";$warehouse_name = "Tesla"; Mail::to('thabtiissam7@gmail.com')->send(new \App\Mail\myTestEmail($warehouse_name,$quantity));});
Route::get('/test_email200', function(){$quantity="10000";$warehouse_name = "Tesla"; Mail::to('thabtiissam7@gmail.com')->send(new \App\Mail\Alert_Stock200($warehouse_name,$quantity));});
Route::get('/test_email3', function(){Mail::to('')->send(new \App\Mail\Alert_Stock3());});
Route::get('/test_emailCustomer', function(){$customer_name="taha zouari";$hour="22:30";Mail::to('thabtiissam7@gmail.com')->send(new \App\Mail\Alert_customer($customer_name,$hour));});
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::get('/admin/alerts/history/regenerate/{id}', [HistoriqueAlert::class, 'regenerate'])->name('historiqueAlerts.regenerate');
Route::delete('/admin/alerts/history/delete/{id}', [HistoriqueAlert::class, 'destroy'])->name('historiqueAlerts.destroy');;



Route::get('/admin/recent-orders', [AdminController::class, 'fetchRecentOrders'])->name('admin.recentOrders');
Route::middleware(['auth', 'isOrgAdmin'])->group(function () {
    Route::get('/org-dashboard', [OrgDashboardController::class, 'index'])->name('org.dashboard');
    Route::get('/org-dashboard/recent-activities', [OrgDashboardController::class, 'fetchRecentActivities'])->name('org.dashboard.recentActivities');
});

Route::post('/login', [Login::class, 'login'])->name('post-login');
Route::get('/logout', [Login::class, 'logout'])->name('auth-logout');





Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-admin');
    Route::get('/users', [Users::class, 'index'])->name('users');
    Route::get('/reports/history', [App\Http\Controllers\HistoriqueReportController::class, 'index'])->name('history-reports');
    Route::group(['prefix' => 'alerts'], function () {
        Route::get('/history', [HistoriqueAlert::class, 'index'])->name('history-alerts');
        Route::get('/history/{id}', [HistoriqueAlert::class, 'regenerate'])->name('history-details-alerts');

        Route::get('/', [Alert::class, 'index'])->name('alerts-list');
        Route::get('/create', [Alert::class, 'create'])->name('alerts-list-create');
        Route::post('/store', [Alert::class, 'store'])->name('alerts-list-store');
        Route::get('/update/{id}', [Alert::class, 'update'])->name('alerts-list-update');
        Route::post('/update/{id}', [Alert::class, 'edit'])->name('alerts-list-edit');
        Route::get('/delete/{id}', [Alert::class, 'destroy'])->name('alerts-list-delete');
        Route::get('/show/{id}', [Alert::class, 'show'])->name('alerts-list-show');
        Route::group(['prefix' => 'types'], function () {
            Route::get('/', [TypeAlerts::class, 'index'])->name('alerts-types');
            Route::get('/create', [TypeAlerts::class, 'create'])->name('alerts-types-create');
            Route::post('/store', [TypeAlerts::class, 'store'])->name('alerts-types-store');
            Route::get('/update/{id}', [TypeAlerts::class, 'edit'])->name('alerts-types-update');
            Route::post('/update/{id}', [TypeAlerts::class, 'update'])->name('alerts-types-edit');
            Route::get('/delete/{id}', [TypeAlerts::class, 'destroy'])->name('alerts-types-delete');
            Route::get('/show/{id}', [TypeAlerts::class, 'show'])->name('alerts-types-show');
        });



      Route::group(['prefix' => 'reports'], function () {
        Route::get('/history', [HistoriqueReportController::class, 'index'])->name('history-reports');
        Route::get('/history/{id}', [HistoriqueReportController::class, 'regenerate'])->name('history-details-reports');
        Route::delete('/history/delete/{id}', [HistoriqueReportController::class, 'destroy'])->name('history-reports-destroy');

        Route::get('/', [RecapsController::class, 'index'])->name('reports-list');
        Route::get('/create', [RecapsController::class, 'create'])->name('reports-list-create');
        Route::post('/store', [RecapsController::class, 'store'])->name('reports-list-store');
        Route::get('/update/{id}', [RecapsController::class, 'update'])->name('reports-list-update');
        Route::post('/update/{id}', [RecapsController::class, 'edit'])->name('reports-list-edit');
        Route::get('/delete/{id}', [RecapsController::class, 'destroy'])->name('reports-list-delete');
        Route::get('/show/{id}', [RecapsController::class, 'show'])->name('reports-list-show');
        Route::group(['prefix' => 'types'], function () {
          Route::get('/', [TypeReportsController::class, 'index'])->name('reports-types');
          Route::get('/create', [TypeReportsController::class, 'create'])->name('reports-types-create');
          Route::post('/store', [TypeReportsController::class, 'store'])->name('reports-types-store');
          Route::get('/update/{id}', [TypeReportsController::class, 'edit'])->name('reports-types-update');
          Route::post('/update/{id}', [TypeReportsController::class, 'update'])->name('reports-types-edit');
          Route::get('/delete/{id}', [TypeReportsController::class, 'destroy'])->name('reports-types-delete');
          Route::get('/show/{id}', [TypeReportsController::class, 'show'])->name('reports-types-show');
        });
      });
    });

});




Route::group(['prefix' => 'organisation'], function () {
    Route::get('/dashboard', [Analytics::class, 'indexOrganisation'])->name('dashboard-organisation');
    
    // AJAX routes for dashboard data management
    Route::post('/dashboard/refresh', [Analytics::class, 'refreshData'])->name('dashboard.refresh');
    Route::get('/dashboard/cached', [Analytics::class, 'getCachedData'])->name('dashboard.cached');
    
    Route::group(['prefix' => 'alerts'], function () {
        Route::get('/history', [HistoriqueAlert::class, 'index'])->name('organisation-history-alerts');
        Route::get('/history/{id}', [HistoriqueAlert::class, 'regenerate'])->name('organisation-history-details-alerts');

        Route::get('/', [Alert::class, 'indexOrganisation'])->name('organisation-alerts');;
        Route::get('/create', [Alert::class, 'create'])->name('organisation-alerts-create');
        Route::post('/store', [Alert::class, 'store'])->name('organisation-alerts-store');
        Route::get('/update/{id}', [Alert::class, 'update'])->name('organisation-alerts-update');
        Route::post('/update/{id}', [Alert::class, 'edit'])->name('organisation-alerts-edit');
        Route::get('/delete/{id}', [Alert::class, 'destroy'])->name('organisation-alerts-destroy');
        Route::get('/show/{id}', [Alert::class, 'show'])->name('organisation-alerts-show');


    });

   Route::group(['prefix' => 'email'], function () {
    Route::get('/liste', [EmailTemplateController::class, 'index'])->name('email.liste');
    Route::get('/create', [EmailTemplateController::class, 'create'])->name('email.create');
    Route::post('/store', [EmailTemplateController::class, 'store'])->name('organisation.email.templates.store');
    Route::delete('/destroy/{id}', [EmailTemplateController::class, 'destroy'])->name('email.destroy');

    Route::get('organisation/email/edit/{id}', [EmailTemplateController::class, 'edit'])->name('email.edit');    Route::put('/update/{id}', [EmailTemplateController::class, 'update'])->name('email.update');
    Route::get('/show/{id}', [EmailTemplateController::class, 'show'])->name('email.show');

});

  Route::group(['prefix' => 'reports'], function () {
    Route::get('/', [RecapsController::class, 'indexOrganisation'])->name('organisation-reports');
    Route::get('createReport', [RecapsController::class, 'create'])->name('organisation-reports-create');
    Route::post('/store', [RecapsController::class, 'store'])->name('organisation-reports-store');
    Route::get('/update/{id}', [RecapsController::class, 'updateForm'])->name('organisation-reports-update');
    Route::post('/update/{id}', [RecapsController::class, 'edit'])->name('organisation-reports-edit');
    Route::get('/delete/{id}', [RecapsController::class, 'destroy'])->name('organisation-reports-destroy');
    Route::get('/show/{id}', [RecapsController::class, 'show'])->name('organisation-reports-show');
    Route::get('/generate', [RecapsController::class, 'generateForm'])->name('reports.generate.form');
    Route::post('/generate', [RecapsController::class, 'generateReport'])->name('reports.generate');
    Route::get('/history', [HistoriqueReportController::class, 'index'])->name('organisation-history-reports');
    Route::get('/history/{id}', [HistoriqueReportController::class, 'regenerate'])->name('organisation-history-details-reports');
    Route::delete('/history/delete/{id}', [HistoriqueReportController::class, 'destroy'])->name('organisation-history-reports-destroy');
  });

});






// routes/web.php

Route::post('/generate-recapitulatifs', [RecapitulatifController::class, 'generateRecapitulatifs']);


Route::middleware(['auth:api'])->group(function () {
    Route::get('generate-recapitulatif/{operationId}', [RecapitulatifController::class, 'generateRecapitulatif']);
});
Route::middleware(['web'])->group(function () { // This should have a matching closing bracket
    // Your route definitions
});




// layout routes
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages routes

// Routes protégées par le middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-notifications');
    Route::post('/account/update', [AccountSettingsAccount::class, 'update'])->name('account.update');
});





// Route sans authentification
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('account.settings');
Route::post('/account/update', [AccountSettingsAccount::class, 'update'])->name('account.update');



Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface routes
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended UI routes
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons routes
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements routes
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts routes
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables routes
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');

Route::get('/dashboard/download-report', [\App\Http\Controllers\dashboard\Analytics::class, 'downloadReport'])->name('dashboard.downloadReport');
