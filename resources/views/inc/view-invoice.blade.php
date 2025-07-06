@extends('layouts.part')
@include('partials.nav-bar')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                @if (false)
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                        <a href="#" class="btn btn-primary btn-round">Add Customer</a>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Invoice : <strong
                                    style="color: #0000FF;">{{ str_pad($invoiceId, 4, '0', STR_PAD_LEFT) }}</strong>
                            </h4>
                        </div>
                        @php
                            $totalAmountWithoutDiscount = 0;
                            $vatTotal = 0;
                            $totalDiscount = 0;
                        @endphp
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div class="table-responsive">
                                    {{-- products view --}}
                                    @if (count($invoiceItems) > 0)
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
                                                @foreach ($invoiceItems as $item)
                                                    @php
                                                        $totalDiscount += $item->unitPrice * $item->discount;
                                                        $totalAmountWithoutDiscount +=
                                                            $item->unitPrice * $item->quantity;
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
                                                @if (count($invoiceItems) != 0)
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
                                                        $totalAmountWithoutDiscount +=
                                                            $item->unitPrice * $item->quantity;
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

                                    {{-- ITEM OUT OF STORE --}}
                                    @if (count($itemsOutOfStore) > 0)
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
                                                @foreach ($itemsOutOfStore as $item)
                                                    @php
                                                        $totalDiscount += $item->unitPrice * $item->discount;
                                                        $totalAmountWithoutDiscount +=
                                                            $item->unitPrice * $item->quantity;
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

                                    <hr class="mt-5 mb-5">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr><strong>Transactions:</strong></tr>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Payer Phone</th>
                                                <th>Amount Paid</th>
                                                <th>Date Paid</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 p-3">
                    <center>
                        @php
                            $qrText = "Invoice ID: $invoiceId\n";

                            foreach ($invoiceItems as $item) {
                                $qrText .= "Item: {$item->itemName}, Qty: {$item->quantity}, Price: {$item->unitPrice}\n";
                            }

                            $qrText .=
                                'Total Price: TSH ' .
                                number_format($totalAmountWithoutDiscount + $vatTotal - $totalDiscount, 2);
                        @endphp

                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($qrText) !!}
                    </center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-6 mt-3 w-100">
                        <form action="{{ route('cancell.invoice') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" value="{{ $invoiceId }}" name="invoice_id" />
                            <button type="submit" id="" class="btn btn"
                                style="background-color: red; border-color: orange; color: #FFFF;">
                                Cancell Invoice
                            </button>
                            @php
                                $encryptedAutoId = Crypt::encrypt($invoiceId);
                            @endphp
                            <a href="{{ route('invoice.download', $encryptedAutoId) }}" id=""
                                class="btn btn-primary float-end" border-color: #007BFF; color: #FFFF;">
                                Download Invoice
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
