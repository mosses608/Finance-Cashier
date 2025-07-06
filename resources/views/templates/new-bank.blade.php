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
                                    <h4 class="p-3 fs-5">Bank Management</h4>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-secondary btn-sm float-end mt-3" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"><i class="fa fa-plus"></i> Add New Branch</button>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Banks List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Branch List</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Create New Bank</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables0" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Bank</th>
                                                    <th>Acc Name</th>
                                                    <th>Acc No</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th>P.O.Box</th>
                                                    <th>Code</th>
                                                    <th>Region</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bankLists as $list)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap">{{ $list->bankName }}</td>
                                                        <td class="text-nowrap">{{ $list->accountName }}</td>
                                                        <td class="text-nowrap">{{ $list->accountNumber }}</td>
                                                        <td class="text-nowrap">{{ $list->phone }}</td>
                                                        <td class="text-nowrap">{{ $list->address }}</td>
                                                        <td class="text-nowrap">{{ $list->box }}</td>
                                                        <td class="text-nowrap">{{ $list->code }}</td>
                                                        <td class="text-nowrap">{{ $list->region }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables00" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Bank Name</th>
                                                    <th>Branch Name</th>
                                                    <th>Branch Code</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bankBranchLists as $branch)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap">{{ $branch->bank_name }}</td>
                                                        <td class="text-nowrap">{{ $branch->branch_name }}</td>
                                                        <td class="text-nowrap">{{ $branch->branch_code }}</td>
                                                        <td class="text-nowrap">
                                                            <button class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                                            <button class="btn btn-sm" style="background-color: red; color: #FFF;"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-new-data" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <form action="{{ route('create.banks') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">Name</span>
                                                        <input type="text" class="form-control"
                                                            aria-label="Sizing example input" name="bank_name"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="bank name" required>
                                                    </div>
                                                </div>

                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">Account</span>
                                                        <input type="string" class="form-control"
                                                            aria-label="Sizing example input" name="account_name"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="account name">
                                                    </div>
                                                </div>

                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">Number</span>
                                                        <input type="number" class="form-control"
                                                            aria-label="Sizing example input" name="account_number"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="account number" maxlength="10">
                                                    </div>
                                                </div>

                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">Phone</span>
                                                        <input type="string" class="form-control"
                                                            aria-label="Sizing example input" name="phone"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="07xxxxxxxx" maxlength="10">
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">Address</span>
                                                        <input type="text" class="form-control"
                                                            aria-label="Sizing example input" name="address"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="physical address">
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">Email</span>
                                                        <input type="email" class="form-control"
                                                            aria-label="Sizing example input" name="email"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="xxxxxxx@gmail.com">
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">P.O.Box</span>
                                                        <input type="text" class="form-control" id="box"
                                                            aria-label="box" name="box"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="P.O.Box 1331">
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">Code</span>
                                                        <input type="text" class="form-control" id="code"
                                                            aria-label="Code" name="bank_code"
                                                            aria-describedby="inputGroup-sizing-default"
                                                            placeholder="bank code">
                                                    </div>
                                                </div>

                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"
                                                            id="inputGroup-sizing-default">Region</span>
                                                        <select class="form-control" id="region" aria-label="region"
                                                            name="region" aria-describedby="inputGroup-sizing-default">
                                                            <option value="" selected disabled>--select region--
                                                            </option>
                                                            @foreach ($regions as $region)
                                                                <option value="{{ $region->id }}">{{ $region->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-4 mb-3">
                                                    <div class="input-group mb-3">
                                                        <button type="submit" class="btn btn-primary">Save Data</button>
                                                    </div>
                                                </div>

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
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Branch</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('bank.branch') }}" method="POST" class="modal-body w-100">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Bank Name</span>
                                <select class="form-control" id="type" aria-label="group-name"
                                    aria-describedby="inputGroup-sizing-default" name="bank_name">
                                    <option value="" selected disabled>--select bank--</option>
                                    @foreach ($bankLists as $bankOpt)
                                        <option value="{{ $bankOpt->autoId }}">{{ $bankOpt->bankName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Name</span>
                                <input type="text" class="form-control" id="type" aria-label="group-name"
                                    aria-describedby="inputGroup-sizing-default" name="branch_name"
                                    placeholder="branch name" />
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Code</span>
                                <input type="text" class="form-control" id="type" aria-label="group-name"
                                    aria-describedby="inputGroup-sizing-default" name="branch_code"
                                    placeholder="branch code" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary btn-sm">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#basic-datatables0');
        new DataTable('#basic-datatables00');
    </script>
@stop
