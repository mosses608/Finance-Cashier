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
                                    <h4 class="p-3 fs-5">Staff Management</h4>
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
                                    aria-selected="true">Staff List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">New Staff</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    {{-- <th>S/N</th> --}}
                                                    <th>ID</th>
                                                    <th>Names</th>
                                                    <th>Phone</th>
                                                    <th>Phone2</th>
                                                    <th>Department</th>
                                                    <th>Address</th>
                                                    <th>TIN</th>
                                                    <th>Bank</th>
                                                    <th>Bank Acc</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               @foreach ($employees as $employee)
                                                   <tr>
                                                    <td>{{ $employee->id }}</td>
                                                    <td class="text-nowrap">{{ $employee->fName . ' ' . $employee->lName }}</td>
                                                    <td>{{ $employee->phone_number }}</td>
                                                    <td>{{ $employee->emergency_contact_phone }}</td>
                                                    <td>{{ $employee->department }}</td>
                                                    <td>{{ $employee->address }}</td>
                                                    <td>{{ $employee->tin }}</td>
                                                    <td>{{ $employee->bankName }}</td>
                                                    <td>{{ $employee->bank_account_number }}</td>
                                                    <td class="text-nowrap text-center">
                                                        <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                   </tr>
                                               @endforeach
                                            </tbody>
                                        </table>
                                       
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <form action="{{ route('staff.store') }}" method="POST" class="row" enctype="multipart/form-data">
                                        @csrf
                                        @include('partials.staff-reg')
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
        new DataTable('#basic-datatables');
        new DataTable('#basic-datatables1');
        new DataTable('#basic-datatables10');
        new DataTable('#basic-datatables11');
    </script>

@stop
