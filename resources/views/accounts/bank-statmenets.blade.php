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
                                    <h4 class="p-3 fs-5">Bank Statements</h4>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Statement Summary</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    @if ($statementsCounter == 0)
                                        <form action="{{ route('bank.statements') }}" method="GET">
                                            @php
                                                $today = \Carbon\Carbon::today()->format('Y-m-d');
                                            @endphp
                                            <div class="row">
                                                <div class="col-5 mb-3">
                                                    <div class="input-group">
                                                        <input type="date" name="from_date" id="from_date"
                                                            max="{{ $today }}" class="form-control"
                                                            value="{{ old('from_date', $today) }}">
                                                        <span class="input-group-text">-</span>
                                                        <input type="date" name="to_date" id="to_date"
                                                            class="form-control" value="{{ old('to_date', $today) }}">
                                                    </div>
                                                </div>
                                                <div class="col-5 mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">Petty Cash Acc</span>
                                                        <select class="form-control" name="bank_id">
                                                            <option value="" selected disabled>--select account--
                                                            </option>
                                                            @foreach ($pettyCashAccounts as $account)
                                                                <option
                                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($account->bankId) }}">
                                                                    {{ $account->bankName . ' ' . ' - ' . ' ' . $account->accName . ' ' . ' - ' . ' ' . $account->account_number }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-2 mb-3">
                                                    <div class="input-group mb-3 float-end">
                                                        <button type="submit" class="btn btn-primary">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                    @if (isset($statements) && $statementsCounter != 0)
                                        <div class="row">
                                            <table class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-capitalize"><strong>Account Name</strong></th>
                                                        <th class="float-end text-capitalize">
                                                            <strong>{{ $accoutData->bank_name . ' ' . ' - ' . $accoutData->account_name }}</strong>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-capitalize"><strong>Statement Date</strong></th>
                                                        <th class="float-end text-capitalize">
                                                            <strong>{{ \Carbon\Carbon::today()->format('M d, Y') }}</strong>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-capitalize"><strong>Statement Period</strong></th>
                                                        <th class="float-end text-capitalize">
                                                            <strong>{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') . ' ' . ' - ' . ' ' . \Carbon\Carbon::parse($toDate)->format('M d, Y') }}
                                                            </strong>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <table class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-capitalize"><strong>S/N</strong></th>
                                                        <th class="text-capitalize"><strong>Date</strong></th>
                                                        <th class="text-capitalize"><strong>Details</strong></th>
                                                        <th class="text-capitalize"><strong>Debit</strong></th>
                                                        <th class="text-capitalize"><strong>Credit</strong></th>
                                                        <th class="text-capitalize"><strong>Balance (TZS)</strong></th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $n = 1;
                                                @endphp
                                                <tbody>
                                                    @php
                                                        $balance = $balanceBroughtForward;
                                                        $n = 1;
                                                    @endphp

                                                    <tr>
                                                        <td>#</td>
                                                        <td class="text-primary">
                                                            {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</td>
                                                        <td>Balance Brought Forward <strong
                                                                class="text-secondary">(B/Forward)</strong></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-primary">
                                                            <strong>{{ number_format($balance, 2) }}</strong>
                                                        </td>
                                                    </tr>

                                                    @foreach ($statements as $statement)
                                                        @php
                                                            if ($statement->accType == 'Cr') {
                                                                $balance -= $statement->amount;
                                                            } elseif ($statement->accType == 'Dr') {
                                                                $balance += $statement->amount;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $n++ }}</td>
                                                            <td class="text-primary">
                                                                {{ \Carbon\Carbon::parse($statement->date)->format('M d, Y') }}
                                                            </td>
                                                            <td>
                                                                {{ $statement->expName }} -
                                                                <a href="#"
                                                                    class="text-secondary">{{ $statement->decription }}</a>
                                                            </td>
                                                            <td class="text-success">
                                                                @if ($statement->accType == 'Dr')
                                                                    {{ number_format($statement->amount, 2) }}
                                                                @endif
                                                            </td>
                                                            <td class="text-warning">
                                                                @if ($statement->accType == 'Cr')
                                                                    {{ number_format($statement->amount, 2) }}
                                                                @endif
                                                            </td>
                                                            <td class="text-primary">
                                                                <strong>{{ number_format($balance, 2) }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td class="text-nowrap" colspan="3"><strong>Total (TZS)</strong></td>
                                                        <td class="text-success"><strong>{{ number_format($totalDr, 2) }}</strong></td>
                                                        <td class="text-warning"><strong>{{ number_format($totalCr, 2) }}</strong></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <a href="{{ route('download.bank.statement', [
                                                    'bank_id' => request('bank_id'),
                                                    'fromDate' => $fromDate,
                                                    'toDate' => $toDate,
                                                ]) }}"
                                                    class="btn btn-primary" target="_blank">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#basic-datatables0');
        new DataTable('#basic-datatables00');
    </script>
@stop
