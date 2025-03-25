<div class="sidebar">
    <!-- Profile Section -->
    <div class="profile">
    <img src="{{ asset('assets/images/user-icon.jpg') }}" alt="Profile">
    <h5 class="left-card" style="float: right; cursor: pointer; color: red;">&times;</h5>
        <h5>{{ Auth::guard('web')->user()->name }}</h5>
        <span>Admin User</span>
    </div>

    <div class="menu">
        <a href="{{ route('dashboard') }}" class="active"><i class="fas fa-home"></i> Dashboard <i class="fas fa-angle-right" style="float: right; padding: 6px; font-size: 14px;"></i></a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown"><i class="fas fa-box"></i> Bank</i></a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('bank.lists') }}">Bank Lists</a></li>
                <li><a class="dropdown-item" href="{{ route('transfer.lists') }}">Transfer Lists</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                <i class="fas fa-money-bill"></i> Payment
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Cash Payment</a></li>
                <li><a class="dropdown-item" href="#">Bank Payment</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                <i class="fas fa-tags"></i> Ledger
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('create.ledger') }}">Create Ledger</a></li>
                <li><a class="dropdown-item" href="{{ route('ledger.list') }}">Ledger List</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                <i class="fas fa-receipt"></i> Voucher
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="#">Contra</a>
                </li>

                <li>
                    <a class="dropdown-item" href="#">Receipt</a>
                </li>

                <li>
                    <a class="dropdown-item" href="#">Sales</a>
                </li>

                <li>
                    <a class="dropdown-item" href="{{ route('purchases') }}">Purcahes</a>
                </li>

                <li>
                    <a class="dropdown-item" href="{{ route('journals') }}">Journal</a>
                </li>
            </ul>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                <i class="fas fa-chart-line"></i> Reports 
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Transaction</a></li>
                <li><a class="dropdown-item" href="#">Account Balance</a></li>
                <li><a class="dropdown-item" href="#">Income</a></li>
                <li><a class="dropdown-item" href="#">Expenses</a></li>
                <li><a class="dropdown-item" href="{{ route('trial.balance') }}">Trial Balance</a></li>
                <li><a class="dropdown-item" href="#">Profit and Loss</a></li>
                <li><a class="dropdown-item" href="#">Balance Sheet</a></li>
            </ul>
        </div>
        <!-- <a href="#"><i class="fas fa-comments"></i> SMS <i class="fas fa-angle-right" style="float: right; padding: 6px; font-size: 14px;"></i></a> -->
        <a href="#"><i class="fa fa-file-invoice"></i> Billing <i class="fas fa-angle-right" style="float: right; padding: 6px; font-size: 14px;"></i></a>
        <a href="{{ route('sales') }}"><i class="fas fa-shopping-cart"></i> Sales &amp; Stock Out <i class="fas fa-angle-right" style="float: right; padding: 6px; font-size: 14px;"></i></a>
        <a href="{{ route('storage.manage') }}"><i class="fas fa-shopping-bag"></i> Store Management <i class="fas fa-angle-right" style="float: right; padding: 6px; font-size: 14px;"></i></a>
        <a href="{{ route('users') }}"><i class="fa fa-users"></i> User Management <i class="fas fa-angle-right" style="float: right; padding: 6px; font-size: 14px;"></i></a>
        <a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout <i class="fas fa-angle-right" style="float: right; padding: 6px; font-size: 14px;"></i></a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
