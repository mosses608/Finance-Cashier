@extends('layouts.mainLayout')

@section('content')

<div class="transparent" onclick="hidey(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
    <h3>{{ __('Transafer List') }}</h3>
    <button type="button" class="btn btn-secondary" onclick="createTransfer(event)"><a href="#"><i class="fa fa-plus"></i> Create Tarnsfer</a></button>
<br>
    <form action="#" method="GET" class="form-data">
        @csrf
        <div class="meta-data">
            <span><i class="fa fa-search"></i></span>
            <input
                type="text" 
                name="search" 
                id=""
                placeholder="Search Transfer"
            >
        </div>
        <div class="export-print">
            <button type="button"> <a href="file.xlsx" class="btn btn-outline-success" download>
                <i class="fa fa-file-excel"></i>
            </a>
            </button>
            <button type="button"> <a href="file.pdf" class="btn btn-outline-success">
                <i class="fa fa-file-pdf" style="color: orange;"></i>
            </a>
            </button>
            <button type="button"> <a href="#"  class="btn btn-outline-success">
                <i class="fa fa-print" style="color: #007BFF;"></i>
            </a>
            </button>
        </div>
        <br><br>
    </form>

    @include('partials.create-transfer')

    <div class="scrollable" style="width: 100%; overflow-x: scroll;">
        <table class="table table-bordered table-striped">
            <thead class="table-white">
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>Reference Number</th>
                    <th>From Account</th>
                    <th>To Account</th>
                    <th>Amount Transfered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
               @foreach($transfers as $transfer)
               <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transfer->created_at->format('Y-m-d') }}</td>
                <td>TRN-0{{ $transfer->id }}</td>
                <td>{{ $transfer->from_account }}</td>
                <td>{{ $transfer->to_account }}</td>
                <td>{{ number_format($transfer->amount, 2) }}</td>
                <td class="action-btn">
                    <button class="edit-usr" style="color: #008800;"><i class="fa fa-edit"></i></button>
                    <button class="delete-usr" style="color: red;"><i class="fa fa-trash"></i></button>
                </td>
               </tr>
               @endforeach
            </tbody>
        </table>
    </div>
    @if(count($transfers) == 0)
    <span>No data found!</span>
    @endif
</div>


@stop