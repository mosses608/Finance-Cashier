<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // stock management
        $parentId = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Inventory Management',
            'module_label' => 'inventory management',
            'module_path' => '#inventory',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-boxes"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Add New Stock',
            'module_label' => 'add new stock',
            'module_path' => 'stock-management',
            'module_parent_id' => $parentId,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Stock In',
            'module_label' => 'stock in',
            'module_path' => 'stockIn',
            'module_parent_id' => $parentId,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Stock Out',
            'module_label' => 'stock out',
            'module_path' => 'stock-out',
            'module_parent_id' => $parentId,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Stock Out Change',
            'module_label' => 'stock out change',
            'module_path' => 'stock-change-history',
            'module_parent_id' => $parentId,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Report',
            'module_label' => 'report',
            'module_path' => 'stock-out-report',
            'module_parent_id' => $parentId,
            'module_icon' => '',
        ]);

        // Invoice

        // $parentIdInvoice = DB::table('auth_user_modules')->insertGetId([
        //     'module_name' => 'Invoices',
        //     'module_label' => 'invoices',
        //     'module_path' => '#invoice',
        //     'module_parent_id' => null,
        //     'module_icon' => '<i class="fa-solid fa-file-invoice"></i>',
        // ]);

        $parentIdStore = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Storage Management',
            'module_label' => 'stores',
            'module_path' => '#',
            'module_parent_id' => null,
            'module_icon' => '<i class="fa-solid fa-store"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Add New Store',
            'module_label' => 'ass new store',
            'module_path' => 'add-store',
            'module_parent_id' => $parentIdStore,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Store List',
            'module_label' => 'store lists',
            'module_path' => 'store-lists',
            'module_parent_id' => $parentIdStore,
            'module_icon' => '',
        ]);

        $parentIdExpense = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Expenses',
            'module_label' => 'expenses',
            'module_path' => '#expenses',
            'module_parent_id' => null,
            'module_icon' => '<i class="fa-solid fa-calculator"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Record Expenses',
            'module_label' => 'record expenses',
            'module_path' => 'expenses',
            'module_parent_id' => $parentIdExpense,
            'module_icon' => '',
        ]);

        $parentIdAccount = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Account & Finance',
            'module_label' => 'accounts',
            'module_path' => '#accounts',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-credit-card"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Account Balance',
            'module_label' => 'account balance',
            'module_path' => 'account-balance',
            'module_parent_id' => $parentIdAccount,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Bank Statements',
            'module_label' => 'bank statmenets',
            'module_path' => 'bank-statements',
            'module_parent_id' => $parentIdAccount,
            'module_icon' => '',
        ]);

        $parentIdPayment = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Cash-Cheque Payments',
            'module_label' => 'cash-cheque payments',
            'module_path' => '#payments',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-cash-register"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Payment Requests',
            'module_label' => 'payment requests',
            'module_path' => 'payment-requests',
            'module_parent_id' => $parentIdPayment,
            'module_icon' => '',
        ]);

        $parentIdPayroll = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Payroll Management',
            'module_label' => 'payroll management',
            'module_path' => '#payroll',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-money-bill-wave"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Register Allowances',
            'module_label' => 'register allowances',
            'module_path' => 'register-allowance',
            'module_parent_id' => $parentIdPayroll,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Staff Budget Codes',
            'module_label' => 'staff budget codes',
            'module_path' => 'staff-budget-codes',
            'module_parent_id' => $parentIdPayroll,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Monthly Allowances',
            'module_label' => 'monthly allowances',
            'module_path' => 'monthly-allowance',
            'module_parent_id' => $parentIdPayroll,
            'module_icon' => '',
        ]);

        $parentIdSales = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Sales Management',
            'module_label' => 'sales management',
            'module_path' => '#sales',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-dollar-sign"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Create Profoma Invoice',
            'module_label' => 'create invoice',
            'module_path' => 'create-invoice',
            'module_parent_id' => $parentIdSales,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Invoice Lists',
            'module_label' => 'invoice list',
            'module_path' => 'invoice-list',
            'module_parent_id' => $parentIdSales,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Profoma Invoice Lists',
            'module_label' => 'profoma invoice list',
            'module_path' => 'profoma-invoice',
            'module_parent_id' => $parentIdSales,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Sevices Management',
            'module_label' => 'service management',
            'module_path' => 'services',
            'module_parent_id' => $parentIdSales,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Accept Profoma Invoice',
            'module_label' => 'accept profoma invoice',
            'module_path' => 'accept-profoma',
            'module_parent_id' => $parentIdSales,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Invoice Adjustments',
            'module_label' => 'invoice adjustments',
            'module_path' => 'invoice-adjustments',
            'module_parent_id' => $parentIdSales,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Sales List',
            'module_label' => 'sales list',
            'module_path' => 'sales-list',
            'module_parent_id' => $parentIdSales,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Sales Report',
            'module_label' => 'sales reports',
            'module_path' => 'sales-reports',
            'module_parent_id' => $parentIdSales,
            'module_icon' => '',
        ]);

        $purchaseOrder = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Purchase Orders',
            'module_label' => 'purchase orders',
            'module_path' => '#purchase-order',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-shopping-cart"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Issue Purchase Order',
            'module_label' => 'issue purchase order',
            'module_path' => 'issue-purchase-order',
            'module_parent_id' => $purchaseOrder,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Reports',
            'module_label' => 'reports',
            'module_path' => 'sales-reports',
            'module_parent_id' => $purchaseOrder,
            'module_icon' => '',
        ]);

        // Budget
        $parentIdBudget = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Budget Management',
            'module_label' => 'budget management',
            'module_path' => '#budget',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-money-bill"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'New Budget',
            'module_label' => 'new budget',
            'module_path' => 'budgets',
            'module_parent_id' => $parentIdBudget,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Budget Review',
            'module_label' => 'budget review',
            'module_path' => 'budget-review',
            'module_parent_id' => $parentIdBudget,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Budget Roll Out',
            'module_label' => 'budget roll out',
            'module_path' => 'budget-roll-out',
            'module_parent_id' => $parentIdBudget,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Budget Report',
            'module_label' => 'budget report',
            'module_path' => 'budget-reports',
            'module_parent_id' => $parentIdBudget,
            'module_icon' => '',
        ]);

        // stakeholders mgt
        $parentIdStakeholder = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Stakeholders Management',
            'module_label' => 'stakeholders management',
            'module_path' => '#users',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-users"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'New Stakeholder',
            'module_label' => 'new stakeholder',
            'module_path' => 'stakeholders',
            'module_parent_id' => $parentIdStakeholder,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'New Bank',
            'module_label' => 'new bank',
            'module_path' => 'new-bank',
            'module_parent_id' => $parentIdStakeholder,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Stakeholders Report',
            'module_label' => 'stakeholders report',
            'module_path' => 'stakeholder-report',
            'module_parent_id' => $parentIdStakeholder,
            'module_icon' => '',
        ]);

        // HR
        $parentIdHR = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Human Resources',
            'module_label' => 'human resources',
            'module_path' => '#human-resource',
            'module_parent_id' => null,
            'module_icon' => '<i class="fa-solid fa-user-tie"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Staff management',
            'module_label' => 'staff management',
            'module_path' => 'staff-management',
            'module_parent_id' => $parentIdHR,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Salary Advance',
            'module_label' => 'salar advance',
            'module_path' => 'salary-advance',
            'module_parent_id' => $parentIdHR,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Leave Application',
            'module_label' => 'leave application',
            'module_path' => 'leave-apply',
            'module_parent_id' => $parentIdHR,
            'module_icon' => '',
        ]);

        // Leave management

        $parentIdLeave = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'Leave Management',
            'module_label' => 'leave management',
            'module_path' => '#leave-mgt',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-umbrella-beach"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Leave Registration',
            'module_label' => 'leave registration',
            'module_path' => 'leave-registraion',
            'module_parent_id' => $parentIdLeave,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Apply For Leave',
            'module_label' => 'apply leave',
            'module_path' => 'apply-for-leave',
            'module_parent_id' => $parentIdLeave,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Leave Applications',
            'module_label' => 'leave application',
            'module_path' => 'view-leave-applications',
            'module_parent_id' => $parentIdLeave,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Leave Adjustments',
            'module_label' => 'leave adjustments',
            'module_path' => 'leave.adjustments',
            'module_parent_id' => $parentIdLeave,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Aprove Leave Adjustments',
            'module_label' => 'approve leave adjustments',
            'module_path' => 'adjustment-application-list',
            'module_parent_id' => $parentIdLeave,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Reports',
            'module_label' => 'leave reports',
            'module_path' => 'leave-report',
            'module_parent_id' => $parentIdLeave,
            'module_icon' => '',
        ]);

        // System Access
        $parentIdAccess = DB::table('auth_user_modules')->insertGetId([
            'module_name' => 'System Access',
            'module_label' => 'system access',
            'module_path' => '#system-users',
            'module_parent_id' => null,
            'module_icon' => '<i class="fas fa-cog"></i>',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'System Users',
            'module_label' => 'system users',
            'module_path' => 'system-users',
            'module_parent_id' => $parentIdAccess,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Passwords Reset',
            'module_label' => 'password resets',
            'module_path' => 'password-resets',
            'module_parent_id' => $parentIdAccess,
            'module_icon' => '',
        ]);

        DB::table('auth_user_modules')->insert([
            'module_name' => 'Reports',
            'module_label' => 'users report',
            'module_path' => 'users-reports',
            'module_parent_id' => $parentIdAccess,
            'module_icon' => '',
        ]);
    }
}
