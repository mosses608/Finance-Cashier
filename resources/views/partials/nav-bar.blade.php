<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
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
                    <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboards</p>
                        <span class="caret"></span>
                    </a>
                    @if (true)
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
                            {{-- <li>
                                <a href="{{ route('invoice.list') }}">
                                    <span class="sub-item">Sevice Invoice Lists</span>
                                </a>
                            </li> --}}
                            {{-- <li>
                                <a href="{{ route('profoma.invoice') }}">
                                    <span class="sub-item">Profoma Invoice Lists</span>
                                </a>
                            </li> --}}
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
                                <a href="#">
                                    <span class="sub-item">Sales Receipt</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Sales Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="#">
                        <i class="fa-solid fa-money-bill"></i>
                        <p>Billings</p>
                        <span class="badge badge-success">4</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('users.management') }}">
                        <i class="fas fa-users"></i>
                        <p>Users Management</p>
                        <span class="badge badge-secondary">20</span>
                    </a>
                </li> --}}

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
                                <a href="#">
                                    <span class="sub-item">New Bank</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Stakeholders Report</span>
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
