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
                                    class="text-success">{{ number_format($itemsCounter) }} Items</span></h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatablesxzy" class="display table table-striped table-hover">
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
                                        @foreach ($storageData as $data)
                                            @php
                                                $stock = $productStocks->firstWhere(
                                                    'storage_item_id',
                                                    $data->productAutoId,
                                                );
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->productName }}</td>
                                                <td>{{ $data->sku }}</td>
                                                <td>{{ number_format($data->costPrice, 2) }}</td>
                                                <td>{{ number_format($data->sellingPrice, 2) }}</td>
                                                <td>{{ number_format($stock->quantity_total ?? 0) }}</td>
                                                <td>{{ $data->description }}</td>
                                                <td class="text-start text-nowrap">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateQuantityModal-{{ $data->productAutoId }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <div class="modal fade"
                                                            id="updateQuantityModal-{{ $data->productAutoId }}"
                                                            tabindex="-1" aria-labelledby="updateQuantityModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form action="{{ route('store.change.logs') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="updateQuantityModalLabel">
                                                                                Update Stock Details: <strong
                                                                                    id="modal_product_name"
                                                                                    class="text-success">{{ $data->productName }}</strong><sup
                                                                                    class="text-primary text-lowercase"><strong>{{ number_format($stock->quantity_total ?? 0) . ' ' . $data->sku }}</strong></sup>
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="product_id"
                                                                                id="modal_product_id"
                                                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt(json_encode($data->productAutoId)) }}">
                                                                            <div class="row mb-3">
                                                                                <div class="col-6 mb-3">
                                                                                    <label for=""
                                                                                        class="form-label">Selling
                                                                                        Price</label>
                                                                                    <input type="text"  
                                                                                        class="form-control"
                                                                                        name="selling_price"
                                                                                        value="{{ number_format($data->sellingPrice, 2) }}"
                                                                                        required>
                                                                                </div>
                                                                                <div class="col-6 mb-3">
                                                                                    <label for=""
                                                                                        class="form-label">Change
                                                                                        Store</label>
                                                                                    <select class="form-control"
                                                                                        name="store_id">
                                                                                        <option
                                                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt(json_encode($storeId, true)) }}">
                                                                                            {{ $storeName }}
                                                                                        </option>
                                                                                        @foreach ($storeData as $store)
                                                                                            <option
                                                                                                value="{{ $store->id }}">
                                                                                                {{ $store->store_name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Update
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <button class="btn btn-danger btn-sm"><i
                                                                class="fa fa-trash"></i></button>
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
        new DataTable("#basic-datatablesxzy");
    </script>
@stop
