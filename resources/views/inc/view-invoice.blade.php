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
                            <h4 class="card-title">
                                Invoice : <strong
                                    style="color: #0000FF;">{{ str_pad($invoiceId, 4, '0', STR_PAD_LEFT) }}</strong>
                            </h4>
                        </div>

                        @php
                            $totalAmountWithoutDiscount = 0;
                            $vatTotal = 0;
                            $totalDiscount = 0;
                            $grandTotal = 0;
                        @endphp

                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div class="table-responsive">

                                    {{-- Products Invoice --}}
                                    @if (count($invoiceItems) > 0)
                                        @php
                                            $totalDiscount = 0;
                                            $totalAmountWithDiscount = 0;
                                            $hasVAT = false;
                                        @endphp

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
                                                @foreach ($invoiceItems as $item)
                                                    @php
                                                        $itemTotal = $item->unitPrice * $item->quantity;
                                                        $discountValue = ($itemTotal * $item->discount) / 100;
                                                        $amountAfterDiscount = $itemTotal - $discountValue;

                                                        $totalDiscount += $discountValue;
                                                        $totalAmountWithDiscount += $amountAfterDiscount;

                                                        if ($hasVrn) {
                                                            $hasVAT = true;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->itemName }}</td>
                                                        <td>{{ number_format($item->unitPrice, 2) }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ $item->discount }}%</td>
                                                        <td>{{ number_format($discountValue, 2) }}</td>
                                                        <td>{{ number_format($amountAfterDiscount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                @php
                                                    $vatTotal = 0;
                                                    if ($hasVAT) {
                                                        $vatTotal = $totalAmountWithDiscount * 0.18;
                                                    }
                                                    $grandTotal = $totalAmountWithDiscount + $vatTotal;
                                                @endphp

                                                @if ($hasVAT)
                                                    <tr>
                                                        <td class="text-nowrap">VAT (18%)</td>
                                                        <td colspan="5"></td>
                                                        <td>{{ number_format($vatTotal, 2) }}</td>
                                                    </tr>
                                                @else
                                                <tr>
                                                        <td class="text-nowrap">VAT (18%)</td>
                                                        <td colspan="5"></td>
                                                        <td>{{ number_format(0, 2) }}</td>
                                                    </tr>
                                                @endif

                                                <tr class="text-nowrap">
                                                    <td><strong>Totals (TSH)</strong></td>
                                                    <td colspan="4"></td>
                                                    <td><strong>{{ number_format($totalDiscount, 2) }}</strong></td>
                                                    <td><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    @endif

                                    {{-- Service Invoice --}}
                                    @if (count($invoiceServiceItems) > 0)
                                        @php
                                            $totalDiscount = 0;
                                            $totalAmountWithDiscount = 0;
                                            $hasVAT = false;
                                        @endphp

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
                                                @foreach ($invoiceServiceItems as $item)
                                                    @php
                                                        $itemTotal = $item->unitPrice * $item->quantity;
                                                        $discountValue = ($itemTotal * $item->discount) / 100;
                                                        $amountAfterDiscount = $itemTotal - $discountValue;

                                                        $totalDiscount += $discountValue;
                                                        $totalAmountWithDiscount += $amountAfterDiscount;

                                                        if ($hasVrn) {
                                                            $hasVAT = true;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->itemName }}</td>
                                                        <td>{{ number_format($item->unitPrice, 2) }}</td>
                                                        <td>{{ number_format($item->quantity) }}</td>
                                                        <td>{{ $item->discount }}%</td>
                                                        <td>{{ number_format($discountValue, 2) }}</td>
                                                        <td>{{ number_format($amountAfterDiscount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                @php
                                                    $vatTotal = 0;
                                                    if ($hasVAT) {
                                                        $vatTotal = $totalAmountWithDiscount * 0.18;
                                                    }
                                                    $grandTotal = $totalAmountWithDiscount + $vatTotal;
                                                @endphp

                                                @if ($hasVAT)
                                                    <tr>
                                                        <td class="text-nowrap">VAT (18%)</td>
                                                        <td colspan="5"></td>
                                                        <td>{{ number_format($vatTotal, 2) }}</td>
                                                    </tr>
                                                @else
                                                <tr>
                                                        <td class="text-nowrap">VAT (18%)</td>
                                                        <td colspan="5"></td>
                                                        <td>{{ number_format(0, 2) }}</td>
                                                    </tr>
                                                @endif

                                                <tr class="text-nowrap">
                                                    <td><strong>Totals (TSH)</strong></td>
                                                    <td colspan="4"></td>
                                                    <td><strong>{{ number_format($totalDiscount, 2) }}</strong></td>
                                                    <td><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    @endif

                                    {{-- Items Out of Store --}}
                                    @if (count($itemsOutOfStore) > 0)
                                        @php
                                            $totalDiscount = 0;
                                            $totalAmountWithoutDiscount = 0;
                                        @endphp

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
                                                @foreach ($itemsOutOfStore as $item)
                                                    @php
                                                        $discountValue =
                                                            ($item->unitPrice * $item->quantity * $item->discount) /
                                                            100;
                                                        $itemTotalRaw = $item->unitPrice * $item->quantity;

                                                        $totalDiscount += $discountValue;
                                                        $totalAmountWithoutDiscount += $itemTotalRaw;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->itemName }}</td>
                                                        <td>{{ number_format($item->unitPrice, 2) }}</td>
                                                        <td>{{ number_format($item->quantity) }}</td>
                                                        <td>{{ $item->discount }}</td>
                                                        <td>{{ number_format($discountValue, 2) }}</td>
                                                        <td>{{ number_format($itemTotalRaw, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            @php
                                                $itemTotal = $totalAmountWithoutDiscount - $totalDiscount;

                                                $vatTotal = 0;
                                                if (!empty($itemsOutOfStore) && $hasVrn) {
                                                    $vatTotal = $itemTotal * 0.18;
                                                }
                                            @endphp
                                            <tfoot>
                                                @if ($vatTotal > 0)
                                                    <tr>
                                                        <td class="text-nowrap">VAT (18%)</td>
                                                        <td colspan="5"></td>
                                                        <td>{{ number_format($vatTotal, 2) }}</td>
                                                    </tr>
                                                @else
                                                <tr>
                                                        <td class="text-nowrap">VAT (18%)</td>
                                                        <td colspan="5"></td>
                                                        <td>{{ number_format(0, 2) }}</td>
                                                    </tr>
                                                @endif
                                                <tr class="text-nowrap">
                                                    <td><strong>Totals (TSH)</strong></td>
                                                    <td colspan="4"></td>
                                                    <td><strong>{{ number_format($totalDiscount, 2) }}</strong></td>
                                                    <td><strong>{{ number_format($itemTotal + $vatTotal, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    @endif

                                    <hr class="mt-5 mb-5">

                                    {{-- Transactions --}}
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr><strong>Transactions:</strong></tr>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Amount Paid (TZS)</th>
                                                <th>Payment Method</th>
                                                <th>Date Paid</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $n = 1;
                                            @endphp
                                            @if ($transaction)
                                                <tr>
                                                    <td>{{ $n++ }}</td>
                                                    <td>{{ number_format($transaction->amount_paid, 2) }}</td>
                                                    <td class="text-secondary">
                                                        <strong>{{ $transaction->payment_method }}</strong>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($transaction->status == 1)
                                                            <i class="fa fa-check-circle text-success"></i> Paid
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- QR Code --}}
                <div class="col-md-12 p-3">
                    <center>
                        @php
                            $qrText = "Invoice ID: $invoiceId\n";
                            $totalAmountWithoutDiscount = 0;
                            $totalDiscount = 0;

                            $allItems = collect($invoiceItems)->merge($invoiceServiceItems)->merge($itemsOutOfStore);

                            foreach ($allItems as $item) {
                                $unitPrice = is_numeric($item->unitPrice) ? $item->unitPrice : 0;
                                $quantity = is_numeric($item->quantity) ? $item->quantity : 0;
                                $discountPercent = is_numeric($item->discount) ? $item->discount : 0;

                                $lineTotal = $unitPrice * $quantity;
                                $discountValue = ($lineTotal * $discountPercent) / 100;

                                $qrText .=
                                    "Item: {$item->itemName}, Qty: {$quantity}, Price: " .
                                    number_format($unitPrice, 2) .
                                    "\n";

                                $totalAmountWithoutDiscount += $lineTotal;
                                $totalDiscount += $discountValue;
                            }

                            $vatTotal = 0;
                            $hasVAT = $allItems->contains(function ($item) {
                                return !empty($item->vrn);
                            });

                            if ($hasVAT) {
                                $itemTotal = $totalAmountWithoutDiscount - $totalDiscount;
                                $vatTotal = $itemTotal * 0.18;
                            }

                            $grandTotal = $totalAmountWithoutDiscount + $vatTotal - $totalDiscount;

                            $qrText .= 'Total Price: TSH ' . number_format($grandTotal, 2);
                        @endphp

                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($qrText) !!}

                    </center>
                </div>
            </div>

            @if (!$transaction)
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-6 mt-3 w-100">
                            <form action="{{ route('cancell.invoice') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" value="{{ $invoiceId }}" name="invoice_id" />
                                <button type="submit" class="btn btn"
                                    style="background-color: red; border-color: orange; color: #FFFF;">
                                    <i class="fa fa-times"></i> Cancel Invoice
                                </button>
                                @php
                                    $encryptedAutoId = Crypt::encrypt($invoiceId);
                                @endphp
                                <a href="{{ route('invoice.download', $encryptedAutoId) }}"
                                    class="btn btn-primary float-end" style="border-color: #007BFF; color: #FFFF;">
                                    <i class="fa fa-download"></i> Download Invoice
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
