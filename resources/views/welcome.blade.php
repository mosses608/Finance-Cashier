@extends('layouts.landing')

@section('content')
    <div class="container mt-4">
        <center class="center">
            <span><i class="fa fa-area-chart" style="color: gold;"></i></span>
            <!-- <img src="{{ asset('assets/images/logo-finance.png') }}" alt="Logo Finance"> -->
            <h3>{{ __('Finance & ERP') }}</h3>
        </center>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <form action="{{ route('auth0n') }}" method="POST">
                    @csrf
                    <div class="mb-3 position-relative">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <input type="text" name="username" class="form-control" id="username"
                                placeholder="Enter username" autofocus="on" autocomplete="off" required>
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Enter password" required>
                            <span class="input-group-text">
                                <i class="fas fa-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                    <a href="{{ route('forgot.password') }}">Forgot Password?</a>
                </form>
            </div>
        </div>
        <x-messages />
    </div>
@stop
