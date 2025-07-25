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
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center bubble-shadow-small"
                                        style="color: orange; font-size: 4rem;">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Invoices</p>
                                        <h4 class="card-title">{{ number_format($totalInvoices) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fa-solid fa-circle-check text-success"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Paid</p>
                                        <h4 class="card-title">{{ number_format($paidInvoice) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fa-solid fa-circle-exclamation text-warning"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Unpaid</p>
                                        <h4 class="card-title">{{ number_format($unpaidInvoice) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="fa-solid fa-circle-xmark text-danger"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Cancelled</p>
                                        <h4 class="card-title">{{ number_format($cancelledInvoice) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-invoice" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Pending Invoices</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#paid-invoice" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Paid Invoices</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#cancelled-invoice" type="button" role="tab"
                                    aria-controls="nav-contact" aria-selected="false">Cancelled Invoices</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="nav-invoice" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Customer Name</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Date Created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $invoice->customerName }}</td>
                                                        <td class="text-primary">{{ number_format($invoice->amountPaid, 2) }}</td>
                                                        <td class="pending"><i
                                                                class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                            {{ $invoice->invoiceStatus }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($invoice->invoiceDate)->format('M d, Y') }}
                                                        </td>
                                                        @php
                                                            $encryptedInvoiceId = Crypt::encrypt($invoice->invoiceId);
                                                        @endphp
                                                        <td><a href="{{ route('invoice.view', $encryptedInvoiceId) }}"
                                                                class="btn btn-primary sm text-center"><i
                                                                    class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($invoices) == 0)
                                            <p class="p-3">No invoices found!</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="paid-invoice" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Bill Id</th>
                                                    <th>Customer Name</th>
                                                    <th>Amount Paid</th>
                                                    <th>Date Paid</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($paidinvoices as $paidinvoice)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>100</td>
                                                        <td>{{ $paidinvoice->customerName }}</td>
                                                        <td class="text-primary">{{ number_format($paidinvoice->amountPaid, 2) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($paidinvoice->invoiceDate)->format('M d, Y') }}
                                                        </td>
                                                        @php
                                                            $encryptedInvoiceId = Crypt::encrypt(
                                                                $paidinvoice->invoiceId,
                                                            );
                                                        @endphp
                                                        <td><a href="{{ route('invoice.view', $encryptedInvoiceId) }}"
                                                                class="btn btn-primary sm text-center"><i
                                                                    class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($paidinvoices) == 0)
                                            <p class="p-3">No paid invoices found!</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="cancelled-invoice" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Customer Name</th>
                                                    <th>Amount</th>
                                                    <th>Date Cancelled</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cancelledinvoices as $cancelledinvoice)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $cancelledinvoice->customerName }}</td>
                                                        <td class="text-primary fw-700">{{ number_format($cancelledinvoice->amountPaid, 2) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($cancelledinvoice->cancelledDate)->format('M d, Y') }}
                                                        </td>
                                                        @php
                                                            $encryptedInvoiceId = Crypt::encrypt(
                                                                $cancelledinvoice->invoiceId,
                                                            );
                                                        @endphp
                                                        <td><a href="{{ route('invoice.view', $encryptedInvoiceId) }}"
                                                                class="btn btn-primary sm text-center"><i
                                                                    class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($cancelledinvoices) == 0)
                                            <p class="p-3">No invoice cancelled!</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
