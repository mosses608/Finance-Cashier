<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductController;

// PAGES
Route::get('/', [App\Http\Controllers\Pages\PageController::class, 'welcome'])->name('login');
Route::get('/dashboard', [App\Http\Controllers\Pages\PageController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// USERS
Route::get('/users', [App\Http\Controllers\Users\UsersController::class, 'users'])->name('users')->middleware('auth');
Route::post('/store', [App\Http\Controllers\Users\UsersController::class, 'storeUsers'])->name('store.users')->middleware('auth');
Route::put('/update', [App\Http\Controllers\Users\UsersController::class, 'updateUser'])->name('update.users')->middleware('auth');
Route::delete('/delete', [App\Http\Controllers\Users\UsersController::class, 'deleteUser'])->name('delete.users')->middleware('auth');

// STORAGE
Route::get('/store', [App\Http\Controllers\Storage\StorageController::class, 'storeManage'])->name('storage.manage')->middleware('auth');
Route::post('/register', [App\Http\Controllers\Storage\StorageController::class, 'register'])->name('comp.store')->middleware('auth');

// PRODUCT
Route::post('/storeProduct', [ProductController::class, 'storeProduct'])->name('store.products')->middleware('auth');
Route::get('/product/{product}', [App\Http\Controllers\Products\ProductController::class, 'singleProduct'])->name('single.product')->middleware('auth');
Route::post('/stock', [App\Http\Controllers\Stock\StockController::class, 'stock'])->name('stock.store')->middleware('auth');

Route::get('/sales', [App\Http\Controllers\TransactionController::class, 'transactions'])->name('sales')->middleware('auth');
Route::post('/storeTransaction', [App\Http\Controllers\TransactionController::class, 'storeTransaction'])->name('store.sales')->middleware('auth');

// LEDGER
Route::get('/ledger', [App\Http\Controllers\Ledger\LedgerController::class, 'ledgers'])->name('create.ledger')->middleware('auth');
Route::post('/ledgers', [App\Http\Controllers\Ledger\LedgerController::class, 'storeLedger'])->name('store.ledgers')->middleware('auth');
Route::post('/ledger-group', [App\Http\Controllers\Ledger\LedgerController::class, 'ledgerGroup'])->name('ledgers.group')->middleware('auth');
Route::get('/ledger-list', [App\Http\Controllers\Ledger\LedgerController::class, 'ledgerList'])->name('ledger.list')->middleware('auth');

// BANK
Route::get('/banks', [App\Http\Controllers\Bank\BankController::class, 'bank'])->name('bank.lists')->middleware('auth');
Route::post('/store-bank', [App\Http\Controllers\Bank\BankController::class, 'storeBank'])->name('store.bank')->middleware('auth');

Route::get('/transfer', [App\Http\Controllers\Bank\BankController::class, 'transfers'])->name('transfer.lists')->middleware('auth');
Route::post('/create-transfer', [App\Http\Controllers\Bank\BankController::class, 'createTransfer'])->name('store.transfer')->middleware('auth');

// VOUCHER
Route::get('/journals', [App\Http\Controllers\Vouchers\VoucherController::class, 'journals'])->name('journals')->middleware('auth');
Route::post('/storeJournal', [App\Http\Controllers\Vouchers\VoucherController::class, 'storeJournal'])->name('store.journals');
Route::get('/purchases', [App\Http\Controllers\Vouchers\VoucherController::class, 'purchases'])->name('purchases')->middleware('auth');

// REPORTS
Route::get('/trial-balance', [App\Http\Controllers\Reports\ReportController::class, 'trial_balance'])->name('trial.balance')->middleware('auth');

// USERS
Route::post('/authenticate', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'authentication'])->name('authenticate.user');
Route::get('/logout', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'logout'])->name('logout')->middleware('auth');