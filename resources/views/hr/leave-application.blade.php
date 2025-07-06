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
                                        <table id="basic-datatableszindex" class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>S/N</th>
                                                    <th>Leave Name</th>
                                                    <th>Days</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Attachment</th>
                                                    <th>Date Applied</th>
                                                    <th>Leave Status</th>
                                                    <th>Approval Status</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($myLeaveApplications as $application)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $application->leaveName }}</td>
                                                        <td>{{ number_format($application->days) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($application->start_date)->format('M d, Y (l)') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($application->end_date)->format('M d, Y (l)') }}
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($application->attachment != null)
                                                                <a href="{{ asset('storage/' . $application->attachment) }}"
                                                                    target="__blank" class="btn btn-primary btn-sm"><i
                                                                        class="fa fa-eye"></i></a>
                                                            @else
                                                            <button class="btn btn-secondary btn-sm">No file</button>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($application->dateApplied)->format('M d, Y (l)') }}</td>
                                                        <td class="text-center">
                                                            @php
                                                                $endDate = \Carbon\Carbon::parse($application->end_date)->format('Y-m-d');
                                                                $today = \Carbon\Carbon::today()->format('Y-m-d');
                                                            @endphp
                                                            @if ($endDate > $today)
                                                                <button class="btn btn-primary btn-sm"> <i class="fa fa-check-circle"></i> Active</button>
                                                            @endif
                                                            @if ($endDate < $today)
                                                                <button class="btn btn-danger btn-sm"> <i class="fas fa-exclamation-triangle"></i> Over</button>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($application->status == 'Pending')
                                                                <i class="fas fa-spinner fa-spin"></i> {{ $application->status }}
                                                            @endif

                                                            @if ($application->status == 'Approved')
                                                                <i class="fas fa-check-square text-primary"></i> {{ $application->status }}
                                                            @endif

                                                            @if ($application->status == 'Rejected')
                                                                <i class="fas fa-times-circle text-danger"></i> {{ $application->status }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-out-profoma" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <form action="{{ route('store.leave.applications') }}" method="POST" class="row"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-4 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Leave
                                                    Type</span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="leave_type" aria-describedby="inputGroup-sizing-default" required>
                                                    <option value="" selected disabled>--select--</option>
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
                                        <div class="col-12 mb-3">
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
