<?php


use Illuminate\Support\Facades\Route;

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


Route::middleware(['web'])->group(function () {

    Route::prefix('company_admin')->middleware(['managerAuth'])->group(function () {

        Route::middleware(['rbac'])->group(function () {

            $route_arr = [
                ['mail','\MailController'],['mail_template','\MailTemplateController'],['order','\OrderController'],
                ['order_mail','\OrderMailController']
            ];
            foreach ($route_arr as $val){
                Route::get($val[0].'/index', 'Aphly\LaravelCompany\Controllers\Admin'.$val[1].'@index');
                Route::get($val[0].'/form', 'Aphly\LaravelCompany\Controllers\Admin'.$val[1].'@form');
                Route::post($val[0].'/save', 'Aphly\LaravelCompany\Controllers\Admin'.$val[1].'@save');
                Route::post($val[0].'/del', 'Aphly\LaravelCompany\Controllers\Admin'.$val[1].'@del');
            }

            Route::match(['post'],'/order_mail/upload', 'Aphly\LaravelCompany\Controllers\Admin\OrderMailController@upload');
            Route::match(['post'],'/order_mail/send', 'Aphly\LaravelCompany\Controllers\Admin\OrderMailController@send');

        });
    });

});
