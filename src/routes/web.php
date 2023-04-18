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
                ['customer_service/mail','\CustomerService\MailController'],['customer_service/mail_template','\CustomerService\MailTemplateController'],
                ['order','\OrderController'],
                ['customer_service/mail_task','\CustomerService\MailTaskController'],
                ['work/report','\Work\ReportController']
            ];
            foreach ($route_arr as $val){
                Route::get($val[0].'/index', 'Aphly\LaravelCompany\Controllers\Admin'.$val[1].'@index');
                Route::match(['get','post'],$val[0].'/add', 'Aphly\LaravelCompany\Controllers\Admin'.$val[1].'@add');
                Route::match(['get','post'],$val[0].'/edit', 'Aphly\LaravelCompany\Controllers\Admin'.$val[1].'@edit');
                Route::post($val[0].'/del', 'Aphly\LaravelCompany\Controllers\Admin'.$val[1].'@del');
            }
            Route::match(['get'],'/order/info', 'Aphly\LaravelCompany\Controllers\Admin\OrderController@info');

            Route::get('customer_service/mail_task/order', 'Aphly\LaravelCompany\Controllers\Admin\CustomerService\MailTaskController@order');
            Route::match(['post','get'],'customer_service/mail_task/import', 'Aphly\LaravelCompany\Controllers\Admin\CustomerService\MailTaskController@import');
            Route::get('customer_service/mail_task/send', 'Aphly\LaravelCompany\Controllers\Admin\CustomerService\MailTaskController@send');
        });
    });

});
