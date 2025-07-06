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
                                    aria-selected="true">My Applications List</button>
                                <button class="nav-link" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-out-profoma" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Approved Adjustment Applications
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
                                                    <th>Adjustment</th>
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
                                                        <td>{{ \Carbon\Carbon::parse($application->dateApplied)->format('M d, Y (l)') }}
                                                        </td>
                                                        <td class="text-center">
                                                            @php
                                                                $endDate = \Carbon\Carbon::parse(
                                                                    $application->end_date,
                                                                )->format('Y-m-d');
                                                                $today = \Carbon\Carbon::today()->format('Y-m-d');
                                                            @endphp
                                                            @if ($endDate > $today)
                                                                <button class="btn btn-primary btn-sm"> <i
                                                                        class="fa fa-check-circle"></i> Active</button>
                                                            @endif
                                                            @if ($endDate < $today)
                                                                <button class="btn btn-danger btn-sm"> <i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    Over</button>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($application->status == 'Pending')
                                                                <i class="fas fa-spinner fa-spin"></i>
                                                                {{ $application->status }}
                                                            @endif

                                                            @if ($application->status == 'Approved')
                                                                <i class="fas fa-check-square text-primary"></i>
                                                                {{ $application->status }}
                                                            @endif

                                                            @if ($application->status == 'Rejected')
                                                                <i class="fas fa-times-circle text-danger"></i>
                                                                {{ $application->status }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#staticBackdrop-{{ $application->leaveId }}">Request
                                                                Adjustment</button>

                                                            <form action="{{ route('apply.leave.adjust') }}"
                                                                method="POST" class="modal fade"
                                                                id="staticBackdrop-{{ $application->leaveId }}"
                                                                data-bs-backdrop="static" data-bs-keyboard="false"
                                                                tabindex="-1" aria-labelledby="staticBackdropLabel"
                                                                aria-hidden="true">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5 text-primary"
                                                                                id="staticBackdropLabel">Apply For Leave
                                                                                Adjustment
                                                                            </h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="row modal-body">
                                                                            <input type="hidden" name="leave_id"
                                                                                id=""
                                                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($application->leaveId) }}">
                                                                            <div class="col-12 mb-3">
                                                                                <input type="number" class="form-control"
                                                                                    name="adjusted_days" id=""
                                                                                    placeholder="enter number of days"
                                                                                    required>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <hr>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <button type="submit" id=""
                                                                                    class="btn btn-primary btn-sm float-end">Send
                                                                                    Application
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-out-profoma" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatableszindexy" class="display table table-striped table-hover">
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
                                                    <th>Adjustment</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($approvedAdjustments as $application)
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
                                                        <td>{{ \Carbon\Carbon::parse($application->dateApplied)->format('M d, Y (l)') }}
                                                        </td>
                                                        <td class="text-center">
                                                            @php
                                                                $endDate = \Carbon\Carbon::parse(
                                                                    $application->end_date,
                                                                )->format('Y-m-d');
                                                                $today = \Carbon\Carbon::today()->format('Y-m-d');
                                                            @endphp
                                                            @if ($endDate > $today)
                                                                <button class="btn btn-primary btn-sm"> <i
                                                                        class="fa fa-check-circle"></i> Active</button>
                                                            @endif
                                                            @if ($endDate < $today)
                                                                <button class="btn btn-danger btn-sm"> <i
                                                                        class="fas fa-exclamation-triangle"></i>
                                                                    Over</button>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($application->status == 'Pending')
                                                                <i class="fas fa-spinner fa-spin"></i>
                                                                {{ $application->status }}
                                                            @endif

                                                            @if ($application->status == 'Approved')
                                                                <i class="fas fa-check-square text-primary"></i>
                                                                {{ $application->status }}
                                                            @endif

                                                            @if ($application->status == 'Rejected')
                                                                <i class="fas fa-times-circle text-danger"></i>
                                                                {{ $application->status }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <strong class="text-primary">{{ number_format($application->adjusted_days) }} days
                                                                Adjusted</strong>
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
        new DataTable("#basic-datatableszindex");
        new DataTable("#basic-datatableszindexy");
    </script>
@endsection
