@extends('layouts.part')
@include('partials.nav-bar')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Accept Profoma Invoice</button>
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
                                                    <th>Invoice Id</th>
                                                    <th>Customer Name</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Date Created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($profomaInvoices as $invoice)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td class="text-primary">
                                                            <strong>#{{ str_pad($invoice->invoiceProfomaId, 4, '0', STR_PAD_LEFT) }}</strong>
                                                        </td>
                                                        <td>{{ $invoice->name }}</td>
                                                        <td>{{ number_format($invoice->amount, 2) }}</td>
                                                        <td>
                                                            <span class="btn btn-secondary w-100 p-1 rounded-5"><i
                                                                    class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                            </span>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($invoice->dateDue)->format('M d, Y') }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal-{{ $invoice->invoiceProfomaId }}"><i
                                                                    class="fas fa-check"></i></button>

                                                            <div class="modal fade"
                                                                id="exampleModal-{{ $invoice->invoiceProfomaId }}"
                                                                tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <form action="{{ route('accept.profoma.invoice') }}"
                                                                        method="POST" class="modal-content">
                                                                        @method('PUT')
                                                                        @csrf
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="exampleModalLabel">Accept Invoice
                                                                                <strong
                                                                                    class="text-primary">#{{ str_pad($invoice->invoiceProfomaId, 4, '0', STR_PAD_LEFT) }}</strong>
                                                                            </h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="invoiceId"
                                                                                id=""
                                                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($invoice->invoiceId) }}">
                                                                            <input type="hidden" name="profomaId"
                                                                                id=""
                                                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($invoice->invoiceProfomaId) }}">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" value="1" required
                                                                                    id="checkDefault">
                                                                                <label class="form-check-label"
                                                                                    for="checkDefault">
                                                                                    please check this box to accept this
                                                                                    profoma invoice...
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary btn-sm">Accept
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                {{-- PROFOMA INVOICE OUT OF STORE --}}
                                                @foreach ($profomaInvoicesOutStore as $invoice)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td class="text-primary">
                                                            <strong>#{{ str_pad($invoice->invoiceProfomaId, 4, '0', STR_PAD_LEFT) }}</strong>
                                                        </td>
                                                        <td>{{ $invoice->name }}</td>
                                                        <td>{{ number_format($invoice->amount, 2) }}</td>
                                                        <td>
                                                            <span class="btn btn-secondary w-100 p-1 rounded-5"><i
                                                                    class="fas fa-spinner fa-spin text-warning me-2"></i>
                                                            </span>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($invoice->dateDue)->format('M d, Y') }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal-{{ $invoice->invoiceProfomaId }}"><i
                                                                    class="fas fa-check"></i></button>

                                                            <div class="modal fade"
                                                                id="exampleModal-{{ $invoice->invoiceProfomaId }}"
                                                                tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <form action="{{ route('accept.profoma.outstore.invoice') }}"
                                                                        method="POST" class="modal-content">
                                                                        @method('PUT')
                                                                        @csrf
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="exampleModalLabel">Accept Invoice
                                                                                <strong
                                                                                    class="text-primary">#{{ str_pad($invoice->invoiceProfomaId, 4, '0', STR_PAD_LEFT) }}</strong>
                                                                            </h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="invoiceId"
                                                                                id=""
                                                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($invoice->invoiceId) }}">
                                                                            <input type="hidden" name="profomaId"
                                                                                id=""
                                                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($invoice->invoiceProfomaId) }}">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" value="1" required
                                                                                    id="checkDefault">
                                                                                <label class="form-check-label"
                                                                                    for="checkDefault">
                                                                                    please check this box to accept this
                                                                                    profoma invoice...
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary btn-sm">Accept
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    @if (count($profomaInvoices) == 0 || count($profomaInvoicesOutStore) == 0)
                                        <span class="p-2 mt-3">No invoice to approve here!</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
