@extends('layouts.part')
@include('partials.nav-bar')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Profoma Invoice :
                                <strong style="color: #0000FF;">
                                    {{ str_pad($profomaInvoiceId, 4, '0', STR_PAD_LEFT) }}
                                </strong>
                            </h4>
                        </div>
                        @php
                            $vatTotal = 0;
                        @endphp
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div class="table-responsive">

                                    @php
                                        $totalDiscount = 0;
                                        $totalAmountWithDiscount = 0;
                                        $vatTotal = 0;
                                    @endphp

                                    {{-- Product-based Invoice Items --}}
                                    @if (count($profomaInvoiceItems) > 0)
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr><strong>Item List (Products):</strong></tr>
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
                                                @foreach ($profomaInvoiceItems as $item)
                                                    @php
                                                        $itemTotal = $item->unitPrice * $item->quantity;
                                                        $discountValue = ($itemTotal * $item->discount) / 100;
                                                        $amountAfterDiscount = $itemTotal - $discountValue;

                                                        $totalDiscount += $discountValue;
                                                        $totalAmountWithDiscount += $amountAfterDiscount;
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
                                                @if (!empty($item->vrn))
                                                    @php
                                                        $vatTotal = $totalAmountWithDiscount * 0.18;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-nowrap">VAT (18%)</td>
                                                        <td colspan="5"></td>
                                                        <td>{{ number_format($vatTotal, 2) }}</td>
                                                    </tr>
                                                @endif
                                                <tr class="text-nowrap">
                                                    <td><strong>Totals (TSH)</strong></td>
                                                    <td colspan="4"></td>
                                                    <td><strong>{{ number_format($totalDiscount, 2) }}</strong></td>
                                                    <td><strong>{{ number_format($totalAmountWithDiscount + $vatTotal, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    @endif

                                    {{-- Service-based Invoice Items --}}
                                    @if (count($serviceProfomas) > 0)
                                        @php
                                            $serviceTotalDiscount = 0;
                                            $serviceAmountWithDiscount = 0;
                                            $serviceVatTotal = 0;
                                        @endphp

                                        <table class="table table-bordered table-striped mt-4">
                                            <thead class="table-light">
                                                <tr><strong>Item List (Services):</strong></tr>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Service Name</th>
                                                    <th>Price</th>
                                                    <th>Discount (%)</th>
                                                    <th>Discount Value</th>
                                                    <th>Quantity</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($serviceProfomas as $item)
                                                    @php
                                                        $itemTotal = $item->unitPrice * $item->quantity;
                                                        $discountValue = ($itemTotal * $item->discount) / 100;
                                                        $amountAfterDiscount = $itemTotal - $discountValue;

                                                        $serviceTotalDiscount += $discountValue;
                                                        $serviceAmountWithDiscount += $amountAfterDiscount;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->itemName }}</td>
                                                        <td>{{ number_format($item->unitPrice, 2) }}</td>
                                                        <td>{{ $item->discount }}%</td>
                                                        <td>{{ number_format($discountValue, 2) }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($amountAfterDiscount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                @if (!empty($item->vrn))
                                                    @php
                                                        $serviceVatTotal = $serviceAmountWithDiscount * 0.18;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-nowrap">VAT (18%)</td>
                                                        <td colspan="5"></td>
                                                        <td>{{ number_format($serviceVatTotal, 2) }}</td>
                                                    </tr>
                                                @endif
                                                <tr class="text-nowrap">
                                                    <td><strong>Totals (TSH)</strong></td>
                                                    <td colspan="3"></td>
                                                    <td><strong>{{ number_format($serviceTotalDiscount, 2) }}</strong></td>
                                                    <td></td>
                                                    <td><strong>{{ number_format($serviceAmountWithDiscount + $serviceVatTotal, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
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
                                            {{-- Transactions will go here --}}
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No transactions available.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="col-md-12 p-3">
                        <center>
                            @php
                                $qrText = "Invoice ID: $profomaInvoiceId\n\n";

                                if (count($serviceProfomas) > 0) {
                                    foreach ($serviceProfomas as $item) {
                                        $qrText .= "Item: {$item->itemName}, Price: {$item->unitPrice}\n";
                                    }
                                }

                                if (count($profomaInvoiceItems) > 0) {
                                    foreach ($profomaInvoiceItems as $item) {
                                        $qrText .= "Item: {$item->itemName}, Qty: {$item->quantity}, Price: {$item->unitPrice}\n";
                                    }
                                }

                                $qrText .=
                                    "\nTotal Price + VAT(18%): TSH " .
                                    number_format($totalAmountWithDiscount - $totalDiscount + $vatTotal, 2);
                            @endphp

                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($qrText) !!}
                        </center>
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if (!$profomaAccepted)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-6 mt-3 w-100">
                                <form action="{{ route('profoma.cancell.invoice') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="profoma_invoice_id" value="{{ $profomaInvoiceId }}" />
                                    <button type="submit" class="btn"
                                        style="background-color: red; border-color: orange; color: #FFF;">
                                        Cancel Profoma
                                    </button>

                                    @php
                                        $encryptedPrpfomaId = Crypt::encrypt($profomaInvoiceId);
                                    @endphp
                                    <a href="{{ route('download.profoma', $encryptedPrpfomaId) }}"
                                        class="btn btn-primary float-end" style="border-color: #007BFF; color: #FFF;">
                                        Download Profoma
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-4">
                            @php
                                $encryptedPrpfomaId = Crypt::encrypt($profomaInvoiceId);
                            @endphp
                            <a href="{{ route('download.profoma', $encryptedPrpfomaId) }}"
                                class="btn btn-primary float-start" style="border-color: #007BFF; color: #FFF;">
                                <i class="fa fa-download"></i> Download Profoma
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
