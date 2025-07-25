@if (Auth::check())
    @if (true)
        @extends('layouts.mainLayout')

        @section('content')

            <div class="transparent" onclick="hideAll(event)"></div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            @include('partials.sideNav')

            <x-messages />

            <div class="shortcut-report">
                <div class="md-4">
                    <h3>{{ __('Shortcut Report') }}</h3>
                    <a href="#">View Report <i class="fa-solid fa-arrow-right"></i></a>
                    <br><br>
                    <div class="col-3" style="background-color: rgb(229, 243, 255);">
                        <i class="fas fa-coins" style="color: #007BFF;"></i>
                        <h2><strong>Tsh 5,599,024,589</strong></h2>
                        <span>Opening Balance</span>
                        <br><br>
                        <div class="rate" style="color: #007BFF;"><strong>+51%</strong></div>
                    </div>
                    <div class="col-3" style="background-color: rgb(229, 243, 255);">
                        <i class="fas fa-money-check-alt" style="color: gold;"></i>
                        <h2><strong>Tsh {{ number_format($totalAmount, 2) }}</strong></h2>
                        <span>Today's Transaction</span>
                        <br><br>
                        <div class="rate" style="color: gold;"><strong>+21%</strong></div>
                    </div>
                    <div class="col-3" style="background-color: rgb(229, 243, 255);">
                        <i class="fas fa-arrow-down" style="color: maroon;"></i> <i class="fas fa-dollar-sign"
                            style="color: gold;"></i>
                        <h2><strong>Tsh 3,100,589</strong></h2>
                        <span>Today's Expenses</span>
                        <br><br>
                        <div class="rate" style="color: red;"><strong>-11%</strong></div>
                    </div>
                    <div class="col-3" style="background-color: rgb(229, 243, 255);">
                        <i class="fas fa-money-bill-trend-up" style="color: green;"></i>
                        <h2><strong>Tsh 30,047,589</strong></h2>
                        <span>Today's Net Income</span>
                        <br><br>
                        <div class="rate" style="color: #008800;"><strong>+45%</strong></div>
                    </div>
                </div>
                <div class="md-5">
                    <h3>{{ __('Recent Clients') }}</h3>
                    <span id="list-counter">
                        Latest 4
                    </span>
                    <br><br>
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lastFiveTransactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->customer_name }}</td>
                                    <td>{{ number_format($transaction->selling_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="md-4 mt-4">
                    <h3>{{ __('Cashflow') }}</h3>
                    <canvas id="transactionChart"></canvas>
                    <!-- <img src="{{ asset('assets/images/cashFlow.png') }}" alt="Image"> -->
                </div>

                <script>
                    const transactions = @json($transactions);

                    const labels = transactions.map(t => new Date(t.created_at).toLocaleDateString());
                    const dataValues = transactions.map(t => t.stockout_quantity);

                    const ctx = document.getElementById('transactionChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Stock Out Quantity',
                                data: dataValues,
                                borderColor: 'blue',
                                backgroundColor: 'rgba(188, 218, 245, 0.5)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Date'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Quantity'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true
                                }
                            }
                        }
                    });
                </script>

                <div class="md-5 mt-4">
                    <h3>{{ __('Pie Chart') }}</h3>
                    <canvas id="cashFlowPie"></canvas>
                    <!-- <img src="{{ asset('assets/images/cashFlowPie.png') }}" alt="Image"> -->
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const transactions = @json($transactions);

                        // Extract data
                        const labels = transactions.map(txn => txn.created_at); // Dates as labels
                        const sellingPrices = transactions.map(txn => txn.selling_price); // Selling prices
                        const stockOutQuantities = transactions.map(txn => txn.stockout_quantity); // Stock-out quantities

                        const ctx = document.getElementById('cashFlowPie').getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                        label: 'Selling Price',
                                        data: sellingPrices,
                                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                        borderColor: '#fff',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Stock-out Quantity',
                                        data: stockOutQuantities,
                                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                                        borderColor: '#fff',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'center'
                                    }
                                }
                            }
                        });
                    });
                </script>

                <div class="md-6 mt-4" style="width: 100%;">
                    <h3>{{ __('Recent Voucher') }}</h3>
                    <span id="list-counter">
                        List 5
                    </span>
                    <table class="table table-bordered table-striped">
                        <thead class="table-white">
                            <tr>
                                <th>S/N</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Voucher No</th>
                                <th>Due Date</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        </tabe>
                </div>
            </div>
        @stop
    @endif
@else
    <span>Login to access this resource!</span>
@endif

<style>
    body {
        background-color: #f4f7fe;
    }

    .sidebar {
        background-color: #ffffff;
        height: 100vh;
        padding-top: 1rem;
        position: fixed;
        width: 250px;
        border-right: 1px solid #ddd;
    }

    .sidebar .nav-link {
        color: #333;
    }

    .content {
        margin-left: 260px;
        padding: 1rem;
    }

    .card-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

{{-- JUST FOR TESTING --}}
<div class="d-flex">
    <div class="sidebar">
        <div class="text-center mb-4">
            <h5 class="fw-bold">Materially</h5>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="bi bi-house"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <span class="fw-bold text-uppercase text-muted ps-3">Pages</span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="bi bi-file-earmark-text"></i> Sample Page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="bi bi-shield-lock"></i> Authentication</a>
                <ul class="ms-4">
                    <li><a class="nav-link" href="#">→ Login</a></li>
                    <li><a class="nav-link" href="#">→ Register</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <span class="fw-bold text-uppercase text-muted ps-3">Utils</span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="bi bi-grid"></i> Icons</a>
            </li>
        </ul>
    </div>

    <div class="content w-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Dashboard </h4>
            <div class="input-group w-25">
                <input type="text" class="form-control" placeholder="Search...">
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <div class="card-summary">
                            <div>
                                <h5>$30200</h5>
                                <small>All Earnings</small>
                            </div>
                            <i class="bi bi-currency-dollar fs-2"></i>
                        </div>
                        <small>10% changes on profit</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger h-100">
                    <div class="card-body">
                        <div class="card-summary">
                            <div>
                                <h5>145</h5>
                                <small>Task</small>
                            </div>
                            <i class="bi bi-calendar-event fs-2"></i>
                        </div>
                        <small>28% task performance</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <div class="card-summary">
                            <div>
                                <h5>290+</h5>
                                <small>Page Views</small>
                            </div>
                            <i class="bi bi-file-earmark fs-2"></i>
                        </div>
                        <small>10k daily views</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <div class="card-summary">
                            <div>
                                <h5>500</h5>
                                <small>Downloads</small>
                            </div>
                            <i class="bi bi-hand-thumbs-up fs-2"></i>
                        </div>
                        <small>1k download in App store</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>Sales Per Day</h6>
                        <div class="text-center my-4">
                            <img src="https://via.placeholder.com/200x100?text=Graph" alt="Graph"
                                class="img-fluid">
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>$4230</strong>
                            <span>321 Today Sales</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <img src="https://via.placeholder.com/150x150?text=Chart" alt="Chart" class="img-fluid">
                        <div class="mt-2">
                            <span class="badge bg-danger">Youtube</span>
                            <span class="badge bg-primary">Facebook</span>
                            <span class="badge bg-info text-dark">Twitter</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6>Traffic Sources</h6>
                        <div class="mb-2">Direct <div class="progress">
                                <div class="progress-bar" style="width: 80%"></div>
                            </div>
                        </div>
                        <div class="mb-2">Social <div class="progress">
                                <div class="progress-bar bg-secondary" style="width: 50%"></div>
                            </div>
                        </div>
                        <div class="mb-2">Referral <div class="progress">
                                <div class="progress-bar bg-success" style="width: 20%"></div>
                            </div>
                        </div>
                        <div class="mb-2">Bounce <div class="progress">
                                <div class="progress-bar bg-danger" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
