
@extends('layouts.mainLayout')

@section('content')
<div class="transparent" onclick="hideAll(event)"></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('partials.sideNav')

@if($product)

<x-messages />

<div class="shortcut-report">
    <div class="md-4">
        <h3>{{ $product->item_name }}</h3>
        @if($product->item_pic)
        <a href="{{ asset('storage/' . $product->item_pic) }}" target="_blank" id="stock-inn"> <i class="fa fa-eye"></i> Image</a>
        @else
        <a href="#" id="stock-inn"> <i class="fa fa-eye"></i> Image</a>
        @endif
        <a href="#" id="stock-in" onclick="stockIn(event)"> <i class="fa fa-plus"></i> Stock In</a>
        <br><br>
        <div class="scrollable" style="width: 100%; overflow-x: scroll;">
            <table class="table table-bordered table-striped">
                <thead class="table-white">
                    <tr>
                        <th>Stock Code</th>
                        <th>Specs</th>
                        <th>Item Unit</th>
                        <th>Category</th>
                        <th>Prefix</th>
                        <th>Status</th>
                        <th>Store</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @php
                    $stock = $stocks->firstWhere('storage_item_id', $product->id);
                @endphp
                <tr>
                    <td>
                        {{ $product->item_prefix }}-0{{ $product->id }}
                    </td>
                    <td>{{ $product->item_specs }}</td>
                    <td>{{ $product->item_quantity_unit }}</td>
                    <td>{{ $product->item_category }}</td>
                    <td>{{ $product->item_prefix }}</td>
                    @php
                    $stock = $stocks->firstWhere('storage_item_id', $product->id)
                    @endphp
                    <td class="status-td">
                    @php
                        $sumStockOut = 0;
                        
                        $stock = $stocks->firstWhere('storage_item_id', $product->id);

                        if ($stock) {
                            $transactionsForProduct = $transactions->where('product_item_id', $stock->storage_item_id);

                            foreach ($transactionsForProduct as $trans) {
                                $sumStockOut += $trans->stockout_quantity;
                            }
                        } else {
                            $transactionsForProduct = null;
                        }
                    @endphp
                            @if($stock)
                                @if(($stock->quantity_in - $sumStockOut) == 0)
                                    <span style="color: red; font-size: 10px;"><strong>OutStock</strong></span>
                                @elseif(($stock->quantity_in - $sumStockOut) >= 1 && $stock->quantity_in <= 10)
                                    <span style="color: maroon;"><strong>LessStock</strong></span>
                                @elseif(($stock->quantity_in - $sumStockOut) > 10)
                                    <span style="color: #008800;"><strong>InStock</strong></span>
                                @endif
                            @else
                                <span style="color: red;"><strong>OutStock</strong></span>
                            @endif
                    </td>
                    @php
                    $store = $stores->firstWhere('id', $product->store_id);
                    @endphp
                    <td>
                        {{ $store->store_name }}
                    </td>
                    <td class="action-btn">
                        <button class="edit-usr" style="color: #008800;"><i class="fa fa-pencil"></i></button>
                        <button class="delete-usr" style="color: red;"><i class="fa fa-trash"></i></button>    
                    </td>
                </tr>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.stock-import')

    <div class="md-5">
        <h3>{{__('Storage')}}</h3>
        <br><br>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Quantity In</th>
                    <th>Quantity Out</th>
                    <th>Quantity Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                <tr>
                @php
                            $sumStockOut = 0;
                            
                            $stock = $stocks->firstWhere('storage_item_id', $product->id);

                            if ($stock) {
                                $transactionsForProduct = $transactions->where('product_item_id', $stock->storage_item_id);

                                foreach ($transactionsForProduct as $trans) {
                                    $sumStockOut += $trans->stockout_quantity;
                                }
                            } else {
                                $transactionsForProduct = null;
                            }
                        @endphp
                    <td>
                        {{ $stock->quantity_in ?? 0 }}
                    </td>
                    <td>
                        {{ $sumStockOut }}
                    </td>
                    <td>
                        {{ $stock->quantity_in - $sumStockOut }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="md-4 mt-4">
        <h3>{{ __('Sales | Line Graph') }}</h3>
        <canvas id="transactionChart"></canvas>
        <!-- <img src="{{ asset('assets/images/cashFlow.png') }}" alt="Image"> -->
    </div>

    <div class="md-5 mt-4">
        <h3>{{ __('Sales | Pie Chart') }}</h3>
        <!-- <canvas id="cashFlowPie"></canvas> -->
        <img src="{{ asset('assets/images/cashFlowPie.png') }}" alt="Image">
    </div>

</div>
@else
<center>
<span style="padding: 10px;">Product does not exist!</span>
</center>
@endif

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

@stop