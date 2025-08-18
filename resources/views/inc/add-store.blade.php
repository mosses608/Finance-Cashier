@extends('layouts.part')
@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row mt-3">
                <x-messages />
                <form action="{{ route('comp.store') }}" method="POST" class="col-md-12">
                    @csrf
                    <div class="card mt-1">
                        <div class="card-header">
                            <div class="card-title">Add New Store</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="email2">Name</label>
                                        <input type="text" class="form-control" id="email2" name="store_name"
                                            value="{{ old('store_name') }}" placeholder="Store Name" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Location | City</label>
                                        <select class="form-control select2" name="city" id="exampleFormControlSelect1"
                                            style="width: 100%;">
                                            <option value="">--select--</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="location">Location | Area</label>
                                        <input type="text" class="form-control" id="password" name="location"
                                            value="{{ old('location') }}" placeholder="Eg Kigamboni" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="password">Store-Keeper</label>
                                        <input type="text" class="form-control" id="password" name="store_keeper"
                                            value="{{ old('store_keeper') }}" placeholder="Store Keeper Name" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="password">Phone Number</label>
                                        <input type="tel" class="form-control" id="password" name="phone"
                                            value="{{ old('phone') }}" placeholder="Store Keeper Phone" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type=""submit class="btn btn-primary">Save Data</button>
                        {{-- <button class="btn btn-danger">Cancel</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@stop
