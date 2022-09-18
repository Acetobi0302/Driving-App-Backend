<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\MyTestMail;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

Route::get('send-mail', function () {
   
    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];
   
    Mail::to('kksr4941@gmail.com')->send(new MyTestMail($details));
   
    dd("Email is Sent.");
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/login', 'AuthController@login');
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->post('/logout', 'AuthController@logout');
    $router->get('/me', 'AuthController@me');

    $router->put('/me', 'UserController@update');
    $router->put('/me/password', 'UserController@updatePassword');

    $router->get('/booking', 'BookingController@index');
    $router->get('/accounts', 'BookingController@accounts');
    $router->get('/booking/student', 'BookingController@studentBookings');

    $router->group(['middleware' => 'isRA'], function () use ($router) {

        $router->get('/student/{id}', 'StudentController@index');
        $router->get('/studentlist/{id}', 'StudentController@list');
        $router->post('/student', 'StudentController@store');
        $router->put('/student/{id}', 'StudentController@update');
        $router->delete('/student/{id}', 'StudentController@delete');

        $router->get('/franchiselist', 'FranchiseController@list');
        $router->get('/carlist', 'CarManagementController@list');
        $router->get('/extracarlist', 'CarManagementController@extraList');
        $router->get('/classes', 'ClassesController@index');
        $router->get('/course_art/{id}', 'CourseArtController@index');
        $router->get('/driverlist/{id}', 'UserController@driverList');

        $router->post('/break', 'BookingController@storeBreak');
        $router->put('/break/{id}', 'BookingController@updateBreak');

        $router->post('/booking', 'BookingController@store');
        $router->put('/booking/{id}', 'BookingController@update');
        $router->delete('/booking/{id}', 'BookingController@delete');
        $router->get('/notes', 'NoteController@index');
        $router->delete('/notes/{id}', 'NoteController@delete');
    });

    $router->group(['middleware' => 'isAdmin'], function () use ($router) {

        $router->get('/users/{id}', 'UserController@index');
        $router->post('/user', 'UserController@store');
        $router->get('/userlist', 'UserController@list');
        $router->put('/user/{id}', 'UserController@userupdate');
        $router->put('/user/password/{id}', 'UserController@userupdatePassword');
        $router->put('/user/restore/{id}', 'UserController@restore');
        $router->delete('/user/{id}', 'UserController@delete');


        $router->get('/franchise', 'FranchiseController@index');
        $router->post('/franchise', 'FranchiseController@store');
        $router->put('/franchise/{id}', 'FranchiseController@update');
        $router->delete('/franchise/{id}', 'FranchiseController@delete');

        $router->get('/car_management/{id}', 'CarManagementController@index');
        $router->post('/car_management', 'CarManagementController@store');
        $router->put('/car_management/{id}', 'CarManagementController@update');
        $router->delete('/car_management/{id}', 'CarManagementController@delete');


        $router->post('/classes', 'ClassesController@store');
        $router->put('/classes/{id}', 'ClassesController@update');
        $router->delete('/classes/{id}', 'ClassesController@delete');


        $router->get('/course_art_list', 'CourseArtController@list');
        $router->post('/course_art', 'CourseArtController@store');
        $router->put('/course_art/{id}', 'CourseArtController@update');
        $router->delete('/course_art/{id}', 'CourseArtController@delete');

        $router->get('/logs', 'LogController@index');
    });
});
