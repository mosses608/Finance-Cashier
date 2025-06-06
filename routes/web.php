<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductController;
// use Illuminate\Support\Facades\App;

// use Illuminate\Support\Facades\App;

// PAGES
Route::get('/', [App\Http\Controllers\Pages\PageController::class, 'welcome'])->name('login');
Route::get('/dashboard', [App\Http\Controllers\Pages\PageController::class, 'dashboard'])->name('dashboard')->middleware('auth');

Route::get('/api-test-airtel-money', [App\Http\Controllers\MobilePayments\PaymentGateWayController::class, 'airtelMoney']);

Route::post('/airtel-money', [App\Http\Controllers\MobilePayments\PaymentGateWayController::class, 'transactionAirtelMoney'])->name('airtel-transaction');

Route::middleware('guest')->group(function(){
    Route::get('/forgot-password', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'forgotPass'])->name('forgot.password');
    Route::post('/reset-password', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'resetPassword'])->name('rest.password');
    Route::get('/reset', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'resetMail'])->name('reset.mail');
    Route::post('/finalise', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'finaliseReset'])->name('finalise.reset');
});

// USERS
Route::get('/users', [App\Http\Controllers\Users\UsersController::class, 'users'])->name('users')->middleware('auth');
Route::post('/store', [App\Http\Controllers\Users\UsersController::class, 'storeUsers'])->name('store.users')->middleware('auth');
Route::put('/update', [App\Http\Controllers\Users\UsersController::class, 'updateUser'])->name('update.users')->middleware('auth');
Route::delete('/delete', [App\Http\Controllers\Users\UsersController::class, 'deleteUser'])->name('delete.users')->middleware('auth');

// STORAGE
Route::get('/store', [App\Http\Controllers\Storage\StorageController::class, 'storeManage'])->name('storage.manage')->middleware('auth');
Route::post('/register', [App\Http\Controllers\Storage\StorageController::class, 'register'])->name('comp.store')->middleware('auth');
Route::get('/add-store', [App\Http\Controllers\Storage\StorageController::class, 'storePage'])->name('add.store');
Route::get('/store-lists', [App\Http\Controllers\Storage\StorageController::class, 'storeLists'])->name('store.list');
Route::get('/view-store/{encryptedStoreId}', [App\Http\Controllers\Storage\StorageController::class, 'viewStore'])->name('store.view');


// PRODUCT
Route::post('/storeProduct', [ProductController::class, 'storeProduct'])->name('store.products');
Route::get('/product/{product}', [App\Http\Controllers\Products\ProductController::class, 'singleProduct'])->name('single.product')->middleware('auth');
Route::post('/stock', [App\Http\Controllers\Stock\StockController::class, 'stock'])->name('stock.store')->middleware('auth');
Route::get('/stockIn', [App\Http\Controllers\Stock\StockController::class, 'stockInMethod'])->name('stock.in');
Route::get('/download-excel-file', [App\Http\Controllers\Products\ProductController::class, 'downloadExcelFile'])->name('download.file');
Route::post('/import-product', [App\Http\Controllers\Products\ProductController::class, 'importProductFile'])->name('products.import');
Route::get('/stock-out', [App\Http\Controllers\Stock\StockController::class, 'stockOut'])->name('stock.out');
Route::delete('/destroy', [App\Http\Controllers\Products\ProductController::class, 'destroyProduct'])->name('products.destroy');
Route::put('/stockIn-quantity', [App\Http\Controllers\Stock\StockController::class, 'stockInQuantity'])->name('stockIn.Quantity');

// INVOICES
Route::post('/create-invoice', [App\Http\Controllers\Invoice\InvoiceController::class, 'storeInvoice'])->name('create.invoice');
Route::get('/invoice-list', [App\Http\Controllers\Invoice\InvoiceController::class, 'invoiceList'])->name('invoice.list');
Route::get('/invoice-view/{encryptedInvoiceId}', [App\Http\Controllers\Invoice\InvoiceController::class, 'viewInvoice'])->name('invoice.view');
Route::get('/profoma-invoice', [App\Http\Controllers\Invoice\InvoiceController::class, 'profomaInvoice'])->name('profoma.invoice');
Route::put('/cancel-invoicel', [App\Http\Controllers\Invoice\InvoiceController::class, 'cancelInvoice'])->name('cancell.invoice');
Route::post('/create-profoma-invoice', [App\Http\Controllers\Invoice\InvoiceController::class, 'createProfomaInvoice'])->name('create.profoma.invoice');
Route::get('/view-profoma/{encryptedInvoiceId}', [App\Http\Controllers\Invoice\InvoiceController::class, 'viewProfoma'])->name('profoma.invoice.view');
Route::put('/cancel-profoma', [App\Http\Controllers\Invoice\InvoiceController::class, 'cancelProfoma'])->name('profoma.cancell.invoice');
Route::get('/download-profoma/{encryptedPrpfomaId}', [App\Http\Controllers\Invoice\InvoiceController::class, 'downloadProfoma'])->name('download.profoma');
Route::post('/profoma-out-store', [App\Http\Controllers\Invoice\InvoiceController::class, 'profomaOutStore'])->name('out.store.profoma');
Route::get('/profoma-outStore-view/{encryptedInvoiceuto}', [App\Http\Controllers\Invoice\InvoiceController::class, 'viewProfomaOutStore'])->name('profoma.invoice.out.store');
Route::get('/download-profoma1/{encryptedPrpfomaAutoId}', [App\Http\Controllers\Invoice\InvoiceController::class, 'downloadInvoiceProfoma'])->name('download.profoma.out');
Route::get('/invoice-download/{encryptedAutoId}', [App\Http\Controllers\Invoice\InvoiceController::class, 'invoiceDownload'])->name('invoice.download');
Route::get('/create-invoice', [App\Http\Controllers\Invoice\InvoiceController::class, 'createInvoice'])->name('create.invoice');

// TRANSACTION || SALES
Route::get('/sales', [App\Http\Controllers\TransactionController::class, 'transactions'])->name('sales')->middleware('auth');
Route::post('/storeTransaction', [App\Http\Controllers\TransactionController::class, 'storeTransaction'])->name('store.sales')->middleware('auth');
Route::get('/create-sales', [App\Http\Controllers\Sales\SalesController::class, 'createSales'])->name('create.new.sales');
Route::post('/store-sales', [App\Http\Controllers\Sales\SalesController::class, 'storeSales'])->name('store.sales');
Route::get('/sales-receipt/{encryptedSaleId}', [App\Http\Controllers\Sales\SalesController::class, 'viewReceipt'])->name('sale.receipt');
Route::get('/download-receipt/{encryptedReceiptId}', [App\Http\Controllers\Sales\SalesController::class, 'downloadReceipt'])->name('download.receipt');

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
Route::post('/authenticate', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'authentication'])->name('auth0n');
Route::get('/logout', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/test-page', [App\Http\Controllers\Pages\PageController::class, 'testPage']);