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
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-1" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">

                                    @if (count($reports) == 0)
                                        @php
                                            $today = \Carbon\Carbon::now()->format('Y-m-d');
                                        @endphp

                                        <form action="{{ route('budget.reports') }}" method="GET" class="row">
                                            <div class="row">
                                                <div class="col-5 mb-3">
                                                    <div class="input-group">
                                                        <input type="date" name="from_date" id="from_date"
                                                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control"
                                                            value="{{ old('from_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                                        <span class="input-group-text">-</span>
                                                        <input type="date" name="to_date" id="to_date"
                                                            class="form-control" value="{{ old('to_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Project</span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        name="currency" aria-describedby="inputGroup-sizing-default"
                                                        required>
                                                        <option value="" selected disabled>--project name--
                                                        </option>
                                                        @foreach ($projects as $project)
                                                            <option value="{{ $project->id }}">{{ $project->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-3 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Year</span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        name="year" aria-describedby="inputGroup-sizing-default"
                                                        required>
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
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Branch</span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        name="branch" aria-describedby="inputGroup-sizing-default"
                                                        required>
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
                                                            <td class="text-nowrap text-primary">
                                                                {{ number_format($report->amount, 2) }}
                                                            </td>
                                                            <td class="text-nowrap text-primary">
                                                                {{ number_format($report->totalAmount, 2) }}</td>
                                                            <td class="text-nowrap text-center text-secondary">
                                                                {{ number_format($percentageUsage, 2) . '%' }}</td>
                                                            <td class="text-nowrap text-primary">
                                                                {{ number_format($budgetBalance, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (count($reports) == 0)
                                                <span class="mt-3 mb-3 p-3" style="color: maroon;">Ooop! seems like there
                                                    is
                                                    nothing to display here!</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <form id="reportFilterForm" class="row">
                                        <div class="row">
                                            <div class="col-5 mb-3">
                                                <div class="input-group">
                                                    <input type="date" name="from_date" id="from_date"
                                                        max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control"
                                                        value="{{ old('from_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                                    <span class="input-group-text">-</span>
                                                    <input type="date" name="to_date" id="to_date"
                                                        class="form-control" value="{{ old('to_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                                </div>
                                            </div>
                                            <div class="col-5 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">Bank</span>
                                                    <select class="form-control" name="bank_id">
                                                        <option value="" selected disabled>--select bank--
                                                        </option>
                                                        @foreach ($banks as $bank)
                                                            <option value="{{ $bank->bankId }}">
                                                                {{ $bank->bankName . ' - ' . $bank->account_number }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2 mb-3">
                                                <div class="input-group mb-3 float-end">
                                                    <button type="submit" class="btn btn-primary">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive">
                                        <div id="reportResults"></div>
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

    <script>
        document.getElementById('reportFilterForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;
            const bankId = document.querySelector('select[name="bank_id"]').value;

            fetch(`{{ route('budget.reports') }}?from_date=${fromDate}&to_date=${toDate}&bank_id=${bankId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('reportResults').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>


@stop
