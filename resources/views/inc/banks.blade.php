@extends('layouts.mainLayout')

@section('content')

<div class="transparent" onclick="hidee(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
    <h3>{{ __('Bank List') }}</h3>
    <button type="button" class="btn btn-secondary" onclick="addNewBnak(event)"><a href="#"><i class="fa fa-plus"></i> Add Bank</a></button>
<br>
    <form action="#" method="GET" class="form-data">
        @csrf
        <div class="meta-data">
            <span><i class="fa fa-search"></i></span>
            <input
                type="text" 
                name="search" 
                id=""
                placeholder="Search Bank"
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

    @include('partials.create-bank')

    <div class="scrollable" style="width: 100%; overflow-x: scroll;">
        <table class="table table-bordered table-striped">
            <thead class="table-white">
                <tr>
                    <th>S/N</th>
                    <th>Bank Name</th>
                    <th>Branch</th>
                    <th>Account No</th>
                    <th>Account Name</th>
                    <th>Phone</th>
                    <th>Initial Balance</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($banks as $bank)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bank->bank_name }}</td>
                    <td>{{ $bank->branch }}</td>
                    <td>{{ $bank->account_no }}</td>
                    <td>{{ $bank->account_name }}</td>
                    <td>{{ $bank->phone }}</td>
                    <td>{{ number_format($bank->balance, 2) }}</td>
                    <td style="text-align: center;">
                        @if($bank->status == 'Active')
                        <span style="background-color: #008800; padding: 4px; color: #FFF; border-radius: 6px; font-size:12px; width: 80px; text-align: center;">{{ $bank->status }}</span>
                        @else
                        <span>Inactive</span>
                        @endif
                    </td>
                    <td class="action-btn">
                        <button class="edit-usr" style="color: #008800;"><i class="fa fa-edit"></i></button>
                        <button class="delete-usr" style="color: red;"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($banks) == 0)
    <span>No bank found!</span>
    @endif
</div>

@stop