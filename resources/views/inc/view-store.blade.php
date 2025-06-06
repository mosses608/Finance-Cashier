@extends('layouts.part')

@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <x-messages />
                        <div class="card-header">
                            <h4 class="card-title">{{ $storeName }} | <span
                                    style="color:#007bff;">{{ number_format($itemsCounter) }} Items</span></h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Item Name</th>
                                            <th>Units (SKU)</th>
                                            <th>Cost Price</th>
                                            <th>Selling Price</th>
                                            <th>Store Qty</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($storageData as $data)
                                        @php
                                        $stock = $productStocks->firstWhere('storage_item_id', $data->productAutoId);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->productName }}</td>
                                            <td>{{ $data->sku }}</td>
                                            <td>{{ number_format($data->costPrice, 2) }}</td>
                                            <td>{{ number_format($data->sellingPrice, 2) }}</td>
                                            <td>{{ number_format($stock->quantity_total ?? 0) }}</td>
                                            <td>{{ $data->description }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
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
@stop
