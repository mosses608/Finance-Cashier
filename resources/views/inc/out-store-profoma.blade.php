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
                            <h4 class="card-title">Profoma Invoice :
                                <strong style="color: #0000FF;">
                                    {{ str_pad($profomaAutoId, 4, '0', STR_PAD_LEFT) }}
                                </strong>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div class="table-responsive">
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
                                        @php
                                            $totalDiscount = 0;
                                            $totalAmountWithDiscount = 0;
                                        @endphp
                                        <tbody>
                                            @foreach ($profomaInvoiceItems as $item)
                                                @php
                                                    $totalDiscount += $item->quantity * $item->discount;
                                                    $totalAmountWithDiscount +=
                                                        $item->unitPrice * $item->quantity;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->itemName }}</td>
                                                    <td>{{ number_format($item->unitPrice, 2) }}</td>
                                                    <td>{{ number_format($item->quantity) }}</td>
                                                    <td>{{ $item->discount }}</td>
                                                    <td>{{ number_format($item->quantity * $item->discount, 2) }}</td>
                                                    <td>{{ number_format($item->unitPrice * $item->quantity) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totals (TSH)</strong></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><strong>
                                                        {{ number_format($totalDiscount, 2) }}</strong>
                                                </td>
                                                <td><strong>
                                                        {{ number_format($totalAmountWithDiscount - $totalDiscount, 2) }}</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                        </tbody>
                                    </table>
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
                            $qrText = "Invoice ID: $profomaAutoId\n";

                            foreach ($profomaInvoiceItems as $item) {
                                $qrText .= "Item: {$item->itemName}, Qty: {$item->quantity}, Price: {$item->unitPrice}\n";
                            }

                            $qrText .=
                                'Total Price: TSH ' . number_format($totalAmountWithDiscount - $totalDiscount, 2);
                        @endphp

                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($qrText) !!}
                    </center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-6 mt-3 w-100">
                        <form action="{{ route('profoma.cancell.invoice') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" value="{{ $profomaAutoId }}" name="profoma_invoice_id" />
                            <button type="submit" id="" class="btn btn"
                                style="background-color: red; border-color: orange; color: #FFFF;">
                                Cancell Profoma
                            </button>
                            @php
                                $encryptedPrpfomaAutoId = Crypt::encrypt($profomaAutoId);
                            @endphp
                            <a href="{{ route('download.profoma.out', $encryptedPrpfomaAutoId) }}"
                                class="btn btn-primary float-end" border-color: #007BFF; color: #FFFF;">
                                Download Profoma
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
