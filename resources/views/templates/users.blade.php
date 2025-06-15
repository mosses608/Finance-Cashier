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
                                    <h4 class="p-3 fs-5">Stakeholders Management</h4>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-secondary btn-sm float-end mt-3" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"><i class="fa fa-plus"></i> Customer Group</button>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Stakeholder List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Customer Groups List</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Create New Stakeholder</button>
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
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th>Email</th>
                                                    <th>TIN</th>
                                                    <th>VRN</th>
                                                    <th>Region</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($stakeholders as $stakeholder)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $stakeholder->name }}</td>
                                                        <td>{{ $stakeholder->phone }}</td>
                                                        <td>{{ $stakeholder->address }}</td>
                                                        <td>{{ substr($stakeholder->email, 0, 1) . str_repeat('*', strpos($stakeholder->email, '@') - 2) . substr($stakeholder->email, 1, 1) . substr($stakeholder->email, strpos($stakeholder->email, '@')) }}
                                                        </td>
                                                        <td>{{ $stakeholder->tin }}</td>
                                                        <td>{{ $stakeholder->vrn }}</td>
                                                        <td>{{ $stakeholder->region }}</td>
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
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Group Name</th>
                                                    <th>Total Customers</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($customerGroups as $group)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $group->name }}</td>
                                                        <td>{{ number_format(13) }}</td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm"><i
                                                                    class="fa fa-edit"></i></button>
                                                            <button class="btn btn-sm"
                                                                style="background-color: red; color: #CCC;"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-new-data" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <form action="{{ route('stakeholder.create') }}" method="POST">
                                        @csrf
                                        <h4 class="p-2 mt-0 fs-5"><strong style="color: #007BFF;"><i
                                                    class="fa fa-check"></i></strong> General Information</h4>
                                        <div class="row">
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Name</span>
                                                    <input type="text" class="form-control"
                                                        aria-label="Sizing example input" name="name"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="xxx company" required>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Phone</span>
                                                    <input type="tel" class="form-control"
                                                        aria-label="Sizing example input" name="phone"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="07xxxxxxxx" maxlength="10" required>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Address</span>
                                                    <input type="text" class="form-control"
                                                        aria-label="Sizing example input" name="address"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="xxxxx, xxx">
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
                                                        id="inputGroup-sizing-default">TIN</span>
                                                    <input type="text" class="form-control" id="tin"
                                                        aria-label="TIN Number" name="tin"
                                                        aria-describedby="inputGroup-sizing-default" maxlength="11"
                                                        placeholder="xxx-xxx-xxx" required>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">VRN</span>
                                                    <input type="text" class="form-control" id="vrn"
                                                        name="vrn" aria-label="VRN Number"
                                                        aria-describedby="inputGroup-sizing-default" maxlength="10"
                                                        placeholder="xxxxxxxxxx">

                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="p-2 mt-0 fs-5"><strong style="color: #007BFF;"><i
                                                    class="fa fa-check"></i></strong> Specific Information</h4>
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Region</span>
                                                    <select class="form-control" id="region" aria-label="Region"
                                                        aria-describedby="inputGroup-sizing-default" name="region_id">
                                                        <option value="" selected disabled>--select region--</option>
                                                        @foreach ($cities as $city)
                                                            <option value="{{ $city->id }}">{{ $city->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-6 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Category</span>
                                                    <select class="form-control" id="selector" aria-label="Category"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        name="stakeholder_category">
                                                        <option value="" selected disabled>--stakeholder category--
                                                        </option>
                                                        @foreach ($stakeholderCategory as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- CUSTOMER SPECIFIC FIELDS --}}
                                        <div class="row" id="for-customer" style="display: none;">
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Type</span>
                                                    <select class="form-control" id="category"
                                                        aria-label="customer type"
                                                        aria-describedby="inputGroup-sizing-default" name="customer_type">
                                                        <option value="" selected disabled>--customer type--</option>
                                                        <option value="{{ __('General') }}">{{ __('General') }}</option>
                                                        <option value="{{ __('Owner') }}">{{ __('Owner') }}</option>
                                                        <option value="{{ __('Unlisted') }}">{{ __('Unlisted') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Identification</span>
                                                    <select class="form-control" id="identification"
                                                        aria-label="customer identification"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        name="identification_type">
                                                        <option value="" selected disabled>--customer
                                                            identification--</option>
                                                        @foreach ($identifications as $identification)
                                                            <option value="{{ $identification->slug }}">
                                                                {{ $identification->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3" id="identification-id" style="display: none;">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">ID</span>
                                                    <input type="number" class="form-control"
                                                        aria-label="Sizing example input"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="Identification Number" name="identification_number">
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Group</span>
                                                    <select class="form-control" id="group"
                                                        aria-label="customer group"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        name="customer_group">
                                                        <option value="" selected disabled>--customer group--
                                                        </option>
                                                        @foreach ($customerGroups as $group)
                                                            <option value="{{ $group->id }}">{{ $group->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- REGULATOR --}}
                                        <div class="row" id="for-regulator" style="display: none;">
                                            <div class="col-6 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Type</span>
                                                    <select class="form-control" id="type"
                                                        aria-label="regulstor type"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        name="regulator_type">
                                                        <option value="" selected disabled>--regulator type--
                                                        </option>
                                                        <option value="{{ __('Pension Funds') }}">
                                                            {{ __('Pension Funds') }}</option>
                                                        <option value="{{ __('Health Insurance Provider') }}">
                                                            {{ __('Health Insurance Provider') }}</option>
                                                        <option value="{{ __('Others') }}">{{ __('Others') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SUPPLIER --}}
                                        <div class="row" id="for-supplier" style="display: none;">
                                            <div class="col-6 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Type</span>
                                                    <select class="form-control" id="type"
                                                        aria-label="supplier type"
                                                        aria-describedby="inputGroup-sizing-default" name="supplier_type">
                                                        <option value="" selected disabled>--supplier type--</option>
                                                        <option value="{{ __('Contract') }}">{{ __('Contract') }}
                                                        </option>
                                                        <option value="{{ __('Building Materials') }}">
                                                            {{ __('Building Materials') }}</option>
                                                        <option value="{{ __('PPE') }}">{{ __('PPE') }}</option>
                                                        <option value="{{ __('Unlisted') }}">{{ __('Unlisted') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-primary float-end">Save Data</button>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Customer Group</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.group') }}" method="POST" class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Name</span>
                                <input type="text" class="form-control" id="type" aria-label="group-name"
                                    aria-describedby="inputGroup-sizing-default" name="name" />
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
        document.getElementById('tin').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '').substring(0, 9);
            value = value.replace(/(\d{3})(\d{3})(\d{0,3})/, function(_, p1, p2, p3) {
                return p3 ? `${p1}-${p2}-${p3}` : `${p1}-${p2}`;
            });
            e.target.value = value;
        });

        document.getElementById('vrn').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '').substring(0, 10);
            e.target.value = value;
        });
    </script>

    <script>
        document.getElementById('selector').addEventListener('change', function() {
            // Get the selected option's text
            const selectedText = this.options[this.selectedIndex].textContent.trim();

            // Hide all target sections initially
            document.getElementById('for-customer').style.display = 'none';
            document.getElementById('for-regulator').style.display = 'none';
            document.getElementById('for-supplier').style.display = 'none';

            // Show relevant section based on the selected text
            if (selectedText === 'Customer') {
                document.getElementById('for-customer').style.display = 'flex';
            } else if (selectedText === 'Regulator') {
                document.getElementById('for-regulator').style.display = 'flex';
            } else if (selectedText === 'Supplier') {
                document.getElementById('for-supplier').style.display = 'flex';
            }
        });

        document.getElementById("identification").addEventListener('change', function() {
            const selectedText = this.value;

            document.getElementById("identification-id").style.display = 'none';

            if (selectedText && selectedText !== '') {
                document.getElementById("identification-id").style.display = 'block';
            }
        });
    </script>


@stop
