@extends('layouts.part')

@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <x-messages />
                        <div class="card-header">
                            <h4 class="card-title">Store List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Store Keeper</th>
                                            <th>Contacts</th>
                                            <th>Storage Items</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    {{--
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </tfoot>
                                    --}}
                                    <tbody>
                                        @foreach ($stores as $store)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $store->store_name }}</td>
                                                <td>{{ $store->store_keeper ?? 'Unknown' }}</td>
                                                <td>{{ $store->phone ?? '255xxxxxxxx' }}</td>
                                                <td class="text-center">
                                                    {{ number_format($store->totalItems) }}
                                                </td>
                                                @php
                                                $encryptedStoreId = Crypt::encrypt($store->autoId);
                                                @endphp
                                                <td><a class="btn btn-primary sm text-center" href="{{ route('store.view', $encryptedStoreId) }}"><i class="fa fa-eye"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($stores->isEmpty())
                                <span class="text-warning p-3 mt-3">If you added stores and still don't see it here, try to assign products to this store, then you will see it here...</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
