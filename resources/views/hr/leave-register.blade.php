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
                                    aria-selected="true">Leave Type List</button>
                                <button class="nav-link" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-out-profoma" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Register Leave Type
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
                                                    <th>Number of days</th>
                                                    <th>Priority</th>
                                                    <th>Gender Specification</th>
                                                    <th>Require Attachment</th>
                                                    <th>Balance Carry-Over</th>
                                                    <th>Created By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($leaves as $leave)
                                                    <tr class="text-nowrap">
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $leave->name }}</td>
                                                        <td>{{ number_format($leave->days) }}</td>
                                                        <td>{{ $leave->leave_priority }}</td>
                                                        <td>{{ $leave->gender_specification }}</td>
                                                        <td>
                                                            @if ($leave->require_attachment == 0)
                                                                No
                                                            @else
                                                                Yes
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($leave->is_balance_carry_over == 0)
                                                                No
                                                            @else
                                                                Yes
                                                            @endif
                                                        </td>
                                                        <td>{{ $leave->first_name . ' ' .  $leave->last_name }}</td>
                                                        <td>
                                                            <form action="#" method="POST">
                                                                @method('PUT')
                                                                @csrf
                                                                <button type="submit" class="btn btn-warning btn-sm"><i
                                                                        class="fa fa-trash"></i></button>
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
                                    <form action="{{ route('store.leave.types') }}" method="POST" class="row">
                                        @csrf
                                        <div class="col-6 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Name</span>
                                                <input type="text" class="form-control" aria-label="Sizing example input"
                                                    name="name" aria-describedby="inputGroup-sizing-default"
                                                    placeholder="leave name" required>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Days</span>
                                                <input type="number" class="form-control" aria-label="Sizing example input"
                                                    name="days" aria-describedby="inputGroup-sizing-default"
                                                    placeholder="number of days">
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Days</span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="leave_priority" aria-describedby="inputGroup-sizing-default">
                                                    <option value="" selected disabled>--select--</option>
                                                    <option value="Mandatory">Mandatory</option>
                                                    <option value="Optional">Optional</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Gender</span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="gender_specification"
                                                    aria-describedby="inputGroup-sizing-default">
                                                    <option value="" selected disabled>--select--</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Both">Both</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Attachment
                                                    Required ? </span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="require_attachment" aria-describedby="inputGroup-sizing-default">
                                                    <option value="" selected disabled>--select--</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="inputGroup-sizing-default">Balance
                                                    Carry-Over ? </span>
                                                <select class="form-control" aria-label="Sizing example input"
                                                    name="is_balance_carry_over"
                                                    aria-describedby="inputGroup-sizing-default">
                                                    <option value="" selected disabled>--select--</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary">Save Data</button>
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
