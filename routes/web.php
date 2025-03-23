<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RegisterEmployeeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StkeeperController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\About_restauranController;
use App\Http\Controllers\Master_receiverController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home.index')->name('home');

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['guest'])->group(function () {
    Route::get('about_restauran', [About_restauranController::class, 'index'])->name('about_restauran');
    Route::get('contacts', [ContactsController::class, 'index'])->name('contacts');

    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/cart/order', [CartController::class, 'order'])->name('cart.order');
});

Route::middleware(['auth', 'role:Админ'])->group(function () {
    Route::get('employee', [EmployeeController::class, 'index'])->name('employee');
    Route::get('employee/{table}', [EmployeeController::class, 'show'])->name('employee.show');
    Route::get('employee/{table}/{id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('employee/{table}/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('employee/{table}/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
    Route::get('employee/{table}/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('employee/{table}', [EmployeeController::class, 'store'])->name('employee.store');

    Route::get('registerEmployee', [RegisterEmployeeController::class, 'index'])->name('registerEmployee');
    Route::post('registerEmployee', [RegisterEmployeeController::class, 'store'])->name('registerEmployee.store');

    Route::get('admin/password/reset', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('admin/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
});


Route::middleware(['auth', 'role:Менеджер по закупкам'])->group(function () {
    Route::get('stkeeper', [StkeeperController::class, 'index'])->name('stkeeper');
    Route::get('/stkeeper/zakaz', [StKeeperController::class, 'zakaz'])->name('stkeeper.zakaz');
    Route::post('/stkeeper/zakaz', [StKeeperController::class, 'store'])->name('stkeeper.store');
    Route::get('stkeeper/orders', [StKeeperController::class, 'ordersList'])->name('stkeeper.orders');
    Route::put('stkeeper/orders/{order}/cancel', [StKeeperController::class, 'cancelOrder'])->name('stkeeper.cancel_order');
    Route::get('/stkeeper/order-details/{id}', [StKeeperController::class, 'orderDetails'])->name('stkeeper.order_details');
    Route::put('stkeeper/orders/{order}/delivered', [StKeeperController::class, 'markDelivered'])->name('stkeeper.mark_delivered');

    Route::get('stkeeper/warehouse', [StKeeperController::class, 'warehouse'])->name('stkeeper.warehouse');

    Route::get('/download-invoice/{id}', [StKeeperController::class, 'downloadInvoice'])->name('download.invoice');

});

Route::middleware(['auth', 'role:Мастер-приемщик'])->group(function () {
    Route::get('master_receiver', [Master_receiverController::class, 'index'])->name('master_receiver');
    Route::get('master_receiver/zakaz', [Master_receiverController::class, 'zakaz'])->name('master_receiver.zakaz');
    Route::post('master_receiver/order', [Master_receiverController::class, 'order'])->name('master_receiver.order');
  
});



Route::middleware(['auth', 'role:Директор'])->group(function () {
    Route::get('director', [DirectorController::class, 'index'])->name('director');
    Route::get('/director/reports', [DirectorController::class, 'showReportsPage'])->name('director.reports');
    Route::get('/director/report', [DirectorController::class, 'generateReport'])->name('director.generateReport');
    Route::get('director/report/download', [DirectorController::class, 'downloadPdf'])->name('director.report.download');
    Route::get('/reports/popular-items', [DirectorController::class, 'showPopularItemsPage'])->name('director.showPopularItemsPage');
    Route::get('/reports/popular-items/generate', [DirectorController::class, 'generatePopularItemsReport'])->name('director.generatePopularItemsReport');
    Route::get('/reports/popular-items/download', [DirectorController::class, 'downloadPopularItemsPdf'])->name('director.downloadPopularItemsPdf');
});
