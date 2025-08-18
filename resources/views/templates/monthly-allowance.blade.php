@extends('layouts.part')
@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="p-3 fs-5">Allowance Types</h4>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Allowance List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Register Allowance</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>S/N</th>
                                                    <th>Allowance Name</th>
                                                    <th>Default Amount</th>
                                                    <th>Date Created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                {{-- @foreach ($companyAllowances as $allowance)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $allowance->name }}</td>
                                                        <td>{{ number_format($allowance->default_amount, 2) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($allowance->created_at)->format('M d, Y') }}
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#staticBackdrop-{{ $allowance->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>

                                                            <div class="modal fade" id="staticBackdrop-{{ $allowance->id }}"
                                                                data-bs-backdrop="static" data-bs-keyboard="false"
                                                                tabindex="-1" aria-labelledby="staticBackdropLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="staticBackdropLabel">Edit Allowance -
                                                                                <span
                                                                                    class="text-primary">{{ $allowance->name }}</span>
                                                                            </h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <form action="{{ route('update.allowance') }}"
                                                                            method="POST" class="modal-body">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden" name="autoId"
                                                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($allowance->id) }}"
                                                                                id="autoId">
                                                                            <div class="row">
                                                                                <div class="col-6 mb-3">
                                                                                    <div class="input-group mb-3">
                                                                                        <span
                                                                                            class="input-group-text">Name</span>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="name" id="name"
                                                                                            value="{{ $allowance->name }}"
                                                                                            required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-6 mb-3">
                                                                                    <div class="input-group mb-3">
                                                                                        <span
                                                                                            class="input-group-text">Amount</span>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="default_amount"
                                                                                            id="default_amount"
                                                                                            value="{{ number_format($allowance->default_amount, 2) }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 mt-3">
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary float-end">Update</button>
                                                                                </div>
                                                                        </form>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">

                                    <form action="{{ route('store.allowance') }}" method="POST" class="row">
                                        @csrf
                                        <div class="col-5 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Name</span>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="allowance type" required>
                                            </div>
                                        </div>

                                        <div class="col-5 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Amount</span>
                                                <input type="text" class="form-control" name="default_amount"
                                                    id="default_amount" placeholder="default amount">
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <div class="input-group mb-3">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#basic-datatables');
        new DataTable('#basic-datatables1');
        new DataTable('#basic-datatables10');
        new DataTable('#basic-datatables11');
    </script>
@stop
