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
                                        <h4 class="card-title">{{ number_format($totalProfomaInvoice) }}</h4>
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
                                        <p class="card-category">Accepted</p>
                                        <h4 class="card-title">{{ number_format($acceptedProfomaInvoice) }}</h4>
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
                                        <p class="card-category">Rejected</p>
                                        <h4 class="card-title">{{ number_format($rejectedProfomaInvoice) }}</h4>
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
                                        <i class="fas fa-box-open text-danger"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Out of Store</p>
                                        <h4 class="card-title">{{ number_format($profomaOutStore) }}</h4>
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
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Profoma Invoices From Store</button>
                                <button class="nav-link" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-out-profoma" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Profoma
                                    Invoices Out From Store</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
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
                                                @foreach ($prodomaInvoiceFromStore as $profomaInvoice)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $profomaInvoice->customerName ?? 'Unknown' }}</td>
                                                        <td>{{ number_format($profomaInvoice->amount, 2) }}</td>
                                                        <td>
                                                            @if ($profomaInvoice->statusInvoice == 'Pending')
                                                                <i class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                                {{ $profomaInvoice->statusInvoice }}
                                                            @else
                                                                <span class="text-primary"><i class="fas fa-check-circle"></i>
                                                                    {{ $profomaInvoice->statusInvoice }}</span>
                                                            @endif

                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($profomaInvoice->dateCreated)->format('M d, Y') }}
                                                        </td>
                                                        @php
                                                            $encryptedInvoiceId = Crypt::encrypt(
                                                                $profomaInvoice->profomaId,
                                                            );
                                                        @endphp
                                                        <td><a href="{{ route('profoma.invoice.view', $encryptedInvoiceId) }}"
                                                                class="btn btn-primary sm text-center"><i
                                                                    class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($prodomaInvoiceFromStore) == 0)
                                            <p class="p-3">No invoices found!</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-out-profoma" role="tabpanel"
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
                                                @foreach ($profomaInvoiceOutOfStore as $profomaOutStore)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $profomaOutStore->customerName }}</td>
                                                        <td>{{ number_format($profomaOutStore->amount, 2) }}</td>
                                                        <td>
                                                            <i class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                            {{ $profomaOutStore->profomaStatus }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($profomaOutStore->dateCreated)->format('M d, Y') }}
                                                        </td>
                                                        @php
                                                            $encryptedInvoiceuto = Crypt::encrypt(
                                                                $profomaOutStore->autoId,
                                                            );
                                                        @endphp
                                                        <td><a href="{{ route('profoma.invoice.out.store', $encryptedInvoiceuto) }}"
                                                                class="btn btn-primary sm text-center"><i
                                                                    class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($prodomaInvoiceFromStore) == 0)
                                            <p class="p-3">No invoices found!</p>
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
