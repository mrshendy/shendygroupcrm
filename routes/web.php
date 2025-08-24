<?php

use App\Http\Controllers\Application_settings\application_settingsController;
use App\Http\Controllers\Application_settings\CurrenciesController;
use App\Http\Controllers\Application_settings\Nationalities_settingsController;
use App\Http\Controllers\Finance\TransactionsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\UserController;
// (اختياري) لو هتستخدمه في مكان تاني
// use App\Http\Livewire\Clients\Show as ClientShow;


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
        Route::resource('users', 'UserController');
        Route::resource('roles', 'RoleController');
        Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change-password');       
        // إعدادات المالية
        Route::get('finance/settings', [application_settingsController::class, 'financeSettings'])->name('finance.settings');
        Route::get('finance/', [application_settingsController::class, 'mainIndex'])->name('finance.accounts.index');
        Route::get('finance/accounts', [application_settingsController::class, 'accountsIndex'])->name('finance.accounts.manage');
        Route::get('finance/accounts/{id}', [application_settingsController::class, 'accountEdit'])->name('finance.accounts.edit');
        Route::get('finance/items', [application_settingsController::class, 'itemsIndex'])->name('finance.items.index');
        Route::get('finance/items/{id}', [application_settingsController::class, 'itemEdit'])->name('finance.items.edit');

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

            // الرواتب والإجازات
            Route::get('employees/salaries', 'EmployeesController@salaries')->name('employees.salaries');
            Route::get('employees/salaries/{id}/edit', 'EmployeesController@editSalary')->name('salaries.edit');
            Route::get('employees/leaves', 'EmployeesController@leaves')->name('employees.leaves');
            Route::get('employees/leaves/create', 'EmployeesController@createLeave')->name('leaves.create');
            Route::get('employees/leave-balances', 'EmployeesController@leaveBalances')->name('leave-balances.manage');
            Route::get('employees/shifts', 'EmployeesController@shifts')->name('shifts.manage');

            //  الحضور والانصراف
            Route::get('/attendance', 'EmployeesController@attendanceCheck')->name('attendance.check');
            Route::get('/attendance/manage', 'EmployeesController@attendanceManage')->name('attendance.manage');
            Route::get('/attendance/{id}/edit', 'EmployeesController@editattendance')->name('attendance.attendanceedit');

            //Employees
            Route::resource('employees', 'EmployeesController');

           
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

