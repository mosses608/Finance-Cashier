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
                                    aria-selected="true" style="color: #007BFF;">Stock Out Receipt</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="sales-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="container p-4 border rounded bg-white shadow-sm">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h4><strong>Stock Out Receipt</strong></h4>
                                        </div>

                                        <!-- Table -->
                                        <div class="table-responsive mb-4">
                                            <table class="table table-bordered table-striped align-middle">
                                                <thead class="table-primary text-center">
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Date Due</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $staff = null;
                                                @endphp
                                                @foreach ($stockOuts as $out)
                                                @php
                                                    $staff = $out->userName;
                                                @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $out->productName }}</td>
                                                        <td class="text-center">{{ number_format($out->quantityOut) }}</td>
                                                        <td class="text-center">{{ number_format($out->price, 2) }}</td>
                                                        <td class="text-center">{{ \Carbon\Carbon::parse($out->dueDate)->format('M d, Y') }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row p-3">
                                                <span class="p-2">Staff Name: {{ $staff }}</span>
                                                <span class="p-2">Reviewer Signature: ______________________</span>
                                            </div>
                                    </div>
                                    <a href="#"
                                        class="btn btn-primary float-end mt-4"><i class="fa fa-download"></i> Download
                                        Receipt</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
