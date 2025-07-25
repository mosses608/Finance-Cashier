@extends('layouts.part')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('partials.nav-bar')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard | <span class="text-primary">{{ $companyData->companyName }} </span></h3>
                    <h6 class="op-7 mb-2">{{ \Carbon\Carbon::today()->format('M d, Y') }}</h6>
                </div>
                @if (false)
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                        <a href="#" class="btn btn-primary btn-round">Add Customer</a>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Stakeholders</p>
                                        <h4 class="card-title">{{ number_format($totalCustomers) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-money-bill-wave-alt"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Expenses</p>
                                        <h4 class="card-title">{{ number_format($expensesCounter) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Income</p>
                                        <h4 class="card-title">{{ $newIncome }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="fas fa-hand-holding-dollar"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Loans</p>
                                        <h4 class="card-title">{{ number_format(0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">
                                    Sales & Expenses Statistics
                                    <span class="text-primary">({{ $startOfWeek . ' ' . ' - ' . ' ' . $endOfWeek }})</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 400px">
                                <canvas id="statisticsChart" height="400" width="600"></canvas>
                            </div>
                            <div id="myChartLegend"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Daily Sales</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-label-light dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">All Sales</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-category">{{ \Carbon\Carbon::today()->format('M d, Y') }}</div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="mb-4 mt-2">
                                <h1>TZS {{ $todaySales }}</h1>
                            </div>
                            <div class="pull-in">
                                <canvas id="dailySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card card-round">
                        <div class="card-body pb-0">
                            <div class="h1 fw-bold float-end text-primary">
                                {{ number_format(($onlineUsers / $authUsers) * 100) }}%</div>
                            <h2 class="mb-2">{{ number_format($onlineUsers) }}</h2>
                            <p class="text-muted text-primary">Users online</p>
                            <div class="pull-in sparkline-fix">
                                <div id="lineChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-body">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">Customers</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">View All Customers</a>
                                            {{-- <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-list py-2">

                                @foreach ($customers as $customer)
                                    <div class="item-list">
                                        <div class="avatar">
                                            <span
                                                class="avatar-title rounded-circle border border-white">{{ strtoupper(substr($customer->name, 0, 2)) }}</span>
                                        </div>
                                        <div class="info-user ms-3">
                                            <div class="username">{{ $customer->name }}</div>
                                            <div class="status">{{ $customer->address }}</div>
                                        </div>
                                        <a href="mailto:{{ $customer->email }}" target="__blank"
                                            class="btn btn-icon btn-link op-8 me-1">
                                            <i class="far fa-envelope"></i>
                                            </button>
                                            <a href="tel:{{ $customer->phone }}" target="__blank"
                                                class="btn btn-icon btn-link btn-primary op-8">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">Transactions</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">View All Transactions</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <!-- Projects table -->
                                <table class="table align-items-center mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap">Customer Number</th>
                                            <th scope="col" class="text-end text-nowrap">Date & Time</th>
                                            <th scope="col" class="text-end text-nowrap">Amount (TZS)</th>
                                            <th scope="col" class="text-end text-nowrap">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesTransactions as $transaction)
                                            <tr>
                                                <th scope="row">
                                                    <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    From # {{ str_pad($transaction->customerId, 4, '0', STR_PAD_LEFT) }}
                                                </th>
                                                <td class="text-end">
                                                    {{ \Carbon\Carbon::parse($transaction->createdDate)->format('M d, Y H:i A') }}
                                                </td>
                                                <td class="text-end text-primary fw-600">
                                                    {{ number_format($transaction->amount, 2) }}</td>
                                                <td class="text-end">
                                                    <span class="badge badge-primary"><i class="fas fa-circle-check"></i>
                                                        Paid</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('statisticsChart').getContext('2d');
        const weeklyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                        label: 'Sales',
                        data: {!! json_encode($salesData) !!},
                        fill: true,
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        borderColor: '#007bff',
                        tension: 0.4
                    },
                    {
                        label: 'Expenses',
                        data: {!! json_encode($expensesData) !!},
                        fill: true,
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        borderColor: '#dc3545',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (TSH)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Week Days'
                        }
                    }
                }
            }
        });
    </script>
@endsection
