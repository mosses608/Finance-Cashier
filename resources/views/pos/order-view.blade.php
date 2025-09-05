@extends('layouts.part')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('partials.nav-bar')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row mt-3">
                <x-messages />
            </div>
            <form action="{{ route('order.payment') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="d-flex flex-column flex-md-row gap-4">
                    <div class="flex-grow-1">
                        <h4 class="mb-3">Order summary</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($orderItems as $item)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-3 p-2 border rounded shadow-sm">
                                                <img src="{{ asset('storage/' . $item->picture) }}"
                                                    alt="{{ $item->productName }}" class="img-fluid"
                                                    style="width: 90px; height: 100px; object-fit: cover; border-radius: 8px;">

                                                <div class="ms-3 flex-grow-1">
                                                    <h6 class="mb-1 text-capitalize">{{ $item->productName }}</h6>
                                                    <p class="mb-1 text-success"><strong>TZS
                                                            {{ number_format($item->sellingPrice, 2) }}</strong><sup
                                                            class="text-warning">@</sup>
                                                    </p>
                                                    <p class="mb-1 text-muted">
                                                    <div class="quantity-selector" data-id="{{ $item->productId }}"
                                                        data-price="{{ $item->sellingPrice }}">
                                                        <button type="button" class="qty-btn decrement">-</button>
                                                        <input type="number" name="quantity[]" class="qty-input"
                                                            value="1" min="1">
                                                        <button type="button" class="qty-btn increment">+</button>
                                                    </div>
                                                    </p>
                                                    <input type="hidden" name="seling_price[]"
                                                        value="{{ $item->sellingPrice }}" id="">
                                                    <input type="hidden" name="product_id[]" value="{{ $item->productId }}"
                                                        id="">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="min-width: 280px;">
                                    <div class="border rounded p-3 shadow-sm">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal</span>
                                            @php
                                                $subtotal = 0;
                                                $vat = 0;
                                            @endphp
                                            <span class="text-success">TZS <strong id="subtotal">0.00</strong></span>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax {{ $hasVRN == true ? '(18%)' : '(0%)' }}</span>
                                            @php
                                                $subtotal = 0;
                                                $vat = 0;
                                            @endphp
                                            @if ($hasVRN == true)
                                                <span class="text-success" id="tax">0.00</span>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between mb-3">
                                            <strong>Total</strong>
                                            @php
                                                $total = $subtotal + $vat;
                                            @endphp
                                            <span>TZS <strong id="total">0.00</strong></span>
                                        </div>
                                        <hr>

                                        <div class="mb-3">
                                            <input type="tel" maxlength="10" class="form-control mb-2" name="phone"
                                                placeholder="Phone number..." value="{{ old('phone') }}" required>
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
                                        <button type="submit" class="btn btn-purple w-100" name="pay"
                                            value="mobile">Mobile
                                            Checkout &gt;</button>
                                    </div>
                                </div>
                            </div>
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

    {{-- <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const qrUrl = @json($qrUrl);

            new QRCode(document.getElementById("qrcode"), {
                text: qrUrl,
                // width: 150,
                // height: 150,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });
    </script> --}}
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

        .quantity-selector {
            display: inline-flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            width: 110px;
            /* adjust width as needed */
        }

        .quantity-selector .qty-input {
            width: 50px;
            text-align: center;
            border: none;
            outline: none;
            font-size: 14px;
            padding: 5px 0;
        }

        .quantity-selector .qty-btn {
            flex: 1;
            background-color: #f5f5f5;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            padding: 5px 0;
            transition: background-color 0.2s;
        }

        .quantity-selector .qty-btn:hover {
            background-color: #e0e0e0;
        }
    </style>

    <script>
        document.getElementById('checkoutForm').addEventListener('submit', function() {
            const loader = document.getElementById('loader');
            loader.style.display = 'flex';
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subtotalEl = document.getElementById('subtotal');
            const taxEl = document.getElementById('tax');
            const totalEl = document.getElementById('total');
            const amountInput = document.getElementById('amount');

            const hasVRN = {{ $hasVRN ? 'true' : 'false' }};

            // Function to update totals
            function updateTotals() {
                let subtotal = 0;

                const qtyInputs = document.querySelectorAll('.quantity-selector .qty-input');

                qtyInputs.forEach(input => {
                    const qty = parseFloat(input.value) || 0;
                    const price = parseFloat(input.parentElement.dataset.price) ||
                        0; // get price from wrapper
                    subtotal += qty * price;
                });

                const tax = hasVRN ? subtotal * 0.18 : 0;
                const total = subtotal + tax;

                // Update HTML
                subtotalEl.textContent = subtotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                taxEl.textContent = tax.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                totalEl.textContent = total.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Update hidden input for form submission
                amountInput.value = total.toFixed(2);
            }

            // Increment/Decrement buttons
            const selectors = document.querySelectorAll('.quantity-selector');

            selectors.forEach(selector => {
                const input = selector.querySelector('.qty-input');
                const decrement = selector.querySelector('.decrement');
                const increment = selector.querySelector('.increment');

                decrement.addEventListener('click', () => {
                    let value = parseInt(input.value) || 1;
                    if (value > 1) input.value = value - 1;
                    updateTotals(); // update totals after change
                });

                increment.addEventListener('click', () => {
                    let value = parseInt(input.value) || 1;
                    input.value = value + 1;
                    updateTotals(); // update totals after change
                });

                // Also listen for manual input changes
                input.addEventListener('input', updateTotals);
            });

            // Initialize totals on page load
            updateTotals();
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectors = document.querySelectorAll('.quantity-selector');

            selectors.forEach(selector => {
                const input = selector.querySelector('.qty-input');
                const decrement = selector.querySelector('.decrement');
                const increment = selector.querySelector('.increment');

                decrement.addEventListener('click', () => {
                    let value = parseInt(input.value) || 1;
                    if (value > 1) input.value = value - 1;
                    input.dispatchEvent(new Event('input')); // trigger total update
                });

                increment.addEventListener('click', () => {
                    let value = parseInt(input.value) || 1;
                    input.value = value + 1;
                    input.dispatchEvent(new Event('input')); // trigger total update
                });
            });
        });
    </script>
@endsection
