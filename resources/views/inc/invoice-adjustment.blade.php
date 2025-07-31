@extends('layouts.part')
@include('partials.nav-bar')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <x-messages />
                        <div class="card-header">
                            <h4 class="card-title fs-5 text-primary">Profoma Invoice Adjustments: <strong
                                    class="text-secondary">#{{ $profomaInvoiceId }}</strong>
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (!$profomaInvoiceId)
                                <form id="create-sale-form" method="GET" action="{{ route('invoice.adjustments') }}">
                                    <div class="row justify-content-center">
                                        <div class="col-8 mb-3">
                                            <label for="invoice_id" class="form-label d-flex">
                                                <strong>Invoice ID</strong>
                                            </label>
                                            <div class="input-group">
                                                <div class="form-floating flex-grow-1">
                                                    <input type="number" id="invoice_id" name="invoice_id"
                                                        class="form-control" placeholder="Enter Invoice ID">
                                                    <label for="invoice_id">Enter Invoice ID</label>
                                                </div>
                                                <button type="submit" id="fetch-invoice"
                                                    class="btn btn-secondary">Fetch</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            @if (count($profomaInvoiceItems) > 0)
                                <form action="{{ route('adjust.invoice') }}" method="POST">
                                    @csrf
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr><strong>Item List:</strong></tr>
                                            <tr>
                                                <th class="text-capitalize">#</th>
                                                <th class="text-capitalize">Item Name</th>
                                                <th class="text-capitalize">Unit Price</th>
                                                <th class="text-capitalize">Quantity</th>
                                                <th class="text-capitalize">Discount (TZS)</th>
                                                <th class="text-capitalize">Total Amount (TZS)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $itemTotal = 0;
                                                $totalDiscount = 0;
                                                $totalAmountWithoutDiscount = 0;
                                            @endphp
                                            @foreach ($profomaInvoiceItems as $item)
                                                @php
                                                    $totalDiscount += $item->unitPrice * $item->discount;
                                                    $totalAmountWithoutDiscount += $item->unitPrice * $item->quantity;
                                                @endphp
                                                <input type="hidden" name="invoice_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($profomaInvoiceId) }}"
                                                    id="">
                                                <input type="hidden" name="type" id="" value="item" required>
                                                <tr data-item-id="{{ $item->itemId }}">
                                                    <td>
                                                        <input type="checkbox" name="" class="item-check"
                                                            value="{{ $item->itemId }}">
                                                        <input type="hidden" name="item_id[]" id=""
                                                            value="{{ $item->itemId }}">
                                                    </td>
                                                    <td>{{ $item->itemName }}</td>
                                                    <td>
                                                        <input type="text" class="form-control unit-price"
                                                            style="width: 120px" name="unit_price[]"
                                                            id="unit-price-{{ $item->itemId }}"
                                                            value="{{ number_format($item->unitPrice) }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control quantity"
                                                            name="quantity[]" id="quantity-{{ $item->itemId }}"
                                                            style="width: 60px"
                                                            value="{{ number_format($item->quantity) }}" readonly>
                                                    </td>
                                                    @php
                                                        $discountAmount = ($item->unitPrice * $item->discount)/100;
                                                    @endphp
                                                    <td>
                                                        <input type="text" class="form-control discount" name="dicount[]"
                                                            id="unit-discount-{{ $item->itemId }}"
                                                            value="{{ number_format($discountAmount, 2) }}"
                                                            readonly>
                                                    </td>
                                                    <td id="totalAmount">
                                                        <input type="text" style="font-weight: 900;"
                                                            class="form-control text-success subtotal"
                                                            name="SubTotalAmount[]"
                                                            value="{{ number_format($item->unitPrice * $item->quantity - $discountAmount, 2) }}"
                                                            id="totalUnitPrice-{{ $item->itemId }}" readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @php
                                                $vatTotal = 0;
                                                $itemTotal = $totalAmountWithoutDiscount - $totalDiscount;
                                            @endphp
                                        </tbody>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="checked"
                                                    id="checkDefault" required>
                                                <label class="form-check-label text-primary" for="checkDefault">
                                                    Check here to confirm adjusting this invoice...
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                                Submit</button>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            {{-- service invoice view --}}
                            @if (count($invoiceServiceItems) > 0)
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr><strong>Item List:</strong></tr>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Item Name</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Discount (%)</th>
                                            <th>Discount Value</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $itemTotal = 0;
                                            $totalDiscount = 0;
                                            $totalAmountWithoutDiscount = 0;
                                        @endphp
                                        @foreach ($invoiceServiceItems as $item)
                                            @php
                                                $totalDiscount += $item->unitPrice * $item->discount;
                                                $totalAmountWithoutDiscount += $item->unitPrice * $item->quantity;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->itemName }}</td>
                                                <td>{{ number_format($item->unitPrice, 2) }}</td>
                                                <td>{{ number_format($item->quantity) }}</td>
                                                <td>{{ $item->discount }}</td>
                                                <td>{{ number_format($item->unitPrice * $item->discount, 2) }}</td>
                                                <td>{{ number_format($item->unitPrice * $item->quantity) }}</td>
                                            </tr>
                                        @endforeach
                                        @php
                                            $vatTotal = 0;
                                            $itemTotal = $totalAmountWithoutDiscount - $totalDiscount;
                                        @endphp
                                    <tfoot>
                                        @if (count($invoiceServiceItems) != 0)
                                            @if ($item->vrn != null)
                                                @php
                                                    $vatTotal = $itemTotal * 0.18;
                                                @endphp
                                                <tr>
                                                    <td class="text-nowrap">VAT (18%)</td>
                                                    <td colspan="5"></td>
                                                    <td>{{ number_format($vatTotal, 2) }}</td>
                                                </tr>
                                            @endif
                                        @endif
                                        <tr class="text-nowrap">
                                            <td><strong>Totals (TSH)</strong></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>
                                                    {{ number_format($totalDiscount, 2) }}</strong>
                                            </td>
                                            <td><strong>
                                                    {{ number_format($totalAmountWithoutDiscount + $vatTotal - $totalDiscount, 2) }}</strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                    </tbody>
                                </table>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.item-check').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const itemId = this.value;
                const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
                if (row) {
                    row.querySelectorAll('input:not([type="checkbox"])').forEach(input => {
                        input.readOnly = !this.checked;
                    });
                }
            });
        });
    </script>



@endsection
