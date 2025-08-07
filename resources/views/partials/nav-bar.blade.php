<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('home') }}" class="logo">
                <img width="180" src="{{ asset('assets/images/akilisoft-logo-image.png') }}" alt="navbar brand" class="navbar-brand"
                    height="70" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item active">
                    <a href="{{ route('home') }}" class="collapsed">
                        <i class="fas fa-home"></i>
                        <p>Dashboards</p>
                        <span class="caret"></span>
                    </a>
                    @if (false)
                        <div class="collapse" id="dashboard">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="#">
                                        <span class="sub-item">HR Dashboard</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    {{--
                    <h4 class="text-section">Components</h4>
                    --}}
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#stock">
                        <i class="fas fa-boxes"></i>
                        <p>Stock Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="stock">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('storage.manage') }}">
                                    <span class="sub-item">Add New Stock</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stock.in') }}">
                                    <span class="sub-item">Stock In</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stock.out') }}">
                                    <span class="sub-item">Stock Out</span>
                                </a>
                            </li>

                            @if (false)
                                <li>
                                    <a href="components/gridsystem.html">
                                        <span class="sub-item">Grid System</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="components/panels.html">
                                        <span class="sub-item">Panels</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="components/notifications.html">
                                        <span class="sub-item">Notifications</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="components/sweetalert.html">
                                        <span class="sub-item">Sweet Alert</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="components/font-awesome-icons.html">
                                        <span class="sub-item">Font Awesome Icons</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="components/simple-line-icons.html">
                                        <span class="sub-item">Simple Line Icons</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="components/typography.html">
                                        <span class="sub-item">Typography</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#invoice">
                        <i class="fa-solid fa-file-invoice"></i>
                        <p>Invoices</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="invoice">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('create.invoice') }}">
                                    <span class="sub-item">Create Invoice</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('invoice.list') }}">
                                    <span class="sub-item">Invoice Lists</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profoma.invoice') }}">
                                    <span class="sub-item">Profoma Invoice Lists</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('accept.profoma') }}">
                                    <span class="sub-item">Accept Profoma Invoice</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('invoice.adjustments') }}">
                                    <span class="sub-item">Invoice Adjustments</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#stores">
                        <i class="fa-solid fa-store"></i>
                        <p>Stores</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="stores">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('add.store') }}">
                                    <span class="sub-item">Add New Store</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('store.list') }}">
                                    <span class="sub-item">Store List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#service">
                        <i class="fas fa-concierge-bell"></i>
                        <p>Services</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="service">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('service.page') }}">
                                    <span class="sub-item">Create Sevices</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#expenses">
                        <i class="fa-solid fa-calculator"></i>
                        <p>Expenses</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="expenses">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('record.expenses') }}">
                                    <span class="sub-item">Record Expenses</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#accounts">
                        <i class="fas fa-credit-card"></i>
                        <p>Accounts</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="accounts">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('account.balance') }}">
                                    <span class="sub-item">Account Balance</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('bank.statements') }}">
                                    <span class="sub-item">Bank Statements</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#payments">
                        <i class="fas fa-cash-register"></i>
                        <p>Cash-Cheque Payments</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="payments">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('payment.requests') }}">
                                    <span class="sub-item">Payment Requests</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#payroll">
                        <i class="fas fa-money-bill-wave"></i>
                        <p>Payroll Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="payroll">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('register.alowances') }}">
                                    <span class="sub-item">Register Allowances</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('assign.budget.code') }}">
                                    <span class="sub-item">Staff Budget Codes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('monthly.allowance') }}">
                                    <span class="sub-item">Monthly Allowances</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Staff Absence</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Staff Over-Times</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Process Payroll</span> 
                                    {{-- process & approve payroll --}}
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Payroll Payment</span>
                                    {{-- prepare , approve & list of paid payroll --}}
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Posted Payroll</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Reversed Payroll</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Reports</span>
                                    {{-- payroll report, PAYE Return, WCF Return, Payslips & Staff Absence --}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sales">
                        <i class="fas fa-dollar-sign"></i>
                        <p>Sales Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="sales">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('create.new.sales') }}">
                                    <span class="sub-item">New Sales</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sales.list') }}">
                                    <span class="sub-item">Sales List</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sales.reports') }}">
                                    <span class="sub-item">Sales Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#budget">
                        <i class="fas fa-money-bill"></i>
                        <p>Budget Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="budget">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('new.budget') }}">
                                    <span class="sub-item">New Budget</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('budget.review') }}">
                                    <span class="sub-item">Budget Review</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('budget.roll.out') }}">
                                    <span class="sub-item">Budget Roll Out</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('budget.reports') }}">
                                    <span class="sub-item">Budget Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#users">
                        <i class="fas fa-users"></i>
                        <p>Stakeholders Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="users">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('users.management') }}">
                                    <span class="sub-item">New Stakeholder</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('new.bank') }}">
                                    <span class="sub-item">New Bank</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stakeholder.reports') }}">
                                    <span class="sub-item">Stakeholders Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#human-resource">
                        <i class="fa-solid fa-user-tie"></i>
                        <p>Human Resources</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="human-resource">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('staff.management') }}">
                                    <span class="sub-item">Staff management</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('salary.advance') }}">
                                    <span class="sub-item">Salary Advance</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('leave.application.pg') }}">
                                    <span class="sub-item">Leave Application</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stakeholder.reports') }}">
                                    <span class="sub-item">Payroll Commitment</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stakeholder.reports') }}">
                                    <span class="sub-item">Create Allowances</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stakeholder.reports') }}">
                                    <span class="sub-item">Reports</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#leave-mgt">
                        <i class="fas fa-umbrella-beach"></i>
                        <p>Leave Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="leave-mgt">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('register.leave.type') }}">
                                    <span class="sub-item">Leave Registration</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('apply.leave') }}">
                                    <span class="sub-item">Apply For Leave</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('leave.applications') }}">
                                    <span class="sub-item">Leave Applications</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('leave.adjustments') }}">
                                    <span class="sub-item">Leave Adjustments</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('approve.leave.adjustments') }}">
                                    <span class="sub-item">Aprove Leave Adjustments</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('staff.leave.reports') }}">
                                    <span class="sub-item">Reports</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                 <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#system-users">
                        <i class="fas fa-cog"></i>
                        <p>System Access</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="system-users">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('system.users') }}">
                                    <span class="sub-item">System Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('apply.leave') }}">
                                    <span class="sub-item">User Branch</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('password.resets') }}">
                                    <span class="sub-item">Passwords Reset</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('users.system.reports') }}">
                                    <span class="sub-item">Reports</span>
                                    {{-- users reports, System logs --}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#messages">
                        <i class="fas fa-comments"></i>
                        <p>Messages</p>
                        <span class="badge badge-primary">10</span>
                        {{-- <span class="caret"></span> --}}
                    </a>
                    <div class="collapse" id="messages">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Send Message</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Message List</span>
                                    <span class="badge badge-primary">10</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                @if (false)
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#messages">
                            <i class="fas fa-comments"></i>
                            <p>Messages</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="messages">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a data-bs-toggle="collapse" href="#subnav1">
                                        <span class="sub-item">Level 1</span>
                                        <span class="caret"></span>
                                    </a>
                                    <div class="collapse" id="subnav1">
                                        <ul class="nav nav-collapse subnav">
                                            <li>
                                                <a href="#">
                                                    <span class="sub-item">Level 2</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <span class="sub-item">Level 2</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <a data-bs-toggle="collapse" href="#subnav2">
                                        <span class="sub-item">Level 1</span>
                                        <span class="caret"></span>
                                    </a>
                                    <div class="collapse" id="subnav2">
                                        <ul class="nav nav-collapse subnav">
                                            <li>
                                                <a href="#">
                                                    <span class="sub-item">Level 2</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="sub-item">Level 1</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
