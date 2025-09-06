@extends('layouts.part')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('partials.nav-bar')
@section('content')
    <style>
        .btn-radius-20 {
            border-radius: 20px !important;
        }
    </style>
    <div class="container">
        <div class="page-inner">
            <div class="row mt-3">
                <x-messages />
            </div>
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Point of Sales | <strong
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
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            {{-- <div class="card-head-row card-tools-still-right"> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="search" id="productSearch" class="form-control float-end"
                                        placeholder="Search...">
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row p-1">
                                <div class="col-md-8">
                                    <div class="row p-1">
                                        @foreach ($availableProducts as $product)
                                            @php
                                                $productId = \Illuminate\Support\Facades\Crypt::encrypt(
                                                    json_encode($product->productId),
                                                );
                                                $encryptedId = \Illuminate\Support\Facades\Crypt::encrypt(
                                                    $product->productId,
                                                );
                                            @endphp
                                            <div class="col-md-4 product-card" id="product-card-{{ $encryptedId }}"
                                                data-encrypted-id="{{ $encryptedId }}">
                                                <div class="card p-2 shadow-sm card-hover position-relative">
                                                    <img src="{{ asset('storage/' . $product->picture) }}"
                                                    height="100%"
                                                        class="card-img-top" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdrop" data-bs-backdrop="false"
                                                        data-bs-keyboard="false" alt="{{ $product->productName }}"
                                                        style="height: 100%; object-fit: cover; border-radius: 10px;">

                                                    <div class="card-body text-start">
                                                        <h6
                                                            class="card-title mb-0 text-primary fs-6 text-capitalize d-flex">
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

                                                    <div class="card-actions" style="display: none;">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <a href="{{ route('view-pos-product', $productId) }}"
                                                                    target="__blank"
                                                                    class="btn btn-success btn-sm w-100 rounded btn-radius-20">
                                                                    <i class="fa fa-shopping-cart"></i> sale this item
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card shadow-sm border-0 rounded">
                                        <div class="card-header bg-white border-0 d-flex align-items-center">
                                            <i class="fas fa-shopping-cart me-2 text-muted"></i>
                                            <h6 class="mb-0 fw-bold">Sales Cart</h6>
                                        </div>
                                        <div class="card-body" id="cartContainer">
                                            <div class="text-center empty-cart">
                                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3 text-warning"></i>
                                                <h6 class="fw-semibold">Cart is empty</h6>
                                                <p class="text-muted">
                                                    Add products to start a new <span
                                                        class="text-primary fw-bold">sale</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white border-0 d-none" id="cartFooter">
                                            <div id="previewWrapper" class="p-2 d-none">
                                                <a href="#" id="previewOrderLink"
                                                    class="btn btn-primary btn-sm w-100 btn-radius-20">
                                                    <i class="fas fa-eye"></i> Preview Order
                                                </a>
                                            </div>
                                            {{-- <form action="" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_ids" id="cartProductIds">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-eye"></i> Preview Order
                                                </button>
                                            </form> --}}
                                        </div>
                                    </div>
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
            display: block !important;
        }

        .card-hover {
            /* height: 250px !important; */
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
            let searchValue = this.value.toLowerCase().trim();
            let products = document.querySelectorAll('.product-card');

            products.forEach(function(product) {
                let productName = product.querySelector('.card-title').textContent.toLowerCase();
                let productAlt = product.querySelector('.card-img-top').getAttribute('alt').toLowerCase();

                if (productName.includes(searchValue) || productAlt.includes(searchValue)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cartContainer = document.getElementById("cartContainer");
            const cartFooter = document.getElementById("cartFooter");
            const previewWrapper = document.getElementById("previewWrapper");
            const previewOrderLink = document.getElementById("previewOrderLink");

            let cartProductIds = [];

            // Blade-generated route with placeholder
            const basePreviewRoute = `{{ route('preview.order', ['productIds' => ':ids']) }}`;

            // Handle adding products
            document.querySelectorAll(".product-card").forEach(card => {
                card.addEventListener("click", function() {
                    const encryptedId = this.dataset.encryptedId;
                    const imgSrc = this.querySelector(".card-img-top").getAttribute("src");
                    const productName = this.querySelector(".card-title").textContent;

                    // Prevent duplicates
                    if (cartProductIds.includes(encryptedId)) return;
                    cartProductIds.push(encryptedId);

                    // Remove empty cart placeholder
                    const emptyCart = cartContainer.querySelector(".empty-cart");
                    if (emptyCart) emptyCart.remove();

                    // Add cart item
                    const cartItem = document.createElement("div");
                    cartItem.classList.add("position-relative", "d-inline-block", "m-2");
                    cartItem.dataset.encryptedId = encryptedId;

                    cartItem.innerHTML = `
                <img src="${imgSrc}" alt="${productName}" class="rounded shadow-sm" 
                     style="width: 90px; height: 80px; object-fit: cover;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-item" 
                        style="border-radius: 50%; padding: 2px 6px;">
                    <i class="fas fa-times"></i>
                </button>
            `;

                    cartContainer.appendChild(cartItem);

                    updateCartFooter();
                    updatePreviewLink();
                });
            });

            // Handle removing products
            document.addEventListener("click", function(e) {
                if (e.target.closest(".remove-item")) {
                    const cartItem = e.target.closest(".position-relative");
                    if (!cartItem) return;

                    const encryptedId = cartItem.dataset.encryptedId;
                    cartProductIds = cartProductIds.filter(id => id !== encryptedId);
                    cartItem.remove();

                    if (cartContainer.children.length === 0) {
                        cartContainer.innerHTML = `
                    <div class="text-center empty-cart">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3 text-warning"></i>
                        <h6 class="fw-semibold">Cart is empty</h6>
                        <p class="text-muted">
                            Add products to start a new <span class="text-primary fw-bold">sale</span>
                        </p>
                    </div>
                `;
                    }

                    updateCartFooter();
                    updatePreviewLink();
                }
            });

            // Show/Hide cart footer
            function updateCartFooter() {
                if (cartProductIds.length > 0) {
                    cartFooter.classList.remove("d-none");
                } else {
                    cartFooter.classList.add("d-none");
                }
            }

            // Update Preview Order link
            function updatePreviewLink() {
                if (cartProductIds.length > 0) {
                    previewOrderLink.href = basePreviewRoute.replace(":ids", encodeURIComponent(cartProductIds.join(
                        ",")));
                    previewWrapper.classList.remove("d-none");
                } else {
                    previewWrapper.classList.add("d-none");
                }
            }
        });
    </script>
@endsection
