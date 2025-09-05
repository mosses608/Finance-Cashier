@extends('layouts.landing')
@section('content')
        <x-messages />
    <div class="container mt-4">
        <center class="center">
            <span><i class="fa fa-area-chart" style="color: gold;"></i></span>
            {{-- <img src="{{ asset('assets/images/logo-finance.png') }}" alt="Logo Finance" width="100" height="100"> --}}
            <h3 class="text-success">{{ __('AkiliSoft ERP') }}</h3>
        </center>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <form action="{{ route('auth0n') }}" method="POST">
                    @csrf
                    <div class="mb-3 position-relative">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <input type="text" name="username" class="form-control" id="username"
                                placeholder="Phone number as username" autofocus="on" autocomplete="off" required>
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Enter password" autocomplete="off" required>
                            <span class="input-group-text">
                                <i class="fas fa-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
                            </span>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success p-3 col-12">Sign In <i
                                class="fa fa-sign-in"></i></button>
                    </div>
                    <div class="row py-3">
                        <div class="col-md-6">
                            <a class="float-start" href="{{ route('get.started') }}">Don't have an account?</a>
                        </div>
                        <div class="col-md-6">
                            <a class="float-end" href="{{ route('forgot.password') }}">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
