<?php

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
use App\Http\Middleware\ApiMiddleware;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RecapitulatifController;
use App\Http\Controllers\EmailTemplateController;



use App\Http\Controllers\UserController as Users;
use App\Http\Controllers\AlertController as Alert;
use App\Http\Controllers\TypeAlertController as TypeAlerts;
use App\Http\Controllers\HistoriqueAlertController as HistoriqueAlert;

use App\Mail\myTestEmail;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrgDashboardController;



Route::middleware(['auth'])->group(function () {
    Route::resource('email-templates', EmailTemplateController::class);
});


Route::middleware(['web'])->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/email/liste', [EmailTemplateController::class, 'index'])->name('email.liste');
        Route::get('/email/create', [EmailTemplateController::class, 'create'])->name('email.create');
        Route::get('/dashboard', [AuthenticationController::class, 'showDashboard'])->name('dashboard');
        Route::post('/email', [EmailTemplateController::class, 'store'])->name('email.store');
    });
});
Route::post('/email', [EmailTemplateController::class, 'store'])->name('email.store');
Route::middleware(['auth'])->group(function () {
    Route::get('/email/create', [EmailTemplateController::class, 'create'])->name('email.create');
});


Route::get('/', [AuthenticationController::class, 'index'])->name('auth-login');
Route::get('/test_email', function(){$quantity="10000";$warehouse_name = "Tesla"; Mail::to('tahayassine28470618@gmail.com')->send(new \App\Mail\myTestEmail($warehouse_name,$quantity));});
Route::get('/test_email200', function(){$quantity="10000";$warehouse_name = "Tesla"; Mail::to('tahayassine28470618@gmail.com')->send(new \App\Mail\Alert_Stock200($warehouse_name,$quantity));});
Route::get('/test_email3', function(){Mail::to('tahayassine28470618@gmail.com')->send(new \App\Mail\Alert_Stock3());});
Route::get('/test_emailCustomer', function(){$customer_name="taha zouari";$hour="22:30";Mail::to('tahayassine28470618@gmail.com')->send(new \App\Mail\Alert_customer($customer_name,$hour));});
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
Route::get('/admin/recent-orders', [AdminController::class, 'fetchRecentOrders'])->name('admin.recentOrders');
Route::middleware(['auth', 'isOrgAdmin'])->group(function () {
    Route::get('/org-dashboard', [OrgDashboardController::class, 'index'])->name('org.dashboard');
    Route::get('/org-dashboard/recent-activities', [OrgDashboardController::class, 'fetchRecentActivities'])->name('org.dashboard.recentActivities');
});


Route::post('/login', [AuthenticationController::class, 'login'])->name('post-login');
Route::get('/logout', [AuthenticationController::class, 'logout'])->name('auth-logout');
Route::group(['prefix' => 'admin','middleware'=>'isadmin'], function () {
    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-admin');
    Route::get('/users', [Users::class, 'index'])->name('users');
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

    });

});
Route::group(['prefix' => 'organisation','middleware'=>'isorganisation'], function () {
    Route::get('/dashboard', [Analytics::class, 'indexOrganisation'])->name('dashboard-organisation');
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
//pour verifier si user identifier wala le
Route::get('/check-auth', function () {
    return auth()->check() ? 'Utilisateur authentifié' : 'Utilisateur non authentifié';
});
Route::get('/check/auth', [AuthenticationController::class, 'checkAuth'])->middleware('auth:api');




// Route sans authentification
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');




// Routes nécessitant une authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('account.settings');
    Route::post('/account/update', [AccountSettingsAccount::class, 'update'])->name('account.update');
});Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// cards routes
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