<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductController;

// PAGES
Route::get('/', [App\Http\Controllers\Pages\PageController::class, 'index'])->name('home');
Route::get('/login', [App\Http\Controllers\Pages\PageController::class, 'welcome'])->name('login');
Route::get('/dashboard-minor', [App\Http\Controllers\Pages\PageController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/api-test-airtel-money', [App\Http\Controllers\MobilePayments\PaymentGateWayController::class, 'airtelMoney']);

Route::post('/airtel-money', [App\Http\Controllers\MobilePayments\PaymentGateWayController::class, 'transactionAirtelMoney'])->name('airtel-transaction');

Route::get('/get-started', [App\Http\Controllers\Pages\PageController::class, 'getStarted'])->name('get.started');
Route::post('/sign-up', [App\Http\Controllers\Pages\PageController::class, 'signUp'])->name('signup.account');

Route::get('/forgot-password', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'forgotPass'])->name('forgot.password');
Route::post('/reset-password', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'resetPassword'])->name('rest.password');
Route::get('/reset', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'resetMail'])->name('reset.mail');
Route::post('/finalise', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'finaliseReset'])->name('finalise.reset');
Route::get('/logout', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'logout'])->name('logout');
Route::post('/authenticate', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'authentication'])->name('auth0n');

Route::post('/subscribe', [App\Http\Controllers\Pages\PageController::class, 'subscribe'])->name('subscribe.user');

Route::get('/availabe-features', [App\Http\Controllers\Pages\PageController::class, 'availableFeatures'])->name('available.features');

Route::middleware('auth')->group(function () {

    // ADMIN
    Route::get('/admin', [App\Http\Controllers\Admin\AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/view-accounts', [App\Http\Controllers\Admin\AdminController::class, 'viewAccounts'])->name('view-accounts');
    Route::get('/user-accounts', [App\Http\Controllers\Admin\AdminController::class, 'userAccounts'])->name('user.account');
    Route::post('/suspend-account', [App\Http\Controllers\Admin\AdminController::class, 'suspendAccount'])->name('suspend.account');
    Route::post('/activate-account', [App\Http\Controllers\Admin\AdminController::class, 'activateAccount'])->name('activate.account');
    Route::get('/logs', [App\Http\Controllers\Admin\AdminController::class, 'logs'])->name('user.logs');


    // USERS
    Route::get('/users', [App\Http\Controllers\Users\UsersController::class, 'users'])->name('users')->middleware('auth');
    Route::post('/store', [App\Http\Controllers\Users\UsersController::class, 'storeUsers'])->name('store.users')->middleware('auth');
    Route::put('/update', [App\Http\Controllers\Users\UsersController::class, 'updateUser'])->name('update.users')->middleware('auth');
    Route::delete('/delete', [App\Http\Controllers\Users\UsersController::class, 'deleteUser'])->name('delete.users')->middleware('auth');
    Route::get('/system-users', [App\Http\Controllers\Users\UsersController::class, 'systemUsers'])->name('system.users');
    Route::post('/add-system-users', [App\Http\Controllers\Users\UsersController::class, 'addSystemUsers'])->name('new.susyem.user');
    Route::get('/password-resets', [App\Http\Controllers\Users\UsersController::class, 'passwordRest'])->name('password.resets');
    Route::put('/reset-password', [App\Http\Controllers\Users\UsersController::class, 'resetPasswords'])->name('reset.password');
    Route::get('/users-reports', [App\Http\Controllers\Users\UsersController::class, 'usersReports'])->name('users.system.reports');
    Route::get('/modules', [App\Http\Controllers\Pages\PageController::class, 'modules'])->name('modules');
    Route::post('/module-check', [App\Http\Controllers\Pages\PageController::class, 'moduleSelect'])->name('module.check');

    // STORAGE
    Route::get('/stock-management', [App\Http\Controllers\Storage\StorageController::class, 'storeManage'])->name('storage.manage')->middleware('auth');
    Route::post('/register', [App\Http\Controllers\Storage\StorageController::class, 'register'])->name('comp.store')->middleware('auth');
    Route::get('/add-store', [App\Http\Controllers\Storage\StorageController::class, 'storePage'])->name('add.store');
    Route::get('/store-lists', [App\Http\Controllers\Storage\StorageController::class, 'storeLists'])->name('store.list');
    Route::get('/view-store/{encryptedStoreId}', [App\Http\Controllers\Storage\StorageController::class, 'viewStore'])->name('store.view');
    Route::post('/store-change-logs', [App\Http\Controllers\Storage\StorageController::class, 'storeChangeLogs'])->name('store.change.logs');

    // new routes to be seeded
    Route::get('/stock-change-history', [App\Http\Controllers\Storage\StorageController::class, 'stockChange'])->name('stock.change');
    Route::get('/stock-out-report', [App\Http\Controllers\Storage\StorageController::class, 'stockOutReport'])->name('stock.out.report');
    Route::post('/approve-reject-stock-chnage', [App\Http\Controllers\Storage\StorageController::class, 'approveRejectStockChange'])->name('approve.reject.stock.change');
    Route::get('/download-stockout-report/{validData}', [App\Http\Controllers\Storage\StorageController::class, 'downloadStockOutReport'])->name('download.stock.out.report');
    // new routes to be seeded

    // PRODUCT
    Route::post('/approve-reject-transactions', [App\Http\Controllers\Stock\StockController::class, 'approveRejectTransactions'])->name('approve.reject.transactions');
    Route::post('/storeProduct', [ProductController::class, 'storeProduct'])->name('store.products');
    Route::get('/product/{product}', [App\Http\Controllers\Products\ProductController::class, 'singleProduct'])->name('single.product')->middleware('auth');
    Route::post('/stock', [App\Http\Controllers\Stock\StockController::class, 'stock'])->name('stock.store')->middleware('auth');
    Route::get('/stockIn', [App\Http\Controllers\Stock\StockController::class, 'stockInMethod'])->name('stock.in');
    Route::get('/download-excel-file', [App\Http\Controllers\Products\ProductController::class, 'downloadExcelFile'])->name('download.file');
    Route::post('/import-product', [App\Http\Controllers\Products\ProductController::class, 'importProductFile'])->name('products.import');
    Route::get('/stock-out', [App\Http\Controllers\Stock\StockController::class, 'stockOut'])->name('stock.out');
    Route::delete('/destroy', [App\Http\Controllers\Products\ProductController::class, 'destroyProduct'])->name('products.destroy');
    Route::put('/stockIn-quantity', [App\Http\Controllers\Stock\StockController::class, 'stockInQuantity'])->name('stockIn.Quantity');
    Route::post('/stockOut', [App\Http\Controllers\Stock\StockController::class, 'stockOutProduct'])->name('stock.out.product');
    Route::get('/stock-out-receipt/{encryptedId}', [App\Http\Controllers\Stock\StockController::class, 'stockOutReceipt'])->name('stock.out.receipt');
    Route::get('/download-stock-import-file', [App\Http\Controllers\Stock\StockController::class, 'downloadStockImportFile'])->name('download.csv.file');
    Route::post('/upload-file', [App\Http\Controllers\Stock\StockController::class, 'uploadCSVFile'])->name('upload.csv');

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
    Route::get('/invoice-adjustments', [App\Http\Controllers\Invoice\InvoiceController::class, 'invoiceAdjustments'])->name('invoice.adjustments');
    Route::post('/adjust-invoice', [App\Http\Controllers\Invoice\InvoiceController::class, 'invoiceAdjustSave'])->name('adjust.invoice');


    // TRANSACTION || SALES
    Route::get('/sales', [App\Http\Controllers\TransactionController::class, 'transactions'])->name('sales')->middleware('auth');
    Route::post('/storeTransaction', [App\Http\Controllers\TransactionController::class, 'storeTransaction'])->name('store.sales')->middleware('auth');
    Route::get('/issue-purchase-order', [App\Http\Controllers\Sales\SalesController::class, 'createSales'])->name('create.new.sales');
    Route::get('/sales-list', [App\Http\Controllers\Sales\SalesController::class, 'salesList'])->name('sales.list');
    Route::post('/store-sales', [App\Http\Controllers\Sales\SalesController::class, 'storeSales'])->name('store.sales');
    Route::get('/sales-receipt/{encryptedSaleId}', [App\Http\Controllers\Sales\SalesController::class, 'viewReceipt'])->name('sale.receipt');
    Route::get('/download-receipt/{encryptedReceiptId}', [App\Http\Controllers\Sales\SalesController::class, 'downloadReceipt'])->name('download.receipt');
    Route::get('/sales-reports', [App\Http\Controllers\Sales\SalesController::class, 'salesReports'])->name('sales.reports');
    // Route::get('/purchase-order-delivery', [App\Http\Controllers\Sales\SalesController::class, 'purchaseOrderDelivery'])->name('purchase.oder.delivery');
    Route::get('/download-report/{validData}', [App\Http\Controllers\Sales\SalesController::class, 'downloadReport'])->name('download.report');

    // SERVICES
    Route::get('/services', [App\Http\Controllers\Services\ServiceController::class, 'servicePage'])->name('service.page');
    Route::post('/store-services', [App\Http\Controllers\Services\ServiceController::class, 'storeServices'])->name('store.service');
    Route::post('/service-profoma', [App\Http\Controllers\Services\ServiceController::class, 'serviceProfomaInvoice'])->name('service.profoma.invoice');
    Route::get('/accept-profoma', [App\Http\Controllers\Services\ServiceController::class, 'acceptProfoma'])->name('accept.profoma');
    Route::put('/accept-profoma-invoice', [App\Http\Controllers\Services\ServiceController::class, 'approveProfomaInvoice'])->name('accept.profoma.invoice');
    Route::put('/accept-profoma-outstore', [App\Http\Controllers\Services\ServiceController::class, 'acceptProfomaOutStore'])->name('accept.profoma.outstore.invoice');

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
    Route::get('/logout', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'logout'])->name('logout')->middleware('auth');
    Route::get('/stakeholders', [App\Http\Controllers\Pages\PageController::class, 'usersManager'])->name('users.management');
    Route::post('/customer-groupd', [App\Http\Controllers\Pages\PageController::class, 'customerGroupd'])->name('customer.group');
    Route::post('/stakeholder-create', [App\Http\Controllers\Pages\PageController::class, 'storeStakeholder'])->name('stakeholder.create');
    Route::get('/new-bank', [App\Http\Controllers\Pages\PageController::class, 'newBank'])->name('new.bank');
    Route::post('/create-bank', [App\Http\Controllers\Pages\PageController::class, 'createBank'])->name('create.banks');
    Route::post('/register-branch', [App\Http\Controllers\Pages\PageController::class, 'registerBranch'])->name('bank.branch');
    Route::get('/stakeholder-report', [App\Http\Controllers\Pages\PageController::class, 'stakeholdReports'])->name('stakeholder.reports');
    Route::get('/staff-management', [App\Http\Controllers\Pages\PageController::class, 'staffManagement'])->name('staff.management');
    Route::post('/store-staff', [App\Http\Controllers\Pages\PageController::class, 'storeStaff'])->name('staff.store');


    // HR MANAGEMENT
    Route::get('/salary-advance', [App\Http\Controllers\HR\HumanResourceController::class, 'salaryAdvance'])->name('salary.advance');
    Route::post('/apply-salary-advance', [App\Http\Controllers\HR\HumanResourceController::class, 'applySalaryAdvance'])->name('apply.salary.advance');
    Route::get('/leave-registraion', [App\Http\Controllers\HR\HumanResourceController::class, 'leaveRegister'])->name('register.leave.type');
    Route::post('/store-leaves', [App\Http\Controllers\HR\HumanResourceController::class, 'storeLeaveTypes'])->name('store.leave.types');
    Route::get('/apply-for-leave', [App\Http\Controllers\HR\HumanResourceController::class, 'applyLeave'])->name('apply.leave');
    Route::post('/leave-application', [App\Http\Controllers\HR\HumanResourceController::class, 'leaveApplications'])->name('store.leave.applications');
    Route::post('/staff-leave-apply', [App\Http\Controllers\HR\HumanResourceController::class, 'staffLeaveApply'])->name('staff.leave.applications');
    Route::get('/view-leave-applications', [App\Http\Controllers\HR\HumanResourceController::class, 'viewApplications'])->name('leave.applications');
    Route::put('/approve-leave-application', [App\Http\Controllers\HR\HumanResourceController::class, 'approveApplication'])->name('approve.leave.request');
    Route::get('/leave-adjustments', [App\Http\Controllers\HR\HumanResourceController::class, 'leaveAdjustments'])->name('leave.adjustments');
    Route::put('/apply-for-adjustments', [App\Http\Controllers\HR\HumanResourceController::class, 'applyForAdjustments'])->name('apply.leave.adjust');
    Route::get('/adjustment-application-list', [App\Http\Controllers\HR\HumanResourceController::class, 'viewAdjustmentLists'])->name('approve.leave.adjustments');
    Route::put('/update-adjustment', [App\Http\Controllers\HR\HumanResourceController::class, 'approveAdjustmentApplication'])->name('approve.leave.adjustment.application');
    Route::get('/leave-report', [App\Http\Controllers\HR\HumanResourceController::class, 'leaveReports'])->name('staff.leave.reports');
    Route::get('/leave-apply', [App\Http\Controllers\HR\HumanResourceController::class, 'hrLeaveApply'])->name('leave.application.pg');


    // BUDGET MANAGER
    Route::get('/budgets', [App\Http\Controllers\Budget\BudgetController::class, 'budgetCreate'])->name('new.budget');
    Route::post('/project-store', [App\Http\Controllers\Budget\BudgetController::class, 'storeProject'])->name('store.project');
    Route::post('/budget-create', [App\Http\Controllers\Budget\BudgetController::class, 'budgetStore'])->name('budget.create');
    Route::get('/view-budget/{encryptedId}', [App\Http\Controllers\Budget\BudgetController::class, 'viewBudget'])->name('view.budget');
    Route::get('/budget-review', [App\Http\Controllers\Budget\BudgetController::class, 'budgetReview'])->name('budget.review');
    Route::put('/budget-approval', [App\Http\Controllers\Budget\BudgetController::class, 'budgetApproval'])->name('approve.budget');
    Route::get('/budget-roll-out', [App\Http\Controllers\Budget\BudgetController::class, 'budgetRollOut'])->name('budget.roll.out');
    Route::post('/add-sub-budgets', [App\Http\Controllers\Budget\BudgetController::class, 'appendSubBudgets'])->name('add.sub.codes');
    Route::put('/remove-sub-budget', [App\Http\Controllers\Budget\BudgetController::class, 'removeSubBudget'])->name('remove.sub.budget');
    Route::post('/budget-roll-out', [App\Http\Controllers\Budget\BudgetController::class, 'budgetRollOutRecreate'])->name('budget.roll.out');
    Route::get('/budget-reports', [App\Http\Controllers\Budget\BudgetController::class, 'budgetReports'])->name('budget.reports');
    Route::get('/staff-budget-codes', [App\Http\Controllers\Budget\BudgetController::class, 'staffBudgetCodes'])->name('assign.budget.code');
    Route::post('/staff-budget-codes', [App\Http\Controllers\Budget\BudgetController::class, 'staffSubBudgetCodes'])->name('staff.budget.codes');
    Route::get('/budget-file-download', [App\Http\Controllers\Budget\BudgetController::class, 'downloadBudgetFileSample'])->name('download.csv.budget');
    Route::post('/bulk-budget-create', [App\Http\Controllers\Budget\BudgetController::class, 'bulkBudgetCreate'])->name('bulk.budget.create');

    Route::get('/register-allowance', [App\Http\Controllers\Budget\BudgetController::class, 'registerAllowances'])->name('register.alowances');
    Route::post('/allowance', [App\Http\Controllers\Budget\BudgetController::class, 'storeAllowance'])->name('store.allowance');
    Route::put('/update-laoowance', [App\Http\Controllers\Budget\BudgetController::class, 'updateAllowance'])->name('update.allowance');
    Route::get('/monthly-allowance', [App\Http\Controllers\Budget\BudgetController::class, 'monthlyAllowance'])->name('monthly.allowance');

    // ACCOUNT
    Route::get('/account-balance', [App\Http\Controllers\Accounts\AccountController::class, 'accountBalance'])->name('account.balance');
    Route::post('/bank-balance-store', [App\Http\Controllers\Accounts\AccountController::class, 'accountBalanceStore'])->name('bank.balance');
    Route::get('/bank-statements', [App\Http\Controllers\Accounts\AccountController::class, 'bankStatements'])->name('bank.statements');
    Route::get('/download-bank-statement', [App\Http\Controllers\Accounts\AccountController::class, 'downloadBankStatement'])->name('download.bank.statement');


    // EXPENSES
    Route::get('/expenses', [App\Http\Controllers\Expenses\ExpensesController::class, 'expenses'])->name('record.expenses');
    Route::post('/store-expenses', [App\Http\Controllers\Expenses\ExpensesController::class, 'storeExpenses'])->name('expense.store');
    Route::get('/payment-requests', [App\Http\Controllers\Expenses\ExpensesController::class, 'paymentRequests'])->name('payment.requests');
    Route::post('/approve-payments', [App\Http\Controllers\Accounts\AccountController::class, 'approvePayments'])->name('approve.payment.request');


    Route::get('/dashboard', [App\Http\Controllers\Pages\PageController::class, 'dashboardFx'])->name('home')->middleware('auth');
    Route::get('/upload-logo', [App\Http\Controllers\Pages\PageController::class, 'uploadLogo'])->name('upload.logo');
    Route::post('/logo-upload', [App\Http\Controllers\Pages\PageController::class, 'saveLogoUpload'])->name('upload.logo.image');

    // WEBSITE BUILDER
    Route::get('/website/{encryptedId}', [App\Http\Controllers\Website\WebsiteBuilderController::class, 'websiteBuilder'])->name('view.website.builder');
});
