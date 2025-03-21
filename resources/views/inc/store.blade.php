@extends('layouts.mainLayout')

@section('content')
<div class="transparent" onclick="hideAll(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
        <h3>{{ __('Product List') }}</h3>
        <button type="button" class="btn btn-secondary" onclick="regyStoreForm(event)"><i class="fa fa-plus"></i> Store</button>
        <button type="button" class="btn btn-secondary" onclick="RegProdForm(event)"><i class="fa fa-plus"></i> Product</button>
<br>
        <form action="{{ route('storage.manage') }}" method="GET" class="form-data">
            @csrf
            <div class="meta-data">
                <span><i class="fa fa-search"></i></span>
                <input
                 type="text" 
                 name="search" 
                 id=""
                 placeholder="Search Products"
                >
            </div>
            <div class="export-print">
                <button type="button"> <a href="file.xlsx" class="btn btn-outline-success" download>
                    <i class="fa fa-file-excel"></i>
                </a>
                </button>
                <button type="button"> <a href="file.pdf" class="btn btn-outline-success">
                    <i class="fa fa-file-pdf" style="color: orange;"></i>
                </a>
                </button>
                <button type="button"> <a href="#"  class="btn btn-outline-success">
                    <i class="fa fa-print" style="color: #007BFF;"></i>
                </a>
                </button>
            </div>
            <br><br>
        </form>

        @include('partials.product')

        @include('partials.store')

        <div class="scrollable" style="width: 100%; overflow-x: scroll;">
            <table class="table table-bordered table-striped">
                <thead class="table-white">
                    <tr>
                        <th>S/N</th>
                        <th>Code</th>
                        <th>Item</th>
                        <th>Specs</th>
                        <th>Unit</th>
                        <th>Category</th>
                        <th>Prefix</th>
                        <th>Status</th>
                        <th>Store</th>
                        <th>Qty (in)</th>
                        <th>Qty (out)</th>
                        <th>Qty (total)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    @php
                        $stock = $stocks->firstWhere('storage_item_id', $product->id);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->item_prefix }}-0{{ $product->id }}</td>
                        <td>{{ $product->item_name }}</td>
                        <td>{{ $product->item_specs }}</td>
                        <td>{{ $product->item_quantity_unit }}</td>
                        <td>{{ $product->item_category }}</td>
                        <td>{{ $product->item_prefix }}</td>
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
                                @elseif(($stock->quantity_in - $sumStockOut) >= 1 && $stock->quantity_in < 10)
                                    <span style="color: maroon;"><strong>LessStock</strong></span>
                                @elseif(($stock->quantity_in - $sumStockOut) >= 10)
                                    <span style="color: #008800;"><strong>InStock</strong></span>
                                @endif
                            @else
                                <span style="color: red;"><strong>OutStock </strong></span>
                            @endif
                        </td>
                        <td>
                            {{ optional($stores->firstWhere('id', $product->store_id))->store_name ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $stock->quantity_in ?? 0 }}
                        </td>
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
                            {{ $sumStockOut ?? 0 }}
                        </td>
                        <td>
                            {{ ($stock->quantity_in ?? 0) - ($sumStockOut ?? 0) }}
                        </td>
                        <td class="action-btn">
                            <button class="view-usr" style="color: #007BFF;">
                                <a href="{{ route('single.product', $product->id ) }}"><i class="fa fa-eye"></i></a>
                            </button> 
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>

@stop