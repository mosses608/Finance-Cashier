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
                                    <h4 class="p-3 fs-5">System Users Reports</h4>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">System Users</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">System User Logs</button>
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
                                                    <th>Names</th>
                                                    <th>Username</th>
                                                    <th>Role</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                    {{-- <th>Action</th> --}}
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($systemUsersFromAdmin as $systemUser)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $systemUser->fullNames }}</td>
                                                        <td>{{ $systemUser->username }}</td>
                                                        <td>{{ $systemUser->roleName }}</td>
                                                        <td>{{ __('Administration') }}</td>
                                                        <td>
                                                            @if ($systemUser->status)
                                                                <button class="btn btn-primary btn-sm">Active</button>
                                                            @else
                                                                <button class="btn btn-warning btn-sm">In-active</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                @foreach ($systemUsersFromEmploy as $user)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $user->fName . ' ' . $user->lName }}</td>
                                                        <td>{{ $user->username }}</td>
                                                        <td>{{ $user->roleName }}</td>
                                                        <td>{{ $user->department }}</td>
                                                        <td>
                                                            @if ($user->status)
                                                                <button class="btn btn-primary btn-sm">Active</button>
                                                            @else
                                                                <button class="btn btn-warning btn-sm">In-active</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>S/N</th>
                                                    <th>Names</th>
                                                    <th>IP Address</th>
                                                    <th>User Agent</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($userLogs as $ulog)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $ulog->fName . ' ' . $ulog->lName }}</td>
                                                        <td>{{ $ulog->ipaddress }}</td>
                                                        <td>{{ $ulog->agent }}</td>
                                                    </tr>
                                                @endforeach

                                                @foreach ($logs as $log)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $log->names }}</td>
                                                        <td>{{ $log->ipaddress }}</td>
                                                        <td>{{ $log->agent }}</td>
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
        new DataTable('#basic-datatables');
    </script>
@stop
