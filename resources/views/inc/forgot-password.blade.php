@extends('layouts.landing')

@section('content')
<div class="container mt-4">
    <div class="center">
        <span><i class="fa fa-area-chart" style="color: gold;"></i></span>
        <!-- <img src="{{ asset('assets/images/logo-finance.png') }}" alt="Logo Finance"> -->
        <h3>{{__('Reset Password')}}</h3>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form action="{{ route('rest.password') }}" method="POST">
                @csrf
                <div class="mb-3 position-relative">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" id="username" placeholder="Enter your username" autofocus="on" autocomplete="off" required>
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Send Rest Link</button>
                <a href="{{ route('login') }}">Back To Login</a>
            </form>
        </div>
    </div>
    <x-messages />
</div>
@stop