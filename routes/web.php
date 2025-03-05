<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RegisterEmployeeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StkeeperController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartwaiterConrtoller;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\GalaryController;
use App\Http\Controllers\About_restauranController;
use App\Http\Controllers\PositionController;
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

    // Route::get('stkeeper/{table}', [StkeeperController::class, 'show'])->name('stkeeper.show');
    // Route::get('stkeeper/{table}/{id}/edit', [StkeeperController::class, 'edit'])->name('stkeeper.edit');
    // Route::put('stkeeper/{table}/{id}', [StkeeperController::class, 'update'])->name('stkeeper.update');
    // Route::delete('stkeeper/{table}/{id}', [StkeeperController::class, 'destroy'])->name('stkeeper.destroy');
    // Route::get('stkeeper/{table}/create', [StkeeperController::class, 'create'])->name('stkeeper.create');
    // Route::post('stkeeper/{table}', [StkeeperController::class, 'store'])->name('stkeeper.store');
    // Route::get('stkeeper.expired', [StkeeperController::class, 'expired'])->name('stkeeper.expired');
    // Route::put('stkeeper/{table}/{id}/writeOff', [StkeeperController::class, 'writeOff'])->name('stkeeper.writeOff');
});

Route::middleware(['auth', 'role:Курьер'])->group(function () {
    Route::get('courier', [CourierController::class, 'index'])->name('courier');
    Route::post('/courier/start-delivery/{order}', [CourierController::class, 'startDelivery'])->name('courier.startDelivery');
    Route::post('/courier/deliver/{order}', [CourierController::class, 'deliver'])->name('courier.deliver');
});

Route::middleware(['auth', 'role:Повар'])->group(function () {
    Route::get('chef', [ChefController::class, 'index'])->name('chef.index');
    Route::post('/chef/start-cooking/{orderItemId}', [ChefController::class, 'startCooking'])->name('chef.startCooking');
    Route::post('/chef/finish-cooking/{orderItemId}', [ChefController::class, 'finishCooking'])->name('chef.finishCooking');
});

Route::middleware(['auth', 'role:Официант'])->group(function () {
    Route::get('waiter', [WaiterController::class, 'index'])->name('waiter.index');
    Route::get('orders', [WaiterController::class, 'orders'])->name('waiter.orders');
    Route::post('/waiter/mark-as-served/{orderItemId}', [WaiterController::class, 'markAsServed'])->name('waiter.markAsServed');
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
