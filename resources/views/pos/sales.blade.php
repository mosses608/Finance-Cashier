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
                    <h3 class="fw-bold mb-3">POS | <strong
                            class="op-7 mb-2">{{ \Carbon\Carbon::today()->format('M d, Y') }}</strong>
                        </span>
                    </h3>
                    {{-- <h6 class="op-7 mb-2">{{ \Carbon\Carbon::today()->format('M d, Y') }}</h6> --}}
                </div>
                @if (true)
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ route('storage.manage') }}" target="__blank" class="btn btn-primary btn-round"><i
                                class="fa fa-plus"></i> Add Products</a>
                    </div>
                @endif
            </div>
            <div class="row">

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            {{-- <div class="card-head-row card-tools-still-right"> --}}
                            <div class="row">
                                <div class="col-md-6 card-title text-success">Available Products</div>
                                <div class="col-md-6">
                                    <input type="search" id="productSearch" class="form-control float-end"
                                        placeholder="Search...">
                                </div>
                            </div>
                            {{-- </div> --}}
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <div class="row col-12 p-1" id="productContainer">
                                    @foreach ($availableProducts as $product)
                                        @php
                                            $productId = \Illuminate\Support\Facades\Crypt::encrypt(
                                                json_encode($product->productId),
                                            );
                                        @endphp
                                        <div class="col-md-3 product-card">
                                            <div class="card p-2 shadow-sm card-hover position-relative">
                                                <img src="{{ asset('storage/' . $product->picture) }}" class="card-img-top"
                                                    alt="{{ $product->productName }}"
                                                    style="height: 150px; object-fit: cover; border-radius: 10px;">

                                                <div class="card-body text-start">
                                                    <h6 class="card-title mb-0 text-primary fs-6 text-capitalize d-flex">
                                                        {{ $product->productName }}
                                                    </h6>
                                                    <div class="row mt-2">
                                                        <div class="col-md-8">
                                                            <p class="text-muted float-start fs-6">
                                                                <strong>{{ number_format($product->sellingPrice) . '/=' }}</strong>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="text-success fs-6 float-end text-nowrap">
                                                                <i class="fas fa-shopping-cart"></i>
                                                                <sub>{{ number_format($product->availableQuantity) }}</sub>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Hidden action section -->
                                                <div class="card-actions position-absolute bottom-0 start-0 end-0 p-2 d-flex justify-content-between align-items-center"
                                                    style="display: none;">
                                                    <a href="{{ route('view-pos-product', $productId) }}"
                                                        target="__blank"class="text-primary"><i class="fa fa-eye"></i></a>
                                                    <input type="checkbox" class="form-check-input" />
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

    <style>
        .card-hover:hover .card-actions {
            display: flex !important;
        }

        .card-hover {
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .card-hover:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card-hover img.card-img-top {
            transition: filter 0.3s ease, opacity 0.3s ease;
        }

        .card-hover:hover img.card-img-top {
            filter: blur(0px);
            opacity: 0.7;
        }
    </style>

    <script>
        document.getElementById('productSearch').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let products = document.querySelectorAll('#productContainer .product-card');

            products.forEach(function(product) {
                let productName = product.querySelector('.card-title').textContent.toLowerCase();
                let productSerial = product.querySelector('.card-img-top').getAttribute('alt')
                    .toLowerCase();

                if (productName.includes(searchValue) || productSerial.includes(searchValue)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        });
    </script>
@endsection
