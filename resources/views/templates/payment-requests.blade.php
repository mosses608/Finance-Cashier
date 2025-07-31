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
                                    <h4 class="p-3 fs-5">Payment Requests | Budget Year : <strong
                                            style="color: #0000FF;">{{ \Carbon\Carbon::now()->year }}</strong></h4>
                                </div>
                                {{-- <div class="col-6">
                                    <button class="btn btn-secondary btn-sm float-end mt-3" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"><i class="fa fa-plus"></i> Add New Branch</button>
                                </div> --}}
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Payment Requests List</button>
                                {{-- <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Record Expenses</button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <form action="{{ route('approve.payment.request') }}" method="POST">
                                        @csrf
                                        <div class="table-responsive">
                                            <table id="basic-datatables" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="checkAll"></th>
                                                        <th>CR</th>
                                                        <th>DR</th>
                                                        <th class="text-nowrap">Expense Name</th>
                                                        <th>Amount</th>
                                                        <th class="text-nowrap">Date Due</th>
                                                        <th class="text-nowrap">Created By</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($expenses as $exp)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="check_payment[]"
                                                                    value="{{ $exp->expenseId }}" class="checkItem">
                                                            </td>

                                                            <td>
                                                                <input type="checkbox" name="cr[{{ $exp->expenseId }}]"
                                                                    class="cr">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="dr[{{ $exp->expenseId }}]"
                                                                    class="dr">
                                                            </td>
                                                            <td class="text-nowrap">{{ $exp->exName }}</td>
                                                            <td class="text-nowrap text-start text-secondary">
                                                                <strong>{{ number_format($exp->amount, 2) }}</strong>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{ \Carbon\Carbon::parse($exp->dueDate)->format('M d, Y') }}
                                                            </td>
                                                            <td class="text-nowrap">{{ $exp->staffName }}</td>
                                                            <td class="text-start text-primary text-nowrap"><i
                                                                    class="fas fa-spinner fa-spin text-warning"></i> <span
                                                                    style="font-size: 12px;">waiting for approval</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr>
                                        <div class="row mt-3 py-3">
                                            <div class="col-6">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Bank
                                                        Account</span>
                                                    <select type="text" class="form-control" id="bank_id"
                                                        name="bank_id" aria-label="bank_id"
                                                        aria-describedby="inputGroup-sizing-default" required>
                                                        <option value="" selected disabled>--select
                                                            bank account--
                                                        </option>
                                                        @foreach ($accountBalanceData as $account)
                                                            <option value="{{ $account->bankId }}"
                                                                data-balance="{{ $account->current_balance }}">
                                                                {{ $account->bank_name . ' ' . ' - ' . ' ' . $account->account_number }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">
                                                        Account Balance</span>
                                                    <input type="text" class="form-control" id="account_balance"
                                                        name="" aria-label="account balance"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="available bank balance" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="approve" type="checkbox"
                                                        value="approve" id="checkDefault" required>
                                                    <label class="form-check-label" for="checkDefault">
                                                        <strong class="text-primary p-2">Check me to approve this payment
                                                            request...</strong>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-1 mb-3">
                                                <button type="submit" class="btn btn-primary"><i
                                                        class="fas fa-thumbs-up"></i> Approve Payments</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <form action="{{ route('expense.store') }}" method="POST">
                                        @csrf
                                        <h4 class="p-2 mt-0 fs-5"><strong style="color: #007BFF;"><i
                                                    class="fa fa-check"></i></strong> Expenses Records
                                        </h4>
                                        <div class="row">
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Budget</span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        name="sub_budget_id" aria-describedby="inputGroup-sizing-default"
                                                        required>
                                                        <option value="" selected disabled>--select
                                                            sub-budget
                                                            code--
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Expense</span>
                                                    <input type="text" class="form-control"
                                                        aria-label="Sizing example input" name="expense_name"
                                                        aria-describedby="inputGroup-sizing-default" required
                                                        placeholder="expense name">
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Type</span>
                                                    <select class="form-control" aria-label="Sizing example input"
                                                        name="expense_type" aria-describedby="inputGroup-sizing-default">
                                                        <option value="" selected disabled>--select
                                                            expense
                                                            type--
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Amount</span>
                                                    <input type="text" class="form-control"
                                                        aria-label="Sizing example input" name="amount"
                                                        aria-describedby="inputGroup-sizing-default" placeholder="amount">
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Ref
                                                        No</span>
                                                    <input type="text" class="form-control" id="code"
                                                        aria-label="code" name="reference_no"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        placeholder="reference number">
                                                </div>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Date
                                                    </span>
                                                    <input type="text" class="form-control" id="code"
                                                        aria-label="code" name="expense_date"
                                                        aria-describedby="inputGroup-sizing-default"
                                                        value="{{ \Carbon\Carbon::now()->format('M d, Y') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"
                                                        id="inputGroup-sizing-default">Description
                                                    </span>
                                                    <textarea class="form-control" id="code" aria-label="code" name="description"
                                                        aria-describedby="inputGroup-sizing-default" placeholder="a litle description about the expsnse">
                                                    </textarea>
                                                </div>
                                            </div>

                                            <div class="col-4 mb-3">
                                                <button type="submit" class="btn btn-primary">Submit</button>
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
    </div>

    <script>
        new DataTable('#basic-datatables');
        new DataTable('#basic-datatables1');
        new DataTable('#basic-datatables10');
        new DataTable('#basic-datatables11');
    </script>


    <script>
        document.getElementById('bank_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            const availableBalance = selectedOption.getAttribute('data-balance');

            document.getElementById('account_balance').value = availableBalance;
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.checkItem');

            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = checkAll.checked);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.checkItem');

            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = checkAll.checked);
            });

            document.querySelectorAll('tr').forEach(row => {
                const cr = row.querySelector('.cr');
                const dr = row.querySelector('.dr');

                if (cr && dr) {
                    cr.addEventListener('change', function() {
                        if (this.checked) dr.checked = false;
                    });

                    dr.addEventListener('change', function() {
                        if (this.checked) cr.checked = false;
                    });
                }
            });
        });
    </script>


@stop
