@extends('layouts.part')
@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#sales-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true" style="color: #007BFF;">Sales Receipt</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="sales-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="container p-4 border rounded bg-white shadow-sm">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h4><strong>Sales Receipt</strong></h4>
                                            <div>
                                                <p class="mb-1"><strong>Payment Date:</strong>
                                                    @if ($paymentData->status == 0)
                                                        <i class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                        <span style="color: #007BFF;">{{ __('Pending Payment...') }}</span>
                                                    @else
                                                        {{ \Carbon\Carbon::parse($paymentData->updated_at)->format('M d, Y') }}
                                                    @endif
                                                </p>
                                                <p><strong>Receipt #:</strong> <span
                                                        style="color: #007BFF;">{{ str_pad($saleAutoId, 4, '0', STR_PAD_LEFT) }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <p><strong>From:</strong></p>
                                                <div class="border p-2" style="min-height: 100px;">
                                                    <!-- Add from-address here -->
                                                    <p class="mb-0">Akili Soft Tech</p>
                                                    <p class="mb-0">Dar es salaam, Kigamboni</p>
                                                    <p class="mb-0">Dar es salaam, Tanzania</p>
                                                    <p class="mb-0">255 694 235 858 | support@akilisoft.com</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Sold To:</strong></p>
                                                <div class="border p-2" style="min-height: 100px;">
                                                    <!-- Add customer info here -->
                                                    <p class="mb-0">Customer: {{ $customerData->name }}</p>
                                                    <p class="mb-0">Region: {{ $customerData->address ?? '' }}</p>
                                                    <p class="mb-0">Nation: Tanzania</p>
                                                    <p class="mb-0">Phone: {{ $customerData->phone ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Table -->
                                        <div class="table-responsive mb-4">
                                            <table class="table table-bordered table-striped align-middle">
                                                <thead class="table-primary text-center">
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Discount (%)</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $totalAmount = 0;
                                                    $totalDiscount = 0;
                                                    $vat = 0;
                                                @endphp
                                                <tbody>
                                                    @if (count($receiptSalesOutOfStore) != 0)
                                                        @foreach ($receiptSalesOutOfStore as $receiptOutStore)
                                                            @php
                                                                $totalDiscount +=
                                                                    $receiptOutStore->discount *
                                                                    $receiptOutStore->amountPay;
                                                                $totalAmount +=
                                                                    $receiptOutStore->quantity *
                                                                        $receiptOutStore->amountPay -
                                                                    $totalDiscount;
                                                            @endphp
                                                            <tr>
                                                                <td class="text-center">{{ $receiptOutStore->product_name }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ number_format($receiptOutStore->quantity) }}</td>
                                                                <td class="text-end">
                                                                    {{ number_format($receiptOutStore->amountPay, 2) }}</td>
                                                                <td class="text-center">
                                                                    {{ number_format($receiptOutStore->discount * $receiptOutStore->amountPay, 2) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($receiptOutStore->quantity * $receiptOutStore->amountPay) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif

                                                    @if (count($receiptSalesFromStore) != 0)
                                                        @foreach ($receiptSalesFromStore as $receiptFromStore)
                                                            @php
                                                                $totalDiscount +=
                                                                    $receiptFromStore->discount *
                                                                    $receiptFromStore->amount;
                                                                $totalAmount +=
                                                                    $receiptFromStore->quantity *
                                                                        $receiptFromStore->amount -
                                                                    $totalDiscount;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $receiptFromStore->name }}</td>
                                                                <td class="text-center">
                                                                    {{ number_format($receiptFromStore->quantity) }}</td>
                                                                <td class="text-end">
                                                                    {{ number_format($receiptFromStore->amount, 2) }}</td>
                                                                <td class="text-center">{{ number_format($receiptFromStore->amount * $receiptFromStore->discount, 2) }}</td>
                                                                <td class="text-end">
                                                                    {{ number_format($receiptFromStore->quantity * $receiptFromStore->amount - $receiptFromStore->amount * $receiptFromStore->discount, 2) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif

                                                    @if (count($salesReceiptFromServices) > 0)
                                                        @foreach ($salesReceiptFromServices as $receiptService)
                                                            @php
                                                                $totalDiscount +=
                                                                    $receiptService->discount * $receiptService->amount;
                                                                $totalAmount +=
                                                                    $receiptService->quantity *
                                                                        $receiptService->amount -
                                                                    $totalDiscount;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $receiptService->name }}</td>
                                                                <td class="text-center">
                                                                    {{ number_format($receiptService->quantity) }}</td>
                                                                <td class="text-end">
                                                                    {{ number_format($receiptService->amount, 2) }}</td>
                                                                <td class="text-center">
                                                                    {{ number_format($receiptService->discount * $receiptService->amount, 2) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ number_format($receiptService->quantity * $receiptService->amount - $receiptService->discount * $receiptService->amount) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Payment Method:</strong>
                                                    @if ($paymentData->status == 0)
                                                        <i class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                        <span class="sm" style="color: #007BFF;">Pending Payment</span>
                                                    @else
                                                        <strong
                                                            class="text-primary">{{ $paymentData->payment_method }}</strong>
                                                    @endif
                                                </p>
                                                <p><strong>Amount Paid:</strong>
                                                    <span
                                                        style="color: #007BFF;"><strong>{{ number_format($paymentData->amount_paid, 2) }}</strong>
                                                        TSH</span>
                                                </p>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <p><strong>Subtotal:</strong> <strong
                                                        style="color: #007BFF;">{{ number_format($totalAmount, 2) }}
                                                        TSH</strong></p>
                                                <p><strong>Tax Rate:</strong> <strong
                                                        style="color: #007BFF;">{{ number_format($paymentData->tax, 2) }}
                                                    </strong>
                                                </p>
                                                <p><strong>Total Amount Due:</strong> <strong
                                                        style="color: #007BFF;">{{ number_format($totalAmount + $paymentData->tax, 2) }}
                                                        TSH</strong></p>
                                            </div>
                                        </div>

                                        <div class="text-center mt-3">
                                            <p><em>Thank you for your business!</em></p>
                                        </div>
                                        @php
                                            $encryptedReceiptId = Crypt::encrypt($saleAutoId);
                                        @endphp
                                    </div>
                                    <a href="{{ route('download.receipt', $encryptedReceiptId) }}"
                                        class="btn btn-primary float-end mt-4"><i class="fa fa-download"></i> Download
                                        Receipt</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
