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
                                    <h4 class="p-3 fs-5">Bank Account Balance</h4>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Account Balance List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Add Bank Balance</button>
                                {{-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Create New Bank</button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <!-- Invoice Tab -->
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables0" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Bank</th>
                                                    <th>Acc Name</th>
                                                    <th>Acc Number</th>
                                                    <th>Currency</th>
                                                    <th>Opening Balance</th>
                                                    <th>Curewnt Balance</th>
                                                    <th>Allow Overdraft</th>
                                                    <th>Overdraft Limit</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                            @endphp
                                            <tbody>
                                                @foreach ($accountBalanceData as $balance)
                                                    @php
                                                        $allowOverdraft = $balance->allow_overdraft;
                                                        $state = null;

                                                        if ($allowOverdraft == 0) {
                                                            $state = 'No';
                                                        } else {
                                                            $state = 'Yes';
                                                        }
                                                    @endphp
                                                    <tr class="text-nowrap">
                                                        <td>{{ $n++ }}</td>
                                                        <td class="text-success">{{ $balance->bank_name }}</td>
                                                        <td>{{ $balance->account_name }}</td>
                                                        <td>{{ $balance->account_number }}</td>
                                                        <td>{{ __('TZS') }}</td>
                                                        <td class="text-primary">
                                                            <strong>{{ number_format($balance->opening_balance, 2) }}</strong>
                                                        </td>
                                                        <td class="text-secondary">
                                                            <strong>{{ number_format($balance->current_balance, 2) }}</strong>
                                                        </td>
                                                        <td>
                                                            {{ $state }}
                                                        </td>
                                                        <td>{{ number_format($balance->overdraft_limit, 2) ?? '' }}</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-primary btn-sm"><i
                                                                    class="fa fa-edit"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <form action="{{ route('bank.balance') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Account</span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        name="bank_id" aria-describedby="inputGroup-sizing-default" required
                                                        id="bankAccount">
                                                        <option value="" selected disabled>--select bank account--
                                                        </option>
                                                        @foreach ($bankData as $itm)
                                                            <option value="{{ $itm->autoId }}"
                                                                data-account-number="{{ $itm->accountNumber }}"
                                                                data-bank="{{ $itm->bankName }}">
                                                                {{ $itm->accountName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Bank</span>
                                                    <input type="string" class="form-control"
                                                        aria-label="Sizing example input" id="bankName"
                                                        aria-describedby="inputGroup-sizing-default" readonly>
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Number</span>
                                                    <input type="number" class="form-control"
                                                        aria-label="Sizing example input" id="account_number"
                                                        aria-describedby="inputGroup-sizing-default" readonly>
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Balance</span>
                                                    <input type="number" class="form-control"
                                                        aria-label="Sizing example input" name="opening_balance"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="opening balance">
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Overdraft</span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        name="allow_overdraft" id="allow_overdraft"
                                                        aria-describedby="inputGroup-sizing-default">
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3" id="overdraft-yes" style="display: none;">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Limit</span>
                                                    <input type="text" class="form-control"
                                                        aria-label="Sizing example input" id="overdraft_limit"
                                                        name="overdraft_limit"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="overdraft limit">
                                                </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <div class="input-group mb-3">
                                                    <button type="submit" class="btn btn-primary">Save Data</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('bankAccount').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            const selectedAccNumer = selectedOption.getAttribute('data-account-number');
            const selectedDataBank = selectedOption.getAttribute('data-bank');

            document.getElementById('bankName').value = selectedDataBank;
            document.getElementById('bankName').textContent = selectedDataBank;

            document.getElementById('account_number').value = selectedAccNumer;
            document.getElementById('account_number').textContent = selectedAccNumer;
        });

        document.getElementById('allow_overdraft').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            const selectedValue = selectedOption.value;

            document.getElementById('overdraft-yes').style.display = 'none';

            if (selectedValue === '1') {
                document.getElementById('overdraft-yes').style.display = 'block';
            }
        });
    </script>

    <script>
        new DataTable('#basic-datatables0');
        new DataTable('#basic-datatables00');
    </script>
@stop
