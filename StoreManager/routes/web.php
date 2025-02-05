<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApplyTaxRate;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('/', [App\Http\Controllers\SaleController::class, 'index'])->name('sale.index')->middleware(ApplyTaxRate::class);

    Route::post('/buy', [App\Http\Controllers\SaleController::class, 'buy'])->name('sale.buy')->middleware(ApplyTaxRate::class);

    Route::post('/customer', [App\Http\Controllers\SaleController::class, 'customer'])->name('sale.customer')->middleware(ApplyTaxRate::class);

    Route::get('/reset', [App\Http\Controllers\SaleController::class, 'reset'])->name('sale.reset')->middleware(ApplyTaxRate::class);

    Route::get('/cancel/{index}', [App\Http\Controllers\SaleController::class, 'cancel'])->name('sale.cancel')->middleware(ApplyTaxRate::class);

    Route::get('/payment', [App\Http\Controllers\SaleController::class, 'payment'])->name('sale.payment')->middleware(ApplyTaxRate::class);

    Route::post('/pay', [App\Http\Controllers\SaleController::class, 'pay'])->name('sale.pay')->middleware(ApplyTaxRate::class);

    Route::get('/paid', [App\Http\Controllers\SaleController::class, 'paid'])->name('sale.paid')->middleware(ApplyTaxRate::class);

    Route::get('/user/parsonal/', [App\Http\Controllers\UserController::class, 'parsonal'])->name('user.parsonal');

    Route::patch('/user/parsonal/{id}', [App\Http\Controllers\UserController::class, 'parsonalUpdate'])->name('user.parsonalUpdate');

    Route::get('/attendance/clock', [App\Http\Controllers\AttendanceController::class, 'clock'])->name('attendance.clock');

    Route::get('/attendance/clock/in', [App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendance.in');

    Route::get('/attendance/clock/out', [App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendance.out');

    Route::get('/attendance/clock/start', [App\Http\Controllers\AttendanceController::class, 'breakStart'])->name('attendance.start');

    Route::get('/attendance/clock/end', [App\Http\Controllers\AttendanceController::class, 'breakEnd'])->name('attendance.end');

    Route::get('/attendance/table', [App\Http\Controllers\AttendanceController::class, 'table'])->name('attendance.table');

    Route::get('/attendance/search', [App\Http\Controllers\AttendanceController::class, 'search'])->name('attendance.search');


    Route::middleware(['trainer'])->group(function () {

        Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index');

        Route::get('/product/search', [App\Http\Controllers\ProductController::class, 'search'])->name('product.search');

        Route::get('/product/create', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create');

        Route::post('/product/store', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');

        Route::get('/product/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit');

        Route::patch('/product/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update');

        Route::post('/product/{id}', [App\Http\Controllers\ProductController::class, 'delete'])->name('product.delete');

        Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory.index');

        Route::get('/inventory/search', [App\Http\Controllers\InventoryController::class, 'search'])->name('inventory.search');

        Route::get('/inventory/{id}', [App\Http\Controllers\InventoryController::class, 'edit'])->name('inventory.edit');

        Route::patch('/inventory/{id}', [App\Http\Controllers\InventoryController::class, 'update'])->name('inventory.update');

        Route::get('/order/create/{id}', [App\Http\Controllers\InventoryController::class, 'create'])->name('order.create');

        Route::post('/order/store/{id}', [App\Http\Controllers\InventoryController::class, 'store'])->name('order.store');

        Route::get('/order', [App\Http\Controllers\InventoryController::class, 'plan'])->name('order.plan');

        Route::get('/order/receive', [App\Http\Controllers\InventoryController::class, 'receive'])->name('order.receive');

        Route::post('/order/received', [App\Http\Controllers\InventoryController::class, 'received'])->name('order.received');

        Route::post('/order/{id}', [App\Http\Controllers\InventoryController::class, 'delete'])->name('order.delete');

        Route::get('/customer', [App\Http\Controllers\CustomerController::class, 'index'])->name('customer.index');

        Route::get('/customer/search', [App\Http\Controllers\CustomerController::class, 'search'])->name('customer.search');

        Route::get('/customer/create', [App\Http\Controllers\CustomerController::class, 'create'])->name('customer.create');

        Route::post('/customer/store', [App\Http\Controllers\CustomerController::class, 'store'])->name('customer.store');

        Route::get('/customer/{id}', [App\Http\Controllers\CustomerController::class, 'edit'])->name('customer.edit');

        Route::patch('/customer/{id}', [App\Http\Controllers\CustomerController::class, 'update'])->name('customer.update');

        Route::post('/customer/{id}', [App\Http\Controllers\CustomerController::class, 'delete'])->name('customer.delete');

        Route::get('/data', [App\Http\Controllers\DataController::class, 'index'])->name('data.index');

        Route::post('/data/analysis', [App\Http\Controllers\DataController::class, 'analysis'])->name('data.analysis');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');

        Route::get('/user/create', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');

        Route::post('/user/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');

        Route::get('/user/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');

        Route::patch('/user/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');

        Route::post('/user/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete');

        Route::get('/system', [App\Http\Controllers\SystemController::class, 'index'])->name('system.index');

        Route::post('/system/lang', [App\Http\Controllers\SystemController::class, 'switchLanguage'])->name('language.switch');

        Route::post('/system/cur', [App\Http\Controllers\SystemController::class, 'switchCurrency'])->name('currency.switch');

        Route::post('/system/timezone', [App\Http\Controllers\SystemController::class, 'switchTimezone'])->name('timezone.switch');

        Route::get('/tax', [App\Http\Controllers\TaxController::class, 'index'])->name('tax.index');

        Route::post('/tax', [App\Http\Controllers\TaxController::class, 'changeTax'])->name('tax.changeTax');

        Route::get('/tax/edit/{id}', [App\Http\Controllers\TaxController::class, 'edit'])->name('tax.edit');

        Route::patch('/tax/edit/{id}', [App\Http\Controllers\TaxController::class, 'update'])->name('tax.update');

        Route::get('/attendance/select', [App\Http\Controllers\AttendanceController::class, 'select'])->name('attendance.select');

        Route::get('/attendance/edit/{id}', [App\Http\Controllers\AttendanceController::class, 'edit'])->name('attendance.edit');

        Route::patch('/attendance/update', [App\Http\Controllers\AttendanceController::class, 'update'])->name('attendance.update');

        Route::get('/attendance/total', [App\Http\Controllers\AttendanceController::class, 'total'])->name('attendance.total');

        Route::post('/attendance/total/table', [App\Http\Controllers\AttendanceController::class, 'totalTable'])->name('attendance.totalTable');
    });
});
