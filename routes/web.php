<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Livewire\Offers\Show;
use App\Http\Controllers\OfferController;
use App\Http\Livewire\Offers\OfferStatus;



Auth::routes(['verify'=>true]);

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
Route::group(['middleware'=>['guest']],function(){
    Route::get('/', function()
     {
         return view('auth.login');
       
         
     });
 
 });
 Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath','auth','verified' ]
    ],
    function () {

        Route::group(['namespace' => 'Application_settings'], function () {
            Route::resource('places_settings', 'place_settingsController');
            Route::resource('countries', 'CountriesController');
            Route::get('/city/{id}', 'CityController@getGovernment');
            Route::resource('city', 'CityController');
            Route::resource('government', 'GovernmentController');
            Route::get('/area/{id}', 'areaController@getcity');
            Route::resource('area', 'areaController');
            Route::resource('settings_type', 'settings_typeController');
            Route::resource('settings', 'application_settingsController');
            Route::resource('currencies', 'currenciesController');
            Route::resource('nationalities_settings', 'nationalities_settingsController');
        });

        Route::group(['namespace' => 'Shendy'], function () {
            Route::resource('clients', 'ClientsController');
            Route::resource('projects', 'ProjectsController');
            Route::resource('offers', 'OffersController');

            Route::get('/offers/show/{id}', 'OffersController@show')->name('offers.show');
            Route::get('/offers/edit/{id}', 'OffersController@edit')->name('offers.edit');
            Route::get('/offers/followup/{offerId}', 'OffersController@Followup'::class)->name('offers.followup');
            Route::get('offers/{offer}/status', 'OffersController@OfferStatus'::class)->name('offers.status');

            
            Route::resource('files', 'FilesController');
            Route::resource('finance', 'FinanceController');
            Route::resource('employees', 'EmployeesController');
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