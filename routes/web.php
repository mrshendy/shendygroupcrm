<?php

use App\Http\Controllers\Application_settings\application_settingsController;
use App\Http\Controllers\Application_settings\CurrenciesController;
use App\Http\Controllers\Application_settings\Nationalities_settingsController;
use App\Http\Controllers\Finance\TransactionsController;
use App\Http\Livewire\Employees\Leaves\Manage as LeavesManage;
use App\Http\Livewire\Employees\Salaries\Manage as SalariesManage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

        // شاشة إدارة المرتبات
        Route::get('/employees/salaries', SalariesManage::class)
            ->name('employees.salaries');

        // شاشة إدارة الإجازات
        Route::get('/employees/leaves', LeavesManage::class)
            ->name('employees.leaves');
        // إعدادات المالية
        Route::get('finance/settings', [application_settingsController::class, 'financeSettings'])->name('finance.settings');
        Route::get('finance/', [application_settingsController::class, 'mainIndex'])->name('finance.accounts.index');
        Route::get('finance/accounts', [application_settingsController::class, 'accountsIndex'])->name('finance.accounts.manage');
        Route::get('finance/items', [application_settingsController::class, 'itemsIndex'])->name('finance.items.index');

        // معاملات مالية
        Route::get('finance/transactions', [TransactionsController::class, 'index'])->name('finance.transactions.index');
        Route::get('finance/transactions/create/expense', [TransactionsController::class, 'createExpense'])->name('finance.transactions.create.expense');
        Route::get('finance/transactions/create/income', [TransactionsController::class, 'createIncome'])->name('finance.transactions.create.income');
        Route::get('finance/transactions/edit/{transactionId}', [TransactionsController::class, 'edit'])->name('finance.transactions.edit');
        Route::get('finance/transactions/show/{transactionId}', [TransactionsController::class, 'show'])->name('finance.transactions.show');

        // Application_settings (مزيج سترينج + كلاس)
        Route::group(['namespace' => 'Application_settings'], function () {
            Route::resource('places_settings', 'place_settingsController');
            Route::resource('countries', 'CountriesController');
            Route::get('/city/{id}', 'CityController@getGovernment');
            Route::resource('city', 'CityController');
            Route::resource('government', 'GovernmentController');
            Route::get('/area/{id}', 'areaController@getcity');
            Route::resource('area', 'areaController');
            Route::resource('settings_type', 'SettingsTypeController');

            Route::resource('settings', application_settingsController::class);
            Route::resource('currencies', CurrenciesController::class);
            Route::resource('nationalities_settings', Nationalities_settingsController::class);
        });

        // Shendy
        Route::group(['namespace' => 'Shendy'], function () {
            Route::resource('clients', 'ClientsController');
            Route::resource('projects', 'ProjectsController');
            Route::resource('offers', 'OffersController');
            Route::resource('contracts', 'ContractsController');
            // روابط الكنترولر: تنزيل/معاينة ملف العقد
            Route::get('/contracts/{contract}/download', [ContractController::class, 'download'])
                ->whereNumber('contract')->name('contracts.download');

            Route::get('/contracts/{contract}/preview', [ContractController::class, 'preview'])

                ->whereNumber('contract')->name('contracts.preview');
            Route::get('/offers/followup/{offerId}', 'OffersController@Followup')->name('offers.followup');
            Route::get('offers/{offer}/status', 'OffersController@OfferStatus')->name('offers.status');
            Route::resource('files', 'FilesController');
            Route::resource('finance', 'FinanceController');
            Route::resource('employees', 'EmployeesController');
            Route::get('/employees/{id}/show', 'EmployeesController@show')->name('employees.show');
            Route::resource('users', 'UsersController');
            Route::resource('roles', 'RolesController');
            Route::resource('notifications', 'NotificationsController');
        });

        // dashbord
        Route::group(['namespace' => 'dashbord'], function () {
            Route::resource('dashbord', 'dashbordController');
        });

        // catch-all (خليه آخر سطر دائمًا)
        Route::get('/{page}', 'AdminController@index');
    }
);
