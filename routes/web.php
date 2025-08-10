<?php

use App\Http\Controllers\Application_settings\application_settingsController;
use App\Http\Controllers\Application_settings\CurrenciesController;
use App\Http\Controllers\Application_settings\Nationalities_settingsController;
use App\Http\Controllers\Finance\TransactionsController;
use App\Http\Livewire\Clients\Show as ClientShow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use App\Http\Controllers\Shendy\ClientsController;

Auth::routes(['verify' => true]);

// صفحة الدخول للضيوف
Route::group(['middleware' => ['guest']], function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

// كل الراوتس بلغات متعددة + مع حماية Auth/Verified
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth', 'verified'],
    ],
    function () {

        /*
        |-----------------------------|
        | Finance / Transactions CRUD |
        |-----------------------------|
        */
    Route::prefix('finance/transactions')->name('finance.transactions.')->group(function () {
    Route::get('/',               TxIndex::class)->name('index');
    Route::get('/create/expense', CreateExpense::class)->name('create.expense');
    Route::get('/create/income',  CreateCollection::class)->name('create.income');
    Route::get('/{transactionId}/edit', Edit::class)->name('edit');
});

        /*
        |------------------|
        | Finance Settings |
        |------------------|
        */
        Route::get('finance/settings', [application_settingsController::class, 'financeSettings'])->name('finance.settings');
        Route::get('finance/', [application_settingsController::class, 'mainIndex'])->name('finance.accounts.index');
        Route::get('finance/accounts', [application_settingsController::class, 'accountsIndex'])->name('finance.accounts.manage');
        Route::get('finance/items', [application_settingsController::class, 'itemsIndex'])->name('finance.items.index');


        /*
        |------------------------|
        | Application_settings   |
        |------------------------|
        */
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

        /*
        |--------|
        | Shendy |
        |--------|
        */
        Route::group(['namespace' => 'Shendy'], function () {
            Route::resource('clients', 'ClientsController');

            Route::resource('projects', 'ProjectsController');
            Route::resource('offers', 'OffersController');

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

        /*
        |-----------|
        | dashboard |
        |-----------|
        */
        Route::group(['namespace' => 'dashbord'], function () {
            Route::resource('dashbord', 'dashbordController');
        });

        // catch-all (أبقيه آخر سطر)
        Route::get('/{page}', 'AdminController@index');
    }
);
