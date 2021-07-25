<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\ManagersController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TruckController;
use App\Models\Bank;
use App\Models\Good;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\Controller::class, 'index'])->name('admin.index');

//Route::get('/home', [DriverController::class, 'index'])->name('admin.index');
Route::get('/showdrivers', [DriverController::class, 'showDrivers'])->name('admin.showDrivers');
Route::get('/showdrivers/search',[DriverController::class,'search'])->name('DriverController.search');
Route::post('/driver/addfees/{driver}', [DriverController::class, 'addfees'])->name('addfees');
Route::patch('/activate/{driver}', [DriverController::class, 'activateDriver'])->name('activateDriver');
Route::post('/driver/store', [DriverController::class, 'driverStore'])->name('driverStore');


Route::get('/financial', [FinancialController::class,'show'])->name('admin.financial');
Route::get('/financial/search', [FinancialController::class,'search'])->name('FinancialController.search');
Route::post('/financial/modify/{financial}', [FinancialController::class, 'modify'])->name('modify');

Route::get('/orders',[OrderController::class,'show'])->name('admin.orders');
Route::get('orders/search',[OrderController::class,'search'])->name('OrderController.search');
Route::get('/requests',[OrderController::class,'showBills'])->name('admin.requests');
Route::get('/requests/search',[OrderController::class,'searchBills'])->name('OrderController.searchBills');
Route::patch('/requests/accept/{request}',[OrderController::class,'acceptBill'])->name('acceptOrder');
Route::post('/requests/refuseBill/{bill}',[OrderController::class,'refuseBill'])->name('refuseBill');



Route::get('/managers',[ManagersController::class,'show'])->name('admin.managers');
Route::get('/managers/search',[ManagersController::class,'search'])->name('ManagersController.search');
Route::post('/manager/edit/{manager}',[ManagersController::class,'edit'])->name('edit');
Route::post('/manager/delete/{id}',[ManagersController::class,'delete'])->name('delete');
Route::post('manager/create',[ManagersController::class,'create'])->name('create');

Route::get('/companies',[CompanyController::class,'show'])->name('admin.companies');
Route::get('companies/search',[CompanyController::class,'search'])->name('CompanyController.search');
Route::patch('companies/activate/{company}',[CompanyController::class,'activateCompany'])->name('ActivateCompany');
Route::post('/companies/delete/{id}',[CompanyController::class,'delete'])->name('deleteCompany');
Route::post('/companies/edit/{company}',[CompanyController::class,'edit'])->name('editCompany');
Route::post('/companies/create',[CompanyController::class,'create'])->name('createCompany');


Route::get('/banks',[BankController::class,'show'])->name('admin.banks');
Route::get('/banks/search',[BankController::class,'search'])->name('BankController.search');
Route::post('/banks/delete/{id}',[BankController::class,'delete'])->name('deleteBank');
Route::post('/banks/edit/{bank}',[BankController::class,'edit'])->name('editBank');
Route::post('/bank/create',[BankController::class,'create'])->name('createBank');


Route::get('/trucks',[TruckController::class,'show'])->name('admin.trucks');
Route::get('/trucks/search',[TruckController::class,'search'])->name('TruckController.search');
Route::post('/trucks/create',[TruckController::class,'create'])->name('createTruck');
Route::post('/trucks/edit/{truck}',[TruckController::class,'edit'])->name('editTruck');
Route::post('/trucks/delete/{id}',[TruckController::class,'delete'])->name('deleteTruck');
Route::patch('/trucks/activate/{truck}',[TruckController::class,'activate'])->name('activateTruck');

Route::get('/goods',[GoodsController::class,'show'])->name('admin.goods');
Route::get('/goods/search',[GoodsController::class,'search'])->name('GoodsController.search');
Route::post('/goods/create',[GoodsController::class,'create'])->name('createGood');
Route::post('/goods/edit/{good}',[GoodsController::class,'edit'])->name('editGood');

Route::get('/discounts', [DriverController::class, 'discounts'])->name('admin.discounts');
Route::get('/userscomplaints', [DriverController::class, 'usersComplaints'])->name('admin.users_complaints');
Route::get('/driverscomplaints', [DriverController::class, 'driversComplaints'])->name('admin.drivers_complaints');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::patch('/active/{discount}', [DriverController::class, 'activateDiscount'])->name('activateDiscount');
Route::delete('/delete/{discount}', [DriverController::class, 'deleteDiscount'])->name('discount.destroy');
Route::post('/discount/store', [DriverController::class, 'discountStore'])->name('discountStore');

// Auth::routes();

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::resource('users',App\Http\Controllers\DriverController::class);

//laravel will now that the name between {} is the name of the model/class
// Route::get('/post/{post}', [App\Http\Controllers\PostController::class, 'show'])->name('post');
// /** prevent users from entering the admin page if they are not authanticated, we have only one middle ware 'Auth' */
// Route::middleware('auth')->group(function(){

// //the name here, we could use hepler pakcage to help us replace the name of the route, with the name in name() func.
// Route::get('/admin', [App\Http\Controllers\DriverController::class, 'index'])->name('admin.index');


// Route::get('/admin/posts', [App\Http\Controllers\PostController::class, 'index'])->name('post.index');

// /**admins create posts */
// Route::get('/admin/posts/create', [App\Http\Controllers\PostController::class, 'create'])->name('post.create');

// Route::post('/admin/posts', [App\Http\Controllers\PostController::class, 'store'])->name('post.store');

// /** delete function */
// Route::delete('/admin/posts/{post}/destroy', [App\Http\Controllers\PostController::class, 'destroy'])->name('post.destroy');

// /**edit using get */
// Route::get('/admin/posts/{post}/edit', [App\Http\Controllers\PostController::class, 'edit'])-> middleware('can:view,post') ->name('post.edit');

// /**update */
// Route::patch('/admin/posts/{post}', [App\Http\Controllers\PostController::class, 'update'])->name('post.update');

// });
// /**another way to prevent from seeing the edit page */
// //Route::get('/admin/posts/{post}/edit', [App\Http\Controllers\PostController::class, 'edit'])-> middleware('can:view,post') ->name('post.edit');

// //Route::resource('posts','App\Http\Controllers\PostController');


