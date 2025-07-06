@extends('layouts.part')
@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            {{-- <div class="row">
                                <div class="col-6">
                                    <h4 class="p-3 fs-5">Budget Reports</h4>
                                </div>
                               
                            </div> --}}
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Program Expense Budget</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Transactions Report</button>
                                {{-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Regulators Report</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-bank-data" type="button" role="tab"
                                    aria-controls="nav-profile" aria-selected="false">Bank Report</button> --}}
                                {{-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Bank Branch Report</button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-1" id="nav-tabContent">

                                @if (count($reports) == 0)
                                    <!-- Invoice Tab -->
                                    @php
                                        $today = \Carbon\Carbon::now()->format('Y-m-d');
                                    @endphp

                                    <form action="{{ route('budget.reports') }}" method="GET" class="row">
                                        <div class="row">
                                            <div class="col-4 mb-3">
                                                {{-- <label for="from_date" class="form-label">Date Range</label> --}}
                                                <div class="input-group">
                                                    <input type="date" name="from_date" id="from_date"
                                                        class="form-control" value="{{ old('from_date', $today) }}">
                                                    <span class="input-group-text">-</span>
                                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                                        value="{{ old('to_date', $today) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Project</span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="currency" aria-describedby="inputGroup-sizing-default" required>
                                                    <option value="" selected disabled>--project name--
                                                    </option>
                                                    @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-3 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Year</span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="year" aria-describedby="inputGroup-sizing-default" required>
                                                    <option value="" selected disabled>--budget year--
                                                    </option>
                                                    @foreach ($budgetYrs as $yr)
                                                        <option value="{{ $yr->year }}">{{ $yr->year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-3 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Branch</span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="branch" aria-describedby="inputGroup-sizing-default" required>
                                                    <option value="" selected disabled>--branch--
                                                    </option>
                                                    @foreach ($branch as $br)
                                                        <option value="{{ $br->branch }}">{{ $br->branch }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-2 mb-3">
                                            <div class="input-group mb-3 float-end">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>

                                    </form>
                                @endif

                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    @if (isset($reports) && count($reports) != 0)
                                        <div class="table-responsive">
                                            <table id="basic-datatables" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th class="text-nowrap">Cost Type</th>
                                                        <th class="text-nowrap">Sub-Budget Code</th>
                                                        <th class="text-nowrap">Budget Amount</th>
                                                        <th class="text-nowrap">Amount Used</th>
                                                        <th class="text-nowrap">Percentage Usage</th>
                                                        <th class="text-nowrap">Budget Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($reports as $report)
                                                        @php
                                                            $percentageUsage =
                                                                ($report->totalAmount / $report->amount) * 100;
                                                            $budgetBalance = $report->amount - $report->totalAmount;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td class="text-nowrap">{{ $report->costType }}</td>
                                                            <td class="text-nowrap">{{ $report->sub_budget_code }}</td>
                                                            <td class="text-nowrap">{{ number_format($report->amount, 2) }}
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{ number_format($report->totalAmount, 2) }}</td>
                                                            <td class="text-nowrap text-center">
                                                                {{ number_format($percentageUsage, 2) . '%' }}</td>
                                                            <td class="text-nowrap">{{ number_format($budgetBalance, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (count($reports) == 0)
                                                <span class="mt-3 mb-3 p-3" style="color: maroon;">Ooop! seems like there is
                                                    nothing to display here!</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>


                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables1" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>TXN-Ref-No</th>
                                                    <th>Amount</th>
                                                    <th>Purpose</th>
                                                    <th>Created By</th>
                                                    <th>Due Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-new-data" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <div class="table-responsive">
                                            <table id="basic-datatables11"
                                                class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Regulator Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Address</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="nav-bank-data" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <div class="table-responsive">
                                            <table id="basic-datatables10"
                                                class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Bank Name</th>
                                                        <th>Branch Name</th>
                                                        <th>Acc Name</th>
                                                        <th>Acc Number</th>
                                                        <th>Address</th>
                                                        <th>Box</th>
                                                        <th>Code</th>
                                                        <th>Phone</th>
                                                        <th>Region</th>
                                                        <th>Action</th>
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
