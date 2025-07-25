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
                                    <h4 class="p-3 fs-5">Reset Passwords</h4>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Reset Passwords</button>
                                {{-- <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">New System User</button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <form action="{{ route('reset.password') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">

                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    {{-- <span class="input-group-text" id="inputGroup-sizing-default">Username
                                                    </span> --}}
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        id="userSelectIdy" name="user_id"
                                                        aria-describedby="inputGroup-sizing-default">
                                                        <option value="" selected disabled>--select user--
                                                        </option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->user_id }}">{{ $user->username }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Password
                                                    </span>
                                                    <input type="password" class="form-control"
                                                        aria-label="Sizing example input" name="password"
                                                        aria-describedby="inputGroup-sizing-default" id="password"
                                                        placeholder="password" maxlength="12" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Confirm-Password
                                                    </span>
                                                    <input type="password" class="form-control"
                                                        aria-label="Sizing example input" name="password_confirm"
                                                        aria-describedby="inputGroup-sizing-default" id="password-confirm"
                                                        placeholder="re-enter password" maxlength="12" autocomplete="off"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <div class="input-group mb-3">
                                                    <button type="submit" class="btn btn-primary">Submit User</button>
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
    <script>
        $(document).ready(function() {
            $('#userSelectId').select2({
                placeholder: "--select user--",
                allowClear: true
            });
        });
    </script>
@stop
