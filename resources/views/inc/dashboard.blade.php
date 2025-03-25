@if(Auth::check())

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
            <i class="fas fa-coins"  style="color: #007BFF;"></i>
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
            <i class="fas fa-arrow-down"  style="color: maroon;"></i> <i class="fas fa-dollar-sign"  style="color: gold;"></i>
            <h2><strong>Tsh 3,100,589</strong></h2>
            <span>Today's Expenses</span>
            <br><br>
            <div class="rate" style="color: red;"><strong>-11%</strong></div>
        </div>
        <div class="col-3" style="background-color: rgb(229, 243, 255);">
            <i class="fas fa-money-bill-trend-up"  style="color: green;"></i>
            <h2><strong>Tsh 30,047,589</strong></h2>
            <span>Today's Net Income</span>
            <br><br>
            <div class="rate" style="color: #008800;"><strong>+45%</strong></div>
        </div>
    </div>
    <div class="md-5">
        <h3>{{__('Recent Clients')}}</h3>
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
               @foreach($lastFiveTransactions as $transaction)
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
                    x: { title: { display: true, text: 'Date' }},
                    y: { title: { display: true, text: 'Quantity' }}
                },
                plugins: {
                    legend: { display: true }
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
       document.addEventListener("DOMContentLoaded", function () {
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
                    datasets: [
                        {
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

@else

<span>Login to access this resource!</span>

@endif