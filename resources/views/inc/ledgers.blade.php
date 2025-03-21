@extends('layouts.mainLayout')

@section('content')
<div class="transparent" onclick="hideAll(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
        <h3>{{ __('Create Ledger') }}</h3>
        @include('partials.create-ledger')
        <br>
    </div>
</div>

<div class="shortcut-report">
   @include('partials.ledger-group')
</div>

@stop