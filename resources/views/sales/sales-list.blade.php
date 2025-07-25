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
                                    aria-selected="true">All Sales List</button>
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
                                                    <th>Invoice ID</th>
                                                    <th>Amount Paid (TZS)</th>
                                                    <th>Payment Method</th>
                                                    <th>Status</th>
                                                    <th>Date Paid</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($companySales as $sale_receipt)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-primary"><strong>#{{ str_pad($sale_receipt->invoice_id, 4, '0', STR_PAD_LEFT) }}</strong>
                                                        </td>
                                                        <td>{{ number_format($sale_receipt->amount_paid, 2) }}</td>
                                                        <td class="text-center">
                                                            @if ($sale_receipt->status == 1)
                                                                <span
                                                                    class="btn btn-secondary w-100 p-1 rounded-5">{{ $sale_receipt->payment_method }}</span>
                                                            @else
                                                                <span class="btn btn-secondary w-100 p-1 rounded-5"><i
                                                                        class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                                    Pending...</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($sale_receipt->status == 0)
                                                                <span><i
                                                                        class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                                    Pending</span>
                                                            @else
                                                                <span class="text-primary"><i class="fas fa-check-circle me-1"></i> Paid</span>
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
    </script>
@stop
