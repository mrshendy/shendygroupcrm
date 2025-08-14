<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Application Settings Controllers
use App\Http\Controllers\Application_settings\application_settingsController;
use App\Http\Controllers\Application_settings\CurrenciesController;
use App\Http\Controllers\Application_settings\Nationalities_settingsController;
use App\Http\Controllers\Application_settings\place_settingsController;
use App\Http\Controllers\Application_settings\CountriesController;
use App\Http\Controllers\Application_settings\CityController;
use App\Http\Controllers\Application_settings\GovernmentController;
use App\Http\Controllers\Application_settings\areaController;
use App\Http\Controllers\Application_settings\SettingsTypeController;

// Finance Controllers
use App\Http\Controllers\Finance\TransactionsController;

// Shendy Controllers
use App\Http\Controllers\Shendy\ClientsController;
use App\Http\Controllers\Shendy\ProjectsController;
use App\Http\Controllers\Shendy\OffersController;
use App\Http\Controllers\Shendy\ContractsController;
use App\Http\Controllers\Shendy\ContractController;
use App\Http\Controllers\Shendy\FilesController;
use App\Http\Controllers\Shendy\FinanceController;
use App\Http\Controllers\Shendy\EmployeesController;
use App\Http\Controllers\Shendy\UsersController;
use App\Http\Controllers\Shendy\RolesController;
use App\Http\Controllers\Shendy\NotificationsController;
// Dashboard & Admin
use App\Http\Controllers\dashbord\dashbordController;
use App\Http\Controllers\AdminController;

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['guest']], function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth', 'verified'],
    ],
    function () {

        /** إعدادات المالية */
        Route::get('finance/settings', [application_settingsController::class, 'financeSettings'])->name('finance.settings');
        Route::get('finance/', [application_settingsController::class, 'mainIndex'])->name('finance.accounts.index');
        Route::get('finance/accounts', [application_settingsController::class, 'accountsIndex'])->name('finance.accounts.manage');
        Route::get('finance/items', [application_settingsController::class, 'itemsIndex'])->name('finance.items.index');

        /** المعاملات المالية */
        Route::get('finance/transactions', [TransactionsController::class, 'index'])->name('finance.transactions.index');
        Route::get('finance/transactions/create/expense', [TransactionsController::class, 'createExpense'])->name('finance.transactions.create.expense');
        Route::get('finance/transactions/create/income', [TransactionsController::class, 'createIncome'])->name('finance.transactions.create.income');
        Route::get('finance/transactions/edit/{transactionId}', [TransactionsController::class, 'edit'])->name('finance.transactions.edit');
        Route::get('finance/transactions/show/{transactionId}', [TransactionsController::class, 'show'])->name('finance.transactions.show');

        /** Application Settings */
        Route::resource('places_settings', place_settingsController::class);
        Route::resource('countries', CountriesController::class);
        Route::get('/city/{id}', [CityController::class, 'getGovernment']);
        Route::resource('city', CityController::class);
        Route::resource('government', GovernmentController::class);
        Route::get('/area/{id}', [areaController::class, 'getcity']);
        Route::resource('area', areaController::class);
        Route::resource('settings_type', SettingsTypeController::class);

        Route::resource('settings', application_settingsController::class);
        Route::resource('currencies', CurrenciesController::class);
        Route::resource('nationalities_settings', Nationalities_settingsController::class);

        /** Shendy */
        Route::resource('clients', ClientsController::class);
        Route::resource('projects', ProjectsController::class);
        Route::resource('offers', OffersController::class);
        Route::resource('contracts', ContractsController::class);

        // روابط العقد: تنزيل/معاينة
        Route::get('/contracts/{contract}/download', [ContractController::class, 'download'])
            ->whereNumber('contract')->name('contracts.download');
        Route::get('/contracts/{contract}/preview', [ContractController::class, 'preview'])
            ->whereNumber('contract')->name('contracts.preview');

        // عروض الأسعار: متابعة وحالة
        Route::get('/offers/followup/{offerId}', [OffersController::class, 'Followup'])->name('offers.followup');
        Route::get('offers/{offer}/status', [OffersController::class, 'OfferStatus'])->name('offers.status');

        // باقي الموارد
        Route::resource('files', FilesController::class);
        Route::resource('finance', FinanceController::class);

        /** مجموعة الموظفين */
        Route::resource('employees', EmployeesController::class);

        /** المستخدمين والصلاحيات */
        Route::resource('users', UsersController::class);
        Route::resource('roles', RolesController::class);
        Route::resource('notifications', NotificationsController::class);

        /** Dashboard */
        Route::resource('dashbord', dashbordController::class);

        /** Catch-All */
        Route::get('/{page}', [AdminController::class, 'index']);
    }
);
