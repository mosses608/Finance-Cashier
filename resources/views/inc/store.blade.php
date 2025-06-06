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
                            <h4 class="card-title">Add New Stock</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('store.products') }}" method="POST" class="row"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="email2">Stock Name</label>
                                        <input type="text" class="form-control" id="email2" name="name"
                                            value="{{ old('name') }}" placeholder="Stock or Product Name" required />
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="email2">Units (SKU)</label>
                                        <input type="text" class="form-control" id="email2" name="sku"
                                            value="{{ old('sku') }}" placeholder="Stock Keeping Unit eg. mm, cm, kg" required />
                                    </div>
                                </div>
                                {{--
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="email2">Quantity</label>
                                        <input type="text" class="form-control" id="email2" name="quantity"
                                            value="{{ old('quantity') }}" placeholder="Stock Quantity" />
                                    </div>
                                </div>
                                --}}
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="email2">Cost Price</label>
                                        <input type="number" class="form-control" id="email2" name="cost_price"
                                            value="{{ old('cost_price') }}" placeholder="Stock Cost Price" required />
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="email2">Selling Price</label>
                                        <input type="number" class="form-control" id="email2" name="selling_price"
                                            value="{{ old('selling_price') }}" placeholder="Selling Price" required />
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Store Name</label>
                                        <select class="form-control select2" name="store_id" id="exampleFormControlSelect1"
                                            style="width: 100%;">
                                            <option value="">--select--</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
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

                                <div class="card-action">
                                    <button type="submit" class="btn btn-success">Add Stock</button>
                                    <a href="{{ route('download.file') }}" class="btn btn-primary float-end">Download
                                        CSV</a>
                                </div>
                            </form>
                            <div class="card-header">
                                <h4 class="card-title" style="color: #007BFF;">*upload single csv file</h4>
                            </div>
                            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data"
                                class="card-body">
                                @csrf
                                <div class="col-md-6 col-lg-4 w-100">
                                    <div class="form-group">
                                        <label for="file">Upload File (CSV or Excel)</label>
                                        <input type="file" class="form-control" name="file" accept=".csv,.xlsx,.xls"
                                            required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mt-2">Import Products</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @stop











    @if (false)
        {{-- @extends('layouts.mainLayout') --}}

        @section('content')
            <div class="transparent" onclick="hideAll(event)"></div>

            @include('partials.sideNav')

            <x-messages />

            <div class="shortcut-report">
                <div class="md-7">
                    <h3>{{ __('Product List') }}</h3>
                    <button type="button" class="btn btn-secondary" onclick="regyStoreForm(event)"><i
                            class="fa fa-plus"></i>
                        Store</button>
                    <button type="button" class="btn btn-secondary" onclick="RegProdForm(event)"><i
                            class="fa fa-plus"></i>
                        Product</button>
                    <br>
                    <!-- <form action="{{ route('storage.manage') }}" method="GET" class="form-data">
                                                                            @csrf
                                                                            <div class="meta-data">
                                                                                <span><i class="fa fa-search"></i></span>
                                                                                <input
                                                                                 type="text"
                                                                                 name="search"
                                                                                 id=""
                                                                                 placeholder="Search Products"
                                                                                >
                                                                            </div> -->
                    <!-- <div class="export-print">
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
                                                                            </div> -->
                    <!-- <br><br> -->
                    </form>

                    @include('partials.product')

                    @include('partials.store')

                    <div class="scrollable" style="width: 100%; overflow-x: scroll;">
                        <table class="table table-bordered table-striped" id="table-data">
                            <thead class="table-black">
                                <tr style="position: static !important;">
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
                                @foreach ($products as $product)
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
                                                    $transactionsForProduct = $transactions->where(
                                                        'product_item_id',
                                                        $stock->storage_item_id,
                                                    );

                                                    foreach ($transactionsForProduct as $trans) {
                                                        $sumStockOut += $trans->stockout_quantity;
                                                    }
                                                } else {
                                                    $transactionsForProduct = null;
                                                }
                                            @endphp
                                            @if ($stock)
                                                @if ($stock->quantity_in - $sumStockOut == 0)
                                                    <span
                                                        style="color: red; font-size: 10px;"><strong>OutStock</strong></span>
                                                @elseif($stock->quantity_in - $sumStockOut >= 1 && $stock->quantity_in < 10)
                                                    <span style="color: maroon;"><strong>LessStock</strong></span>
                                                @elseif($stock->quantity_in - $sumStockOut >= 10)
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
                                                $transactionsForProduct = $transactions->where(
                                                    'product_item_id',
                                                    $stock->storage_item_id,
                                                );

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
                                        <td>
                                            <!-- <button class="view-usr" style="color: #007BFF;"> -->
                                            <a href="#" style-="color: #008800;"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('single.product', $product->id) }}"><i
                                                    class="fa fa-eye"></i></a>
                                            <!-- </button>  -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <script>
                    new DataTable('#table-data');
                </script>

            @stop

    @endif
