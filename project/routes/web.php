<?php

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



Route::group(['middleware'=>'web'],function() {


//ROUTES FOR ADMIN ROLE
Route::group(['middleware' => ['role:admin']], function () {

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


    Route::prefix('admin')->group(function () {
        Route::get('/home', 'AdminController@home')->name('home-admin');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/acl', 'AdminController@acl')->name('acl');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-acl', 'AdminController@getAcl')->name('get-acl');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/edit-role/{id}', 'AdminController@editRoleView')->name('edit-role');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-role-permissions/{id}', 'AdminController@getRolePermissions')->name('role-permissions');
    });

    Route::prefix('admin')->group(function () {
        Route::patch('/update-permissions/{id}', 'AdminController@updatePermissions')->name('update-permissions');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/users', 'AdminController@users')->name('users');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-users', 'AdminController@getUsers')->name('get-users');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/delete-user/{id}', 'AdminController@deleteUser')->name('delete-user');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/add-user', 'AdminController@addUserView')->name('add-user-view');
    });

    Route::prefix('admin')->group(function () {
        Route::post('/create-user', 'AdminController@createUser')->name('create-user');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/edit-user/{id}', 'AdminController@editUserView')->name('edit-user-view');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-user/{id}', 'AdminController@getUser')->name('get-user');
    });

    Route::prefix('admin')->group(function () {
        Route::patch('/update-user/{id}', 'AdminController@updateUser')->name('update-user');
    });

    Route::prefix('admin')->group(function () {
        Route::patch('/update-profile/{id}', 'AdminController@updateProfile')->name('update-profile');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/profile', 'AdminController@profileView')->name('admin-profile');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/events', 'AdminController@eventsView')->name('events-view');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-events', 'AdminController@getEvents')->name('get-events');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/delete-event/{id}', 'AdminController@deleteEvent')->name('delete-event');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/transactions', 'AdminController@transactionsView')->name('transactions-view');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-transactions', 'AdminController@getTransactions')->name('get-transactions');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/delete-transaction/{id}', 'AdminController@deleteTransaction')->name('delete-transaction');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/rules/{id}', 'AdminController@employeeRulesView')->name('employee-rules');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-employee-calendar/{id}', 'AdminController@getEmployeeCalendar')->name('employee-calendar');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-employee-calendar-availability/{id}', 'AdminController@getEmployeeCalendarAvailability')->name('employee-calendar');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-employee-hour-availability/{id}', 'AdminController@getHourAvailability')->name('employee-calendar');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/employee-get-users', 'AdminController@employeeGetUsers')->name('get-users');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-employee-days-markers/{id}', 'AdminController@getEmployeeDaysMarkers')->name('employee-days-markers');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-employee-events/{id}', 'AdminController@getEmployeeEvents')->name('employee-get-events');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-employee-schedule/{id}', 'AdminController@getEmployeeSchedule')->name('employee-schedule');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-employee-special-schedule/{id}', 'AdminController@getEmployeeSpecialSchedule')->name('employee-special-schedule');
    });

    Route::prefix('admin')->group(function () {
        Route::post('/calendar-employee-settings/{id}', 'AdminController@employeeCalendarSettings')->name('employee-calendar-settings');
    });

    Route::prefix('admin')->group(function () {
        Route::post('/save-employee-schedule/{id}', 'AdminController@saveEmployeeSchedule')->name('save-employee-schedule');
    });

    Route::prefix('admin')->group(function () {
        Route::post('/save-employee-special-schedule/{id}', 'AdminController@saveEmployeeSpecialSchedule')->name('save-employee-special-schedule');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/get-employees', 'AdminController@getEmployees')->name('get-employees');
    });

    Route::prefix('admin')->group(function () {
        Route::post('/create-event', 'AdminController@createEvent')->name('create-event');
    });

    Route::prefix('admin')->group(function () {
        Route::post('/create-appointment', 'AdminController@createAppointment')->name('create-appointment');
    });

});


//ROUTES FOR USER ROLE
Route::group(['middleware' => ['role:user']], function () {



    Route::prefix('user')->group(function () {
        Route::get('/home', 'UserController@home')->name('home-user')->middleware('verified');
    });



    Route::group(['middleware' => ['permission:book appointment']], function () {
        Route::prefix('user')->group(function () {
            Route::get('/book-appointment', 'UserController@bookAppointmentView')->name('book-appointment')->middleware('verified');
        });
    });

    Route::prefix('user')->group(function () {
        Route::get('/check-permission', 'UserController@checkPermission')->name('check-permission');
    });


    Route::prefix('user')->group(function () {
            Route::get('/verify/{token}', 'HomeController@verifyUser');
     });




    Route::prefix('user')->group(function () {
        Route::get('/get-user/{id}', 'UserController@getUser')->name('get-user-user');
    });



    Route::group(['middleware' => ['permission:edit user profile']], function () {
        Route::prefix('user')->group(function () {
            Route::patch('/update-profile/{id}', 'UserController@updateProfile')->name('update-profile-user');
        });

        Route::prefix('user')->group(function () {
            Route::get('/profile', 'UserController@profileView')->name('user-profile')->middleware('verified');;
        });
    });



    Route::prefix('user')->group(function () {
        Route::get('/get-employees', 'UserController@getEmployees')->name('get-employees');
    });

    Route::prefix('user')->group(function () {
        Route::get('/get-calendar-availability/{id}', 'UserController@getCalendarAvailability')->name('get-calendar-availability');
    });

    Route::prefix('user')->group(function () {
        Route::get('/get-calendar-info/{id}', 'UserController@getCalendarInfo')->name('get-calendar-info');
    });

    Route::prefix('user')->group(function () {
        Route::get('/get-full-bookings', 'UserController@getFullBookings')->name('get-full-bookings');
    });

    Route::prefix('user')->group(function () {
        Route::get('/get-timeslots', 'UserController@getHourAvailability')->name('get-timeslots');
    });

    Route::prefix('user')->group(function () {
        Route::post('/complete-event', 'UserController@completeEvent')->name('complete');
    });

    Route::group(['middleware' => ['permission:view user transactions']], function () {
        Route::prefix('user')->group(function () {
            Route::get('/transactions', 'UserController@transactionsView')->name('transactions');
        });

        Route::prefix('user')->group(function () {
            Route::get('/get-transactions', 'UserController@getTransactions')->name('get-transactions');
        });
    });



    Route::prefix('user')->group(function () {
        Route::get('/is-verified', 'UserController@isVerified')->name('is-verified');
    });

    Route::prefix('user')->group(function () {
        Route::get('/get-events', 'UserController@getEvents')->name('get-events');
    });

    Route::prefix('user')->group(function () {
        Route::get('/get-pending-events', 'UserController@getPendingEvents')->name('get-pending-events');
    });

    Route::prefix('user')->group(function () {
        Route::get('/get-events-day', 'UserController@getEventsDay')->name('get-events-day');
    });


});


//ROUTES FOR EMPLOYEE ROLE
Route::group(['middleware' => ['role:employee']], function () {


    Route::prefix('employee')->group(function () {
        Route::get('/home', 'EmployeeController@home')->name('home-employee');
    });


    Route::prefix('employee')->group(function () {
        Route::get('/check-permissions', 'EmployeeController@checkPermissions')->name('check-permissions');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/get-user/{id}', 'EmployeeController@getUser')->name('get-employee-user');
    });

    Route::prefix('employee')->group(function () {
        Route::patch('/update-profile/{id}', 'EmployeeController@updateProfile')->name('update-profile-employee');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/profile', 'EmployeeController@profileView')->name('employee-profile');
    });

    Route::group(['middleware' => ['permission:edit rules']], function () {
        Route::prefix('employee')->group(function () {
            Route::get('/settings', 'EmployeeController@settingsView')->name('employee-settings');
        });
    });

    Route::group(['middleware' => ['permission:view employee transactions']], function () {
        Route::prefix('employee')->group(function () {
            Route::get('/transactions', 'EmployeeController@transactionsView')->name('transactions');
        });

        Route::prefix('employee')->group(function () {
            Route::get('/get-transactions', 'EmployeeController@getTransactions')->name('get-transactions');
        });
    });

    Route::group(['middleware' => ['permission:view employee events']], function () {
        Route::prefix('employee')->group(function () {
            Route::get('/events', 'EmployeeController@customEventsView')->name('events');
        });

        Route::prefix('employee')->group(function () {
            Route::get('/get-custom-events', 'EmployeeController@getCustomEvents')->name('get-custom-events');
        });
    });


    Route::prefix('employee')->group(function () {
        Route::post('/calendar-settings', 'EmployeeController@calendarSettings')->name('calendar-settings');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/get-calendar', 'EmployeeController@getCalendar')->name('get-calendar');
    });

    Route::prefix('employee')->group(function () {
        Route::post('/save-schedule', 'EmployeeController@saveSchedule')->name('save-schedule');
    });



    Route::prefix('employee')->group(function () {
        Route::get('/get-schedule', 'EmployeeController@getSchedule')->name('get-schedule');
    });

    Route::prefix('employee')->group(function () {
        Route::post('/save-special-schedule', 'EmployeeController@saveSpecialSchedule')->name('save-special-schedule');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/get-special-schedule', 'EmployeeController@getSpecialSchedule')->name('get-special-schedule');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/get-days-markers', 'EmployeeController@getDaysMarkers')->name('get-days-markers');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/get-calendar-availability', 'EmployeeController@getCalendarAvailability')->name('get-calendar-availability');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/get-hour-availability', 'EmployeeController@getHourAvailability')->name('get-hour-availability');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/get-users', 'EmployeeController@getUsers')->name('get-users');
    });

    Route::prefix('employee')->group(function () {
        Route::post('/create-appointment', 'EmployeeController@createAppointment')->name('create-appointment');
    });

    Route::prefix('employee')->group(function () {
        Route::post('/create-event', 'EmployeeController@createEvent')->name('create-event');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/get-events', 'EmployeeController@getEvents')->name('get-events');
    });






    Route::prefix('employee')->group(function () {
        Route::get('/home', 'HomeController@index')->name('home');
    });

});





//HOMEPAGE REDIRECTIONS BASED ON USER ROLE
    Route::get('/', function () {

        if (Auth::check()) {

            if(Auth::user()->hasRole('admin')){

                return redirect('/admin/home');
            }
            if(Auth::user()->hasRole('user')){

                return redirect('/user/home');
            }
            if(Auth::user()->hasRole('employee')){

                return redirect('/employee/home');
            }


        }

        return view('auth.login');
    });



    Route::get('/home', function () {

        return redirect('/');
    });





Auth::routes();


//ROUTE FOR GOOGLE CALENDAR AUTHENTICATION
Route::get('/auth/calendar', 'EmployeeController@authenticate')->name('authenticate');




//PERMISSION AND SESSION EXPIRED ERROR PAGES
Route::get('/session-expired', function () {
    return view('errors.token');
});

Route::get('/restricted', function () {
    return view('errors.permission');
});

});