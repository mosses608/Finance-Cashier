@extends('layouts.mainLayout')

@section('content')

<div class="transparent" onclick="hide(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
        <h3>{{ __('Ledger List') }}</h3>
        <button type="button" class="btn btn-secondary" onclick="filterLedger(event)"><i class="fa fa-filter"></i> Filter</button>
        <button type="button" class="btn btn-secondary"><a href="{{ route('create.ledger') }}"><i class="fa fa-plus"></i> New Ledger</a></button>
<br>
        <form action="#" method="GET" class="form-data">
            @csrf
            <div class="meta-data">
                <span><i class="fa fa-search"></i></span>
                <input
                 type="text" 
                 name="search" 
                 id=""
                 placeholder="Search Ledger"
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

        @include('partials.filter')

        <div class="scrollable" style="width: 100%; overflow-x: scroll;">
            <table class="table table-bordered table-striped">
                <thead class="table-white">
                    <tr>
                        <th>S/N</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>References</th>
                        <th>Ledger Type</th>
                        <th>Ledger Group</th>
                        <th>Mode</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($ledgers as $ledger)
                   @php
                   $group = $ledgerGroups->firstWhere('id', $ledger->ledger_group);
                   @endphp
                   <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ledger->date }}</td>
                    <td>{{ $ledger->customer_name }}</td>
                    <td>LREF-0{{ $ledger->id }}</td>
                    <td>{{ $ledger->ledger_type }}</td>
                    <td>{{ $group->group_name }}</td>
                    <td>{{ $ledger->mode }}</td>
                    <td>{{ number_format($ledger->amount, 2) }}</td>
                    <td class="action-btn">
                        <button class="edit-usr" style="color: #008800;"><i class="fa fa-edit"></i></button>
                        <button class="delete-usr" style="color: red;"><i class="fa fa-trash"></i></button>
                    </td>
                   </tr>
                   @endforeach
                </tbody>
            </table>
        </div>
        
        @if(count($ledgers) == 0)
        <span>No data found!</span>
        @endif
</div>

@stop