@extends('layouts.part')
@include('partials.nav-bar')
@section('content')
<div class="container my-5">
    <div class="page-inner">
        <div class="row justify-content-center">
            <div class="col-lg-10 mt-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">Sales Receipt</h5>
                        <x-messages />
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $companyData->logo) }}" width="200px" height="100px" alt="Company Logo" class="mb-2">
                            <h5 class="fw-bold text-primary">{{ $companyData->name }}</h5>
                        </div>

                        <div class="d-flex justify-content-between border-bottom pb-2 mb-4">
                            <div>
                                <p class="mb-1"><strong>Payment Date:</strong>
                                    @if ($paymentData->status == 0)
                                        <i class="fas fa-spinner fa-spin text-warning me-2"></i>
                                        <span class="text-info">Pending Payment...</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($paymentData->updated_at)->format('M d, Y') }}
                                    @endif
                                </p>
                                <p class="mb-0"><strong>Receipt #:</strong>
                                    <span class="text-primary">{{ str_pad($saleAutoId, 4, '0', STR_PAD_LEFT) }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6><strong>From:</strong></h6>
                                <div class="border rounded p-2 bg-light">
                                    <p class="mb-0 text-primary fw-bold">{{ $companyData->name }}</p>
                                    <p class="mb-0">{{ $companyData->address }}</p>
                                    <p class="mb-0">TIN: {{ $companyData->TIN }}</p>
                                    <p class="mb-0">{{ $companyData->email }}</p>
                                    <p class="mb-0"><a href="{{ $companyData->webiste }}" target="_blank">{{ $companyData->webiste }}</a></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Sold To:</strong></h6>
                                <div class="border rounded p-2 bg-light">
                                    <p class="mb-0">Customer: {{ $customerData->name }}</p>
                                    <p class="mb-0">Region: {{ $customerData->address ?? '-' }}</p>
                                    <p class="mb-0">Nation: Tanzania</p>
                                    <p class="mb-0">Phone: {{ $customerData->phone ?? '-' }}</p>
                                    <p class="mb-0">Email: {{ $customerData->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Sales Table --}}
                        @php
                            $totalDiscount = 0;
                            $totalAmountWithDiscount = 0;
                            $hasVAT = $hasVrn;
                            $serial = 1;
                        @endphp

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Discount (%)</th>
                                        <th>Discount (TZS)</th>
                                        <th>Total (TZS)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Loop through all items --}}
                                    @foreach (array_merge($receiptSalesOutOfStore->toArray(), $receiptSalesFromStore->toArray(), $salesReceiptFromServices->toArray()) as $item)
                                        @php
                                            $itemName = $item->product_name ?? $item->name;
                                            $unitPrice = $item->amountPay ?? $item->amount;
                                            $itemTotal = $item->quantity * $unitPrice;
                                            $discountValue = ($itemTotal * $item->discount) / 100;
                                            $amountAfterDiscount = $itemTotal - $discountValue;
                                            $totalDiscount += $discountValue;
                                            $totalAmountWithDiscount += $amountAfterDiscount;
                                        @endphp
                                        <tr>
                                            <td>{{ $serial++ }}</td>
                                            <td>{{ $itemName }}</td>
                                            <td>{{ number_format($item->quantity) }}</td>
                                            <td class="text-end">{{ number_format($unitPrice, 2) }}</td>
                                            <td class="text-end">{{ $item->discount }}%</td>
                                            <td class="text-end">{{ number_format($discountValue, 2) }}</td>
                                            <td class="text-end">{{ number_format($amountAfterDiscount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @php
                                        $vatTotal = $hasVAT ? $totalAmountWithDiscount * 0.18 : 0;
                                        $grandTotal = $totalAmountWithDiscount + $vatTotal;
                                    @endphp
                                    @if ($hasVAT)
                                        <tr>
                                            <td colspan="6" class="text-end"><strong>VAT (18%)</strong></td>
                                            <td class="text-end">{{ number_format($vatTotal, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Total Discount</strong></td>
                                        <td class="text-end">{{ number_format($totalDiscount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Total Amount Due</strong></td>
                                        <td class="text-end text-success fw-bold">{{ number_format($grandTotal, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Payment Summary --}}
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Payment Method:</strong></h6>
                                @if ($paymentData->status == 0)
                                    <span class="text-info"><i class="fas fa-spinner fa-spin text-warning me-2"></i> Pending Payment</span>
                                @else
                                    <span class="fw-bold text-primary">{{ $paymentData->payment_method }}</span>
                                @endif

                                <p class="mt-2"><strong>Amount Paid:</strong>
                                    <span class="text-primary fw-bold">{{ number_format($paymentData->amount_paid, 2) }} TZS</span>
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p><strong>Subtotal:</strong> <span class="text-primary">{{ number_format($totalAmountWithDiscount, 2) }} TZS</span></p>
                                <p><strong>Discount:</strong> <span class="text-primary">{{ number_format($totalDiscount, 2) }} TZS</span></p>
                                <p><strong>VAT:</strong> <span class="text-primary">{{ number_format($vatTotal, 2) }} TZS</span></p>
                                <p><strong>Total Amount Due:</strong> <span class="text-success fw-bold">{{ number_format($grandTotal, 2) }} TZS</span></p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <em class="text-muted">Thank you for your business!</em>
                        </div>

                        @php $encryptedReceiptId = Crypt::encrypt($saleAutoId); @endphp
                        <div class="text-start mt-4">
                            <a href="{{ route('download.receipt', $encryptedReceiptId) }}" class="btn btn-primary">
                                <i class="fa fa-download me-1"></i> Download Receipt
                            </a>
                        </div>

                    </div> {{-- card-body --}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
