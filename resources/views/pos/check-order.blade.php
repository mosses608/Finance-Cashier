@extends('layouts.mini')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row mt-3">
                <x-messages />
            </div>
            <form action="{{ route('ussd.push') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="d-flex flex-column flex-md-row gap-4">
                    <div class="flex-grow-1">
                        <h4 class="mb-3">Order summary</h4>
                        <div class="d-flex align-items-center mb-3 p-2 border rounded shadow-sm">
                            <img src="{{ asset('storage/' . $product->picture ?? '') }}" alt="{{ $product->productName }}"
                                class="img-fluid"
                                style="width: 90px; height: 100px; object-fit: cover; border-radius: 8px;">

                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1 text-capitalize">{{ $product->productName }}</h6>
                                <p class="mb-1 text-success"><strong>TZS
                                        {{ number_format($product->sellingPrice, 2) }}</strong>
                                </p>
                                @php
                                    $productId = \Illuminate\Support\Facades\Crypt::encrypt(json_encode($productId));
                                @endphp
                                <p class="mb-1 text-muted">Qty:
                                    <input type="number" value="1" name="quantity" id="qty-input">
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary Box -->
                    <div style="min-width: 280px;">
                        <div class="border rounded p-3 shadow-sm">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                @php
                                    $subtotal = $product->sellingPrice;
                                    $vat = 0;
                                    $total = 0;
                                @endphp
                                <span class="text-success">TZS <strong
                                        id="subtotal">{{ number_format($subtotal, 2) }}</strong></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                @php
                                    $total = $subtotal + $vat;
                                @endphp
                                <span>TZS <strong id="total">{{ number_format($total, 2) }}</strong></span>
                            </div>
                            <hr>

                            <div class="mb-3">
                                <input type="tel" maxlength="10" class="form-control mb-2" name="phone"
                                    placeholder="Phone number..." required>
                                <input type="hidden" name="productId" value="{{ $productId }}" id="">
                                {{-- <button type="button" class="btn btn-teal w-100">Apply</button> --}}
                            </div>

                            <h6 class="mb-2">Sales Conditions</h6>
                            <p class="small text-muted mb-3">
                                By purchasing this product from our store, you agree with the
                                <a href="#" class="text-decoration-none">Sales Conditions</a>.
                            </p>

                            <input type="hidden" name="amount" id="amount">

                            <button type="submit" class="btn btn-teal w-100" name="pay" value="cash">Cash
                                Checkout</button>
                            <div class="mb-4 mt-4 col-12 text-center">
                                <a href="#" class="text-uppercase">or</a>
                            </div>
                            <button type="submit" class="btn btn-purple w-100" name="pay" value="mobile">Mobile
                                Checkout &gt;</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="loader"
        style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(255,255,255,0.9);
    z-index:9999;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    font-size:20px;
    font-weight:bold;
    color:#0d6efd;
">
        <div class="spinner"></div>
        <p style="margin-top:15px;">Processing Transaction...</p>
    </div>

    <style>
        /* Spinner CSS */
        .spinner {
            border: 6px solid #f3f3f3;
            /* Light grey */
            border-top: 6px solid #0d6efd;
            /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        document.getElementById('checkoutForm').addEventListener('submit', function() {
            const loader = document.getElementById('loader');
            loader.style.display = 'flex';
        });
    </script>

    <script>
        // Store original base values on page load
        const totalElement = document.getElementById('total');
        const subtotalElement = document.getElementById('subtotal');

        const originalTotal = parseFloat(totalElement.textContent.replace(/,/g, ''));
        const originalSubtotal = parseFloat(subtotalElement.textContent.replace(/,/g, ''));
        document.getElementById('amount').value = originalTotal;

        document.getElementById("qty-input").addEventListener('input', function() {
            const inputValue = Number(this.value);

            const totalValue = inputValue * originalTotal;
            const subtotalValue = inputValue * originalSubtotal;

            totalElement.textContent = totalValue.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            subtotalElement.textContent = subtotalValue.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            document.getElementById('amount').value = totalValue;
        });
    </script>

    <style>
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

    <style>
        .btn-teal {
            background-color: #008080;
            color: #fff;
        }

        .btn-teal:hover {
            background-color: #006666;
        }

        .btn-purple {
            background-color: #6b4c70;
            color: #fff;
        }

        .btn-purple:hover {
            background-color: #563a57;
        }

        #qty-input {
            padding: 2px !important;
            outline: none;
            border: none;
            background-color: inherit !important;
            box-shadow: none;
            /* border-bottom: 1.2px solid #000; */
        }

        #qty-input:focus {
            outline: none;
            border: none;
            background-color: inherit !important;
            box-shadow: none;
            border-bottom: 1.2px solid #007bff;
        }
    </style>
@endsection
