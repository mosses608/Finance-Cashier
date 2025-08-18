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
                            <h4 class="card-title">Add Products</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('store.products') }}" method="POST" class="row"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row px-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="select">Product Type</label>
                                            <select class="form-control" name="product_type" id="selectOption">
                                                <option value="">--select product type--</option>
                                                <option value="is_goods">Goods</option>
                                                <option value="is_service">Service</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row px-3" id="service-row" style="display: none;">
                                    <div class="col-md-3">
                                        <label class="input-label p-2"><strong>Service Name</strong></label>
                                        <input type="text" class="form-control order-product-select" name="service_name"
                                            placeholder="service name">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="input-label p-2"><strong>Service Price</strong><sup
                                                class="text-warning f-6">(optional)</sup></label>
                                        <input type="text" name="amount_service" class="form-control order-selling-price"
                                            placeholder="service price">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="input-label p-2"><strong>Quantity</strong><sup
                                                class="text-warning f-6">(optional)</sup></label>
                                        <input type="text" name="quantity_service"
                                            class="form-control order-selling-price" placeholder="quantity">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="input-label p-2"><strong>Category</strong></label>
                                        <select type="number" name="category_service" class="form-control">
                                            <option value="" selected disabled>--select--</option>
                                            <option value="Commercial">Commercial</option>
                                            <option value="Residential">Residential</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="input-label p-2"><strong>Description</strong></label>
                                        <textarea name="description_service" class="form-control" placeholder="Description"></textarea>
                                    </div>
                                </div>

                                <div class="row" id="row-goods" style="display: none;">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="email2">Stock Name</label>
                                            <input type="text" class="form-control" id="email2" name="name"
                                                value="{{ old('name') }}" placeholder="Stock or Product Name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="serial_no">Serial No</label>
                                            <input type="text" class="form-control" id="serial_no" name="serial_no"
                                                value="{{ old('serial_no') }}" placeholder="Serial number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="email2">Units (SKU)</label>
                                            <input type="text" class="form-control" id="sku" name="sku"
                                                value="{{ old('sku') }}"
                                                placeholder="Stock Keeping Unit eg. pc, cm, kg" />
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="email2">Cost Price</label>
                                            <input type="number" class="form-control" id="email2" name="cost_price"
                                                value="{{ old('cost_price') }}" placeholder="Stock Cost Price" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="email2">Selling Price</label>
                                            <input type="number" class="form-control" id="email2"
                                                name="selling_price" value="{{ old('selling_price') }}"
                                                placeholder="Selling Price" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Cost Center</label>
                                            <select class="form-control select2" name="store_id"
                                                id="exampleFormControlSelect1" style="width: 100%;">
                                                <option value="">--select center--</option>
                                                @foreach ($stores as $store)
                                                    <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 w-100">
                                        <div class="form-group">
                                            <label for="email2">Item Picture (optional)</label>
                                            <input type="file" class="form-control" id="email2" name="item_pic"
                                                value="{{ old('item_pic') }}" accept="image/*" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 w-100">
                                        <div class="form-group">
                                            <label for="email2">Description (optional)</label>
                                            <textarea class="form-control" id="email2" name="description" value="{{ old('description') }}"
                                                placeholder="Stock Description"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-action">
                                    <button type="submit" disabled id="submit-btn"
                                        class="btn btn-primary">Submit</button>
                                </div>
                            </form>

                            <div class="card-header" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="card-title" style="color: #007BFF;">*upload bulk products</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('download.file') }}"
                                            class="btn btn-secondary float-end">Download
                                            CSV</a>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data"
                                class="card-body" style="display: none;">
                                @csrf
                                <div class="col-md-6 col-lg-4 w-100">
                                    <div class="form-group">
                                        <label for="file">Upload File (CSV or Excel)</label>
                                        <input type="file" class="form-control" name="file"
                                            accept=".csv,.xlsx,.xls">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Import Products</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('selectOption').addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex].value;
            document.getElementById('service-row').style.display = 'none';
            document.getElementById('row-goods').style.display = 'none';
            document.getElementById('submit-btn').disabled = true;

            if (selectedOption === 'is_goods') {
                document.getElementById('row-goods').style.display = 'flex';
                document.getElementById('service-row').style.display = 'none';
                document.getElementById('submit-btn').disabled = false;
            } else if (selectedOption === 'is_service') {
                document.getElementById('service-row').style.display = 'flex';
                document.getElementById('row-goods').style.display = 'none';
                document.getElementById('submit-btn').disabled = false;
            }
        });
    </script>
@stop
