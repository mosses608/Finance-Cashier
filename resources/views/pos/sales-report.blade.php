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
                                    aria-selected="true">POS Sales Reports</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pos.sales.report') }}" method="GET" class="row">
                                <div class="col-3">
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">From</span>
                                        <input type="date" class="form-control text-primary" name="from"
                                            value="{{ old('searchFrom', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                            max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" aria-label="Username"
                                            aria-describedby="addon-wrapping">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">To</span>
                                        <input type="date" class="form-control text-primary" name="to"
                                            value="{{ old('searchTo', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                            aria-describedby="addon-wrapping">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary float-start">Search</button>
                                    @php

                                    @endphp
                                    <a role="button" href="#" class="btn btn-secondary float-end"> Download</a>
                                </div>
                            </form>
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="sales-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatableszxz" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Ref ID</th>
                                                    <th class="text-nowrap">Product SN</th>
                                                    <th class="text-nowrap">Product Name</th>
                                                    <th class="text-nowrap">Customer Phone</th>
                                                    <th class="text-nowrap">Due Date</th>
                                                    <th class="text-nowrap">Amount Paid</th>
                                                    {{-- <th>Date Paid</th>
                                                    <th>Amount Paid</th> --}}
                                                    {{-- <th>Action</th> --}}
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($orders as $sale)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $sale->referenceId }}</td>
                                                        <td>{{ $sale->serialNo }}</td>
                                                        <td>{{ $sale->productName }}</td>
                                                        <td>{{ $sale->customerPhone }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($sale->saleDate)->format('M d, Y') }}
                                                        </td>
                                                        <td>{{ number_format($sale->amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            @if (count($orders) > 0)
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="6" class="text-primary"><strong> Total Amount
                                                                (TZS)</strong>
                                                        </td>
                                                        <td><strong>{{ number_format(0, 2) }}</strong></td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                        @if ($orders->isEmpty() )
                                            <span class="text-danger mt-3 mb-3">No data found!</span>
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
@stop
