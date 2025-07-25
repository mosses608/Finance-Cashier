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
                                    <h4 class="p-3 fs-5">Salary Advance</h4>
                                </div>
                                {{-- <div class="col-6">
                                    <button class="btn btn-secondary btn-sm float-end mt-3" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"><i class="fa fa-plus"></i> Add New Branch</button>
                                </div> --}}
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Salary Adv Applications</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Approved Applications</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-create" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Apply For Salary Advance</button>
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
                                                    <th>Project</th>
                                                    <th>Budget Year</th>
                                                    <th>Staff</th>
                                                    <th>Amount</th>
                                                    <th>Due Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($salaryAdvances as $sa)
                                                    <tr class="text-nowrap">
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $sa->project }}</td>
                                                        <td>{{ $sa->year }}</td>
                                                        <td>{{ $sa->first_name . ' ' . $sa->last_name }}</td>
                                                        <td>{{ number_format($sa->amount, 2) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($sa->date)->format('M d, Y') }}</td>
                                                        <td>{{ $sa->month }} <sup class="text-primary">months</sup></td>
                                                        <td>{{ $sa->status . ' ... ' }}</td>
                                                        <td><a href="#" class="btn btn-primary btn-sm"><i
                                                                    class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($salaryAdvances) === 0)
                                            <span class="p-3">No data found!</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>S/N</th>
                                                    <th>Project</th>
                                                    <th>Budget Year</th>
                                                    <th>Staff</th>
                                                    <th>Amount</th>
                                                    <th>Due Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($approvedSalaryAdvances as $sa)
                                                    <tr class="text-nowrap">
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $sa->project }}</td>
                                                        <td>{{ $sa->year }}</td>
                                                        <td>{{ $sa->first_name . ' ' . $sa->last_name }}</td>
                                                        <td>{{ number_format($sa->amount, 2) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($sa->date)->format('M d, Y') }}</td>
                                                        <td>{{ $sa->month }} <sup class="text-primary">months</sup></td>
                                                        <td>{{ $sa->status . ' ... ' }}</td>
                                                        <td><a href="#" class="btn btn-primary btn-sm"><i
                                                                    class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($approvedSalaryAdvances) === 0)
                                            <span class="p-3">No data found!</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-create" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <form action="{{ route('apply.salary.advance') }}" method="POST" id="searchForm"
                                        class="row" enctype="multipart/form-data">
                                        @csrf

                                        @php
                                            $today = \Carbon\Carbon::now()->format('Y-m-d');
                                            $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
                                        @endphp

                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Date</span>
                                                <input type="date" class="form-control" name="date" id="date"
                                                    required min="{{ $today }}" max="{{ $endOfMonth }}"
                                                    value="{{ old('date', $today) }}">
                                            </div>
                                        </div>

                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Amount</span>
                                                <input type="number" class="form-control" name="amount" id="amount"
                                                    required value="{{ old('amount') }}" placeholder="amount">
                                            </div>
                                        </div>

                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Repay Time</span>
                                                <select class="form-control" name="month" id="month" required>
                                                    <option value="" selected disabled>--select time--</option>
                                                    @foreach ($months as $num => $name)
                                                        <option value="{{ $num }}">{{ $num }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Staff</span>
                                                <select class="form-control" name="staff_id" id="staff_id" required>
                                                    <option value="" selected disabled>--staff name--</option>
                                                    @foreach ($staffs as $staff)
                                                        <option value="{{ $staff->id }}">
                                                            {{ $staff->first_name . ' ' . $staff->last_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Year</span>
                                                <select class="form-control" name="year" id="year" required>
                                                    <option value="" selected disabled>--budget year--</option>
                                                    @foreach ($budgetData as $yr)
                                                        <option value="{{ $yr->budget_year }}">{{ $yr->budget_year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Project</span>
                                                <select class="form-control" name="project" id="project" required>
                                                    <option value="" selected disabled>--project--</option>
                                                    @foreach ($budgetData as $project)
                                                        <option value="{{ $project->project_name }}">
                                                            {{ $project->project_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                {{-- <span class="input-group-text">Attachment</span> --}}
                                                <input type="file" class="form-control" name="attachment"
                                                    id="attachment" accept="application/pdf">
                                            </div>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <div class="input-group mb-3">
                                                <textarea name="description" class="form-control" id="" placeholder="short descrption..."></textarea>
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <div class="input-group mb-3">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div id="data-result" class="mt-0 mb-2">
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
