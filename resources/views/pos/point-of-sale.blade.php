@extends('layouts.part')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('partials.nav-bar')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row mt-3">
                <x-messages />
            </div>
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">POS Dashboard | <span class="text-primary">{{ $companyData->companyName }}
                        </span>
                    </h3>
                    <h6 class="op-7 mb-2">{{ \Carbon\Carbon::today()->format('M d, Y') }}</h6>
                </div>
                @if (true)
                    @php
                        $encryptedId = \Illuminate\Support\Facades\Crypt::encrypt($companyData->companyId);
                    @endphp
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ route('storage.manage') }}" target="__blank" class="btn btn-primary btn-round"><i
                                class="fa fa-plus"></i> Add Products</a>
                        {{-- <a href="{{ route('view.website.builder', $encryptedId) }}" target="__blank"
                            class="btn btn-primary btn-round"><i class="fa fa-eye"></i> View Website</a> --}}
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
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Sales</p>
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
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Cost</p>
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
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Product Sold</p>
                                        <h4 class="card-title">{{ $productsSold }}</h4>
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
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Orders</p>
                                        <h4 class="card-title">{{ number_format($ordersCounter) }}</h4>
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
                                    Sales & Orders Statistics
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

                    {{-- <div class="card card-round">
                        <div class="card-body pb-0">
                            <div class="h1 fw-bold float-end text-primary">
                                {{ number_format(($onlineUsers / $authUsers) * 100) }}%</div>
                            <h2 class="mb-2">{{ number_format($onlineUsers) }}</h2>
                            <p class="text-muted text-primary">Users online</p>
                            <div class="pull-in sparkline-fix">
                                <div id="lineChart"></div>
                            </div>
                        </div>
                    </div> --}}

                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-body">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title text-primary">Top Sold Items</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">View All Items</a>
                                            {{-- <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-list py-2">

                                @foreach ($topSoldProducts as $top)
                                    <div class="item-list">
                                        <div class="card p-2 shadow-sm" style="border-radius: 12px;">
                                            <img src="{{ asset('storage/' . $top->item_pic) }}" class="card-img-top"
                                                alt="{{ $top->name }}"
                                                style="height: 100px; width: 100px; object-fit: cover; border-radius: 10px;">
                                        </div>
                                        <div class="info-user ms-3">
                                            <div class="username col-12">
                                                <span class="float-end text-muted">{{ $top->name }}</span>
                                            </div><br>
                                            <div class="status float-end"><i class="fa fa-shopping-cart"></i>
                                                <sup><strong
                                                        class="text-primary">{{ number_format($top->total_orders) }}</strong></sup>
                                            </div>
                                        </div>
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
                                <div class="card-title text-success">Top Products</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-clean me-0" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{ route('pos.sales') }}">View All
                                                products</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <div class="row p-2">
                                    @foreach ($topProducts as $product)
                                        <div class="col-md-4">
                                            <div class="card p-2 shadow-sm" style="border-radius: 12px;">
                                                <img src="{{ asset('storage/' . $product->picture) }}"
                                                    class="card-img-top" alt="{{ $product->productName }}"
                                                    style="height: 100px; object-fit: cover; border-radius: 10px;">

                                                <div class="card-body text-start">
                                                    <h6 class="card-title mb-1 text-primary fs-6 text-capitalize d-flex">
                                                        {{ $product->productName }}</h6>
                                                    <p class="text-muted mb-1">
                                                        <strong>
                                                            {{ number_format($product->sellingPrice, 2) }} tsh</strong>
                                                    </p>
                                                    <p class="text-muted mb-0 btn btn-warning btn-sm border text-white">
                                                        {{ $product->availableQuantity }} items</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
