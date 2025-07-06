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
                            <h4 class="card-title">Stock List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatablesx0x" class="display table table-striped table-hover">
                                    <thead>
                                        <tr class="text-nowrap">
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Qty In</th>
                                            <th>Qty Out</th>
                                            <th>Available Qty</th>
                                            <th>Cost Price</th>
                                            <th>Selling Price</th>
                                            <th>Store</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            @php
                                                $quantityStocks = $stocks->firstWhere('storage_item_id', $product->id);
                                                $stockOutQuantity = $stockOutTransactions->where('product_id', $product->id)->sum('stockout_quantity');
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->productName }}</td>
                                                <td>{{ $product->sku }}</td>
                                                <td>
                                                    {{ number_format($quantityStocks->quantity_total ?? 0) }}
                                                </td>
                                                <td>{{ number_format($stockOutQuantity) }}</td>
                                                <td>{{ number_format($quantityStocks->quantity_total - $stockOutQuantity) }}</td>
                                                <td>{{ number_format($product->cost_price, 2) }}</td>
                                                <td>{{ number_format($product->selling_price, 2) }}</td>
                                                <td>{{ $product->storeName }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <button type="button" class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#updateQuantityModal"
                                                            data-product-id="{{ $product->id }}"
                                                            data-product-name="{{ $product->productName }}"
                                                            data-quantity="{{ $quantityStocks->quantity_total ?? 0 }}"
                                                            data-selling-price="{{ $product->selling_price }}">
                                                            <i class="fas fa-arrow-circle-down"></i>
                                                        </button>

                                                        <form action="{{ route('products.destroy') }}" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this product?');"
                                                            class="m-0 p-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" value="{{ $product->id }}"
                                                                name="product_id" />
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
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

    <!-- Single Global Modal -->
    <div class="modal fade" id="updateQuantityModal" tabindex="-1" aria-labelledby="updateQuantityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('stockIn.Quantity') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateQuantityModalLabel">
                            Stocking In: <strong id="modal_product_name"></strong>
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="auto_id" id="modal_product_id">
                        <div class="row mb-3">
                            <div class="col-6 mb-3">
                                <input type="number" class="form-control" name="quantity" id="modal_product_quantity_1"
                                    placeholder="Stock In Quantity" required>
                            </div>
                            <div class="col-6 mb-3">
                                <input type="number" class="form-control" name="seling_price" id="modal_selling_price"
                                    placeholder="Selling Price" readonly>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="number" class="form-control" name="available_quantity"
                                    id="modal_quantity_available" placeholder="Availlable Quantity" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        new DataTable('#basic-datatablesx0x');
    </script>
@stop

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateQuantityModal = document.getElementById('updateQuantityModal');

        updateQuantityModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const sellingPrice = button.getAttribute('data-selling-price');
            const availableQuantity = button.getAttribute('data-quantity');

            document.getElementById('modal_product_id').value = productId;
            document.getElementById('modal_product_name').textContent = productName;

            document.getElementById('modal_product_quantity_1').value = '';

            document.getElementById('modal_selling_price').value = sellingPrice;
            document.getElementById('modal_quantity_available').value = availableQuantity;
        });
    });
</script>
