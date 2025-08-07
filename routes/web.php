<?php

use App\Http\Controllers\Application_settings\application_settingsController;
use App\Http\Controllers\Application_settings\CurrenciesController;
use App\Http\Controllers\Application_settings\Nationalities_settingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
        // إعدادات الإدارة المالية
        Route::get('finance/settings', [application_settingsController::class, 'financeSettings'])->name('finance.settings');
        // صفحة الحسابات المالية
        Route::get('finance/', [application_settingsController::class, 'mainIndex'])->name('finance.accounts.index');
        // صفحة عمليات الحسابات المالية
        Route::get('finance/accounts', [application_settingsController::class, 'accountsIndex'])->name('finance.accounts.manage');
        // صفحة البنود المالية
        Route::get('finance/items', [application_settingsController::class, 'itemsIndex'])->name('finance.items.index');

        Route::get('finance/index', function () {
    return view('finance.index'); // اللي فيه @livewire('finance.index')
})->name('finance.transactions.index');


        Route::group(['namespace' => 'Application_settings'], function () {
            Route::resource('places_settings', 'place_settingsController');
            Route::resource('countries', 'CountriesController');
            Route::get('/city/{id}', 'CityController@getGovernment');
            Route::resource('city', 'CityController');
            Route::resource('government', 'GovernmentController');
            Route::get('/area/{id}', 'areaController@getcity');
            Route::resource('area', 'areaController');
            Route::resource('settings_type', SettingsTypeController::class);

            Route::resource('settings', application_settingsController::class);
            Route::resource('currencies', currenciesController::class);
            Route::resource('nationalities_settings', nationalities_settingsController::class);
        });

        Route::group(['namespace' => 'Shendy'], function () {
            Route::resource('clients', 'ClientsController');
            Route::resource('projects', 'ProjectsController');

            Route::resource('offers', 'OffersController');

            Route::get('/offers/followup/{offerId}', 'OffersController@Followup'::class)->name('offers.followup');
            Route::get('offers/{offer}/status', 'OffersController@OfferStatus'::class)->name('offers.status');

            Route::resource('files', 'FilesController');
            Route::resource('finance', 'FinanceController');

            Route::resource('employees', 'EmployeesController');
            Route::get('/employees/{id}/show', 'EmployeesController@show')->name('employees.show');

            Route::resource('users', 'UsersController');
            Route::resource('roles', 'RolesController');
            Route::resource('notifications', 'NotificationsController');
        });

        Route::group(['namespace' => 'dashbord'], function () {
            Route::resource('dashbord', 'dashbordController');
        });

        Route::get('/{page}', 'AdminController@index');
    });

Auth::routes();
//Auth::routes(['register' => false]);
