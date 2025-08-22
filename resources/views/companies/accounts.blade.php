@extends('layouts.admin')
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
                                    <h4 class="p-1 fs-5 text-success">Company Accounts</h4>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Active Accounts</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Suspended Accounts</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatablesxxx" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Company ID</th>
                                                    <th>Company Name</th>
                                                    {{-- <th>Company Manager</th> --}}
                                                    <th>Region</th>
                                                    <th>Address</th>
                                                    <th>TIN</th>
                                                    <th>VRN</th>
                                                    <th>Date Registered</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($activeCompanies as $company)
                                                    <tr>
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $company->company_reg_no }}</td>
                                                        <td>{{ $company->company_name }}</td>
                                                        <td>{{ $company->region ?? '###' }}</td>
                                                        <td>{{ $company->address ?? '###' }}</td>
                                                        <td class="text-nowrap">{{ $company->tin }}</td>
                                                        <td>{{ $company->vrn ?? '###' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($company->dateReg)->format('M d, Y') }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <a href="#" class="btn btn-primary btn-sm"
                                                                title="view account information"><i
                                                                    class="fa fa-eye"></i></a>
                                                            <a href="#" class="btn btn-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#confirmModal-{{ $company->company_id }}"
                                                                title="deactivate account"><i class="fas fa-ban"></i></a>

                                                            <form action="{{ route('suspend.account') }}" method="POST"
                                                                class="modal fade"
                                                                id="confirmModal-{{ $company->company_id }}" tabindex="-1"
                                                                aria-labelledby="confirmModalLabel" aria-hidden="true">
                                                                @csrf
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title" id="confirmModalLabel">
                                                                                Are you sure?</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            This action cannot be undone. Do you want to
                                                                            proceed?
                                                                        </div>
                                                                        @php
                                                                            $companyId = \Illuminate\Support\Facades\Crypt::encrypt(
                                                                                json_encode($company->company_id),
                                                                            );
                                                                        @endphp
                                                                        <input type="hidden" name="company_id"
                                                                            value="{{ $companyId }}" id="">
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Cancel</button>

                                                                            <button type="submit" class="btn btn-danger"
                                                                                id="confirmBtn">Confirm</button>
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

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables0x1" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Company ID</th>
                                                    <th>Company Name</th>
                                                    <th>Region</th>
                                                    <th>Address</th>
                                                    <th>TIN</th>
                                                    <th>VRN</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $p = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($inactiveAccounts as $inactive)
                                                    <tr>
                                                        <td>{{ $p++ }}</td>
                                                        <td>{{ $inactive->company_reg_no }}</td>
                                                        <td>{{ $inactive->company_name }}</td>
                                                        <td>{{ $inactive->region ?? '###' }}</td>
                                                        <td>{{ $inactive->address ?? '###' }}</td>
                                                        <td class="text-nowrap">{{ $inactive->tin }}</td>
                                                        <td>{{ $inactive->vrn ?? '###' }}</td>
                                                        <td class="text-white text-nowrap"><button btn
                                                                class="btn btn-danger btn-sm"><i class="fas fa-ban"></i>
                                                                Suspended</button></td>
                                                        <td class="text-nowrap">
                                                            <a href="#" class="btn btn-primary btn-sm"
                                                                title="view account information"><i
                                                                    class="fa fa-eye"></i></a>

                                                            <a href="#" class="btn btn-success btn-sm"
                                                                title="activate account"><i class="fas fa-toggle-on"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#activate-{{ $inactive->company_id }}"></i></a>

                                                            <form action="{{ route('activate.account') }}" method="POST"
                                                                class="modal fade"
                                                                id="activate-{{ $inactive->company_id }}"
                                                                tabindex="-1" aria-labelledby="activate"
                                                                aria-hidden="true">
                                                                @csrf
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-warning">
                                                                            <h5 class="modal-title"
                                                                                id="activate">
                                                                                Are you sure?</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            This action cannot be undone. Do you want to
                                                                            proceed?
                                                                        </div>
                                                                        @php
                                                                            $companyId = \Illuminate\Support\Facades\Crypt::encrypt(
                                                                                json_encode($inactive->company_id),
                                                                            );
                                                                        @endphp
                                                                        <input type="hidden" name="company_id"
                                                                            value="{{ $companyId }}" id="">
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-danger"
                                                                                data-bs-dismiss="modal">Cancel</button>

                                                                            <button type="submit" class="btn btn-secondary"
                                                                                id="confirmBtn">Activate</button>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#basic-datatablesxxx');
        new DataTable('#basic-datatables0x1');
    </script>
    {{-- 
    <script>
        document.getElementById('confirmBtn').addEventListener('click', function() {
            alert('Confirmed! Proceeding with action...');
            var modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            modal.hide();
        });
    </script> --}}
@stop
