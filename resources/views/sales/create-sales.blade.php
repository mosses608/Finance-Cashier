@extends('layouts.part')
@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <div class="text-primary"><strong><x-messages /></strong></div>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#sales-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Issued Purchase Orders</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#new-sales" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Issue New Purchase Order</button>
                                {{-- <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Profoma Out From Store</button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="sales-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatableszxz" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Purchase Order No</th>
                                                    <th>Invoice Number</th>
                                                    <th>Amount Paid (TZS)</th>
                                                    <th>Supplier</th>
                                                    <th>Status</th>
                                                    <th>Date Paid</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($currentDaySales as $sale_receipt)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-primary text-center">
                                                            #{{ str_pad($sale_receipt->po_number, 4, '0', STR_PAD_LEFT) }}
                                                        </td>
                                                        <td class="text-primary">
                                                            <strong>#{{ str_pad($sale_receipt->invoice_id, 4, '0', STR_PAD_LEFT) }}</strong>
                                                        </td>
                                                        <td>{{ number_format($sale_receipt->amount_paid, 2) }}</td>
                                                        <td class="text-center">
                                                            <span
                                                                class="btn btn-secondary w-100 p-1 rounded-5">{{ $sale_receipt->stakeholder }}
                                                                - <strong
                                                                    class="text-primary">{{ $sale_receipt->phoneNumber }}</strong></span>

                                                        </td>
                                                        <td class="text-center">
                                                            @if ($sale_receipt->status == 0)
                                                                <span><i
                                                                        class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                                    Pending</span>
                                                            @else
                                                                <span class="text-primary"><i
                                                                        class="fas fa-check-circle me-1"></i> Paid</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($sale_receipt->status == 0)
                                                                <span><i
                                                                        class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                                    Pending</span>
                                                            @else
                                                                {{ \Carbon\Carbon::parse($sale_receipt->updated_at)->format('M d, Y') }}
                                                            @endif
                                                        </td>
                                                        @php
                                                            $encryptedSaleId = Crypt::encrypt($sale_receipt->autoId);
                                                        @endphp
                                                        <td class="text-center">
                                                            <a href="{{ route('sale.receipt', $encryptedSaleId) }}"
                                                                class="btn btn-primary rounded-3"><i
                                                                    class="fa-solid fa-receipt"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($currentDaySales) == 0)
                                            <p class="p-3">No purchase orders issued today!</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- New Sales Tab -->
                                <div class="tab-pane fade" id="new-sales" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <div class="card shadow-sm border-0 rounded-3">
                                        <div class="card-header bg-primary text-white p-2 rounded-1">
                                            <h5 class="mb-0 sm">Issue Purchase Order</h5>
                                        </div>

                                        <div class="card-body">
                                            <form id="create-sale-form" method="GET"
                                                action="{{ route('create.new.sales') }}">
                                                <div class="row justify-content-center">
                                                    <div class="col-8 mb-3">
                                                        <label for="invoice_id" class="form-label d-flex">
                                                            <strong>Purchase Order Number</strong>
                                                        </label>
                                                        <div class="input-group">
                                                            <div class="form-floating flex-grow-1">
                                                                <input type="number" id="invoice_id" name="invoice_id"
                                                                    class="form-control">
                                                                <label for="invoice_id">Enter purchase order number</label>
                                                            </div>
                                                            <button type="submit" id="fetch-invoice"
                                                                class="btn btn-secondary">Fetch</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- Result container -->
                                            <div id="invoice-result" class="mt-4 mb-2">
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
    </div>
    <script>
        new DataTable("#basic-datatableszxz");

        document.getElementById('create-sale-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const invoiceId = document.getElementById('invoice_id').value;
            const url = this.action + '?invoice_id=' + encodeURIComponent(invoiceId);

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('invoice-result').innerHTML = data.html;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>


@stop
