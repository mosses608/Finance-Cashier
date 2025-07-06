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
                                    <h4 class="p-3 fs-5">Stakeholders Reports</h4>
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
                                    aria-selected="true">Customers Report</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Suppliers Report</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Regulators Report</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-bank-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Bank Report</button>
                                {{-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Bank Branch Report</button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Customer Name</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th>Email</th>
                                                    <th>TIN</th>
                                                    <th>VRN</th>
                                                    <th>Region</th>
                                                    <th>Customer Type</th>
                                                    <th>Customer Group</th>
                                                    <th>Issued Invoices</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($customersReports as $report)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap">{{ $report->name ?? '' }}</td>
                                                        <td class="text-nowrap">{{ $report->phone ?? '' }}</td>
                                                        <td class="text-nowrap">{{ $report->address ?? '' }}</td>
                                                        <td class="text-nowrap">{{ $report->email ?? '' }}</td>
                                                        <td class="text-nowrap">{{ $report->tin ?? '' }}</td>
                                                        <td class="text-nowrap">{{ $report->vrn ?? '' }}</td>
                                                        <td class="text-nowrap">{{ $report->region ?? '' }}</td>
                                                        <td class="text-nowrap">{{ $report->customer_type ?? '' }}</td>
                                                        <td class="text-nowrap">{{ $report->groupName ?? '' }}</td>
                                                        <td class="text-nowrap">
                                                            {{ number_format($report->invoiceIssued ?? 0) }}</td>
                                                        <td class="text-center text-nowrap">
                                                            <a href="#" class="btn btn-primary btn-sm"><i
                                                                    class="fa fa-eye"></i></a>
                                                            <a href="#" class="btn btn-sm"
                                                                style="background-color: #008080; color: #FFF;"><i
                                                                    class="fa fa-edit"></i></a>
                                                            <a href="#" class="btn btn-sm"
                                                                style="background-color: red; color: #FFF;"><i
                                                                    class="fa fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($customersReports) == 0)
                                            <span class="p-3 mb-3 mt-3">No customer found!</span>
                                        @endif
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables1" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Supplier Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th>TIN</th>
                                                    <th>VRN</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($stakeholdersSuppliers as $supplier)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap">{{ $supplier->name }}</td>
                                                        <td class="text-nowrap">{{ $supplier->email }}</td>
                                                        <td class="text-nowrap">{{ $supplier->phone }}</td>
                                                        <td class="text-nowrap">{{ $supplier->address }}</td>
                                                        <td class="text-nowrap">{{ $supplier->tin }}</td>
                                                        <td class="text-nowrap">{{ $supplier->vrn }}</td>
                                                        <td class="text-nowrap">
                                                            <button class="btn btn-primary btn-sm"><i
                                                                    class="fa fa-edit"></i></button>
                                                            <button class="btn btn-sm"
                                                                style="background-color: red; color: #FFF;"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if (count($stakeholdersSuppliers) == 0)
                                            <span class="p-3 mb-3 mt-3">No supplier found!</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-new-data" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <div class="table-responsive">
                                            <table id="basic-datatables11" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Regulator Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Address</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($regulatorsReports as $regulator)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td class="text-nowrap">{{ $regulator->name }}</td>
                                                            <td class="text-nowrap">{{ $regulator->email }}</td>
                                                            <td class="text-nowrap">{{ $regulator->phone }}</td>
                                                            <td class="text-nowrap">{{ $regulator->address }}</td>
                                                            <td class="text-nowrap">
                                                                <button class="btn btn-primary btn-sm"><i
                                                                        class="fa fa-edit"></i></button>
                                                                <button class="btn btn-sm"
                                                                    style="background-color: red; color: #FFF;"><i
                                                                        class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (count($regulatorsReports) == 0)
                                                <span class="p-3 mb-3 mt-3">No regulator found!</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="nav-bank-data" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <div class="table-responsive">
                                            <table id="basic-datatables10" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Bank Name</th>
                                                        <th>Branch Name</th>
                                                        <th>Acc Name</th>
                                                        <th>Acc Number</th>
                                                        <th>Address</th>
                                                        <th>Box</th>
                                                        <th>Code</th>
                                                        <th>Phone</th>
                                                        <th>Region</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($bankReports as $bank)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td class="text-nowrap">{{ $bank->bankName }}</td>
                                                            <td class="text-nowrap">{{ $bank->branchName }}</td>
                                                            <td class="text-nowrap">{{ $bank->accountName }}</td>
                                                            <td class="text-nowrap">{{ $bank->accNumber }}</td>
                                                            <td class="text-nowrap">{{ $bank->bankAddress }}</td>
                                                            <td class="text-nowrap">{{ $bank->bankBox }}</td>
                                                            <td class="text-nowrap">{{ $bank->bankCode }}</td>
                                                            <td class="text-nowrap">{{ $bank->bankPhone }}</td>
                                                            <td class="text-nowrap">{{ $bank->region }}</td>
                                                            <td class="text-nowrap">
                                                                <button class="btn btn-primary btn-sm"><i
                                                                        class="fa fa-edit"></i></button>
                                                                <button class="btn btn-sm"
                                                                    style="background-color: red; color: #FFF;"><i
                                                                        class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if (count($bankReports) == 0)
                                                <span class="p-3 mb-3 mt-3">No bank found!</span>
                                            @endif
                                        </div>
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

        // $(document).ready(function() {
        //     $('#basic-datatables').DataTable({
        //         dom: 'Bfrtip',
        //         buttons: [
        //             'copy', 'csv', 'excel', 'pdf', 'print'
        //         ]
        //     });
        // });
    </script>

@stop
