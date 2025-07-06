@extends('layouts.part')
@include('partials.nav-bar')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-3">
                        <div class="card-header">
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Leave Application Reports</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('staff.leave.reports') }}" method="GET" class="row">
                                <div class="col-3">
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">From</span>
                                        <input type="date" class="form-control text-primary" name="searchFrom"
                                            value="{{ old('searchFrom', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" aria-label="Username"
                                            aria-describedby="addon-wrapping">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">To</span>
                                        <input type="date" class="form-control text-primary" name="searchTo"
                                            value="{{ old('searchTo', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                            aria-describedby="addon-wrapping">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">Staff</span>
                                        <select class="form-control text-primary" name="staff"
                                            aria-describedby="addon-wrapping">
                                            <option value="" selected disabled>--select staff--</option>
                                            <option value="">all</option>
                                            @foreach ($staffs as $staff)
                                                <option value="{{ $staff->id }}">
                                                    {{ $staff->first_name . ' ' . $staff->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-primary float-start">Search</button>
                                    <button type="button" class="btn btn-secondary float-end"> Download</button>
                                </div>
                            </form>
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatablesabbc" class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>S/N</th>
                                                    <th>Staff Names</th>
                                                    <th>Phone</th>
                                                    <th>Department</th>
                                                    <th>Leave Name</th>
                                                    <th>Days</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Date Applied</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($leaveApplicationReportData as $application)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>
                                                            <strong class="text-primary text-nowrap">
                                                                {{ $application->fName . ' ' . $application->lName }}
                                                            </strong>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $application->phone }}</strong>
                                                        </td>
                                                        <td>
                                                            {{ $application->departmentName }}
                                                        </td>
                                                        <td>{{ $application->leaveName }}</td>
                                                        <td>{{ number_format($application->days) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($application->start_date)->format('M d, Y (l)') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($application->end_date)->format('M d, Y (l)') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($application->dateApplied)->format('M d, Y (l)') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
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
    <script>
        new DataTable("#basic-datatablesabbc");
        new DataTable("#basic-datatableszindexy");
    </script>
@endsection
