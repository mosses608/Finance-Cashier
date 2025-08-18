@extends('layouts.landing')

@section('content')
    <div class="container mt-4">
        <center class="center">
            <div class="center">
                <span><i class="fa fa-area-chart" style="color: gold;"></i></span>
                <!-- <img src="{{ asset('assets/images/logo-finance.png') }}" alt="Logo Finance"> -->
                <h3>{{ __('Akili Soft ERP') }}</h3>
            </div>
        </center>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <form action="{{ route('finalise.reset') }}" method="POST">
                    @csrf
                    <input type="hidden" name="username" class="form-control" id="username" value="{{ $username }}"
                        required>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Enter new password" minlength="8" required>
                            <span class="input-group-text">
                                <i class="fas fa-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" name="password_comfirm" class="form-control" id="password"
                                placeholder="Re-enter new Password" minlength="8" required>
                            <span class="input-group-text">
                                <i class="fas fa-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success p-3 w-100">Reset Password</button>
                </form>
            </div>
        </div>
        <x-messages />
    </div>
@stop
