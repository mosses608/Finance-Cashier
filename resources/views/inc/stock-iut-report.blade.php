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
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Stock Out Report</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-1" id="nav-tabContent">
                                <form action="{{ route('stock.out.report') }}" method="GET" class="row mb-3">
                                    <div class="col-md-4">
                                        {{-- <label for="date_from" class="form-label">From</label> --}}
                                        <input type="date" id="date_from"
                                            max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                            value="{{ old('date_from', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                            name="date_from" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        {{-- <label for="date_to" class="form-label">To</label> --}}
                                        <input type="date" id="date_to"
                                            value="{{ old('date_to', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                            name="date_to" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary float-end" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" id="submit-search"
                                            onclick="submitSearch()">Search</button>
                                    </div>
                                </form>

                                {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ...
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>S/N</th>
                                                    <th>Serial No</th>
                                                    <th>Item Name</th>
                                                    <th>Qty Out</th>
                                                    <th>Staff</th>
                                                    <th>Due Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($reports as $stock)
                                                    @php
                                                        $status = $stock->status;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $n++ }}
                                                        </td>
                                                        <td>{{ $stock->serialNo ?? '####' }}</td>
                                                        <td>{{ $stock->productName }}</td>
                                                        <td>{{ number_format($stock->quantityOut, 0) }}</td>
                                                        <td>{{ $stock->userName }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($stock->dueDate)->format('M d, Y') }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            @if ($status == null)
                                                                <span class="text-warning">
                                                                    Pending</span>
                                                            @endif
                                                            @if ($status == 1)
                                                                <span class="text-success">
                                                                    Approved</span>
                                                            @endif
                                                            @if ($status == 2)
                                                                <span class="text-danger">
                                                                    Rejected</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-md-6">
                                        @if ($reports->isEmpty())
                                            <span class="text-warning">No records found from <strong
                                                    class="text-primary">{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</strong>
                                                to <strong
                                                    class="text-primary">{{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}
                                                </strong></span>
                                        @endif
                                    </div>
                                    @php
                                        $dataReport = [
                                            'from' => $fromDate,
                                            'to' => $toDate,
                                        ];

                                        $validData = \Illuminate\Support\Facades\Crypt::encrypt(
                                            json_encode($dataReport),
                                        );
                                    @endphp
                                    <div class="col-md-6">
                                        <a href="{{ route('download.stock.out.report', $validData) }}"
                                            class="btn btn-primary float-end"><i class="fa fa-download"></i>
                                            Download Report</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('change', '.product-select', function() {
                const selectedOption = $(this).find('option:selected');

                const availableQuantity = selectedOption.data('available-quantity');
                const sellingPrice = selectedOption.data('selling-price');

                const container = $(this).closest('.row');

                container.find('.available-quantity').val(availableQuantity);
                container.find('.selling-price').val(sellingPrice);
            });

            $('.select2').select2({
                placeholder: '--select product--',
                width: '100%'
            });
        });
    </script>

    <style>
        .blink {
            animation: blink-animation 1.5s steps(2, start) infinite;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }
    </style>
@stop
