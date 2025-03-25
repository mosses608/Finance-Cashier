@extends('layouts.mainLayout')

@section('content')


<div class="transparent" onclick="hidey(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
    <h3>{{ __('Trial Balance') }}</h3>
    <div class="md-7">
        <button type="button" class="btn btn-secondary" onclick="filterTrialBal(event)"><i class="fa fa-filter"></i> Filter</button>
<br>
    <form action="{{ route('trial.balance') }}" method="GET" class="form-data">
        @csrf
        <div class="meta-data">
            <span><i class="fa fa-search"></i></span>
            <input
                type="text" 
                name="search" 
                id=""
                placeholder="Search Data"
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

    @include('partials.tria-balance-filter')

    <div class="scrollable" style="width: 100%; overflow-x: scroll;">
        <table class="table table-bordered table-striped">
            <thead class="table-white">
                <tr>
                    <th>S/N</th>
                    <th>Account Name</th>
                    <th>Particular</th>
                    <th>Debit Balance</th>
                    <th>Credit Balance</th>
                </tr>
            </thead>
            <tbody>
               @foreach($journals as $journal)

               @php

               $ledger = $ledgers->firstWhere('id', $journal->ledger_id);

               $totalDebitBalance = 0;
               $totalCreditBalance = 0;

               foreach($debitBalanceJ as $j){
                    $totalDebitBalance += $ledger->amount;
               }

               foreach($creditBalanceJ as $jou){
                    $totalCreditBalance += $ledger->amount;
               }

               @endphp

               <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ledger->customer_name }}</td>
                <td>{{ $journal->particular }}</td>
                <td>
                    @if($journal->mode == 'Dr')
                    {{ number_format($ledger->amount, 2) }}
                    @else
                    {{ __('0.00') }}
                    @endif
                </td>
                <td>
                    @if($journal->mode == 'Cr')
                    {{ number_format($ledger->amount, 2) }}
                    @else
                    {{ __('0.00') }}
                    @endif
                </td>
               </tr>
               @endforeach

               @if($journals)
               <tr>
                <td><strong>Total</strong></td>
                <td></td>
                <td></td>
                <td><strong>TZS {{ number_format($totalDebitBalance, 2) }}</strong></td>
                <td><strong>TZS {{ number_format($totalCreditBalance, 2) }}</strong></td>
               </tr>
               @endif
            </tbody>
        </table>
    </div>
    @if(count($journals) == 0)
    <span>No data found!</span>
    @endif
</div>

@stop