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
                                    aria-selected="true">Leave Applications List</button>
                                <button class="nav-link" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-out-profoma" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Apply For Leave
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
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
                                                @foreach ($leaveApplications as $application)
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

                                <div class="tab-pane fade" id="nav-out-profoma" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <form action="{{ route('staff.leave.applications') }}" method="POST" class="row"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Employee
                                                    </span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="staff_id" aria-describedby="inputGroup-sizing-default" required>
                                                    <option value="" selected disabled>--select employee--</option>
                                                    @foreach ($employees as $empl)
                                                        <option value="{{ $empl->id }}">{{ $empl->first_name . ' ' .  $empl->last_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Leave
                                                    Type</span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="leave_type" aria-describedby="inputGroup-sizing-default" required>
                                                    <option value="" selected disabled>--select type--</option>
                                                    @foreach ($leaveTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Start
                                                    Date</span>
                                                <input type="date" class="form-control" aria-label="Sizing example input"
                                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="start_date"
                                                    value="{{ old('start_date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                    aria-describedby="inputGroup-sizing-default">
                                            </div>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">End
                                                    Date</span>
                                                <input type="date" class="form-control" aria-label="Sizing example input"
                                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="end_date"
                                                    value="{{ old('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                    aria-describedby="inputGroup-sizing-default">
                                            </div>
                                        </div>
                                        <div class="col-8 mb-3">
                                            <div class="mb-3">
                                                <input type="file" name="attachment" class="form-control" id="attachment"
                                                    accept="application/pdf">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="mb-3">
                                                <textarea class="form-control" name="reason" id="exampleFormControlTextarea1"
                                                    placeholder="reason for leave application"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary">Submit Application</button>
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
        new DataTable("#basic-datatableszindex");
    </script>
@endsection
