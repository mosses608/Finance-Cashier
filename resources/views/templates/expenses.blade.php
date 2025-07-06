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
                                    <h4 class="p-3 fs-5">Expenses | Budget Year : <strong
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
                                    aria-selected="true">Expenses List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Record Expenses</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th class="text-nowrap">Expense Name</th>
                                                    <th class="text-nowrap">Expense Type</th>
                                                    <th class="text-nowrap">Sub-Budget Code</th>
                                                    <th>Amount</th>
                                                    <th class="text-nowrap">Ref No</th>
                                                    <th class="text-nowrap">Date Due</th>
                                                    <th class="text-nowrap">Created By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($expenses as $exp)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap">{{ $exp->exName }}</td>
                                                        <td class="text-nowrap">{{ $exp->exType }}</td>
                                                        <td class="text-nowrap">{{ $exp->description . ' , ' .  $exp->subBudgetCode }}</td>
                                                        <td class="text-nowrap">{{ number_format($exp->amount, 2) }}</td>
                                                        <td class="text-nowrap">{{ $exp->refNo ?? '###' }}</td>
                                                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($exp->dueDate)->format('M d, Y') }}</td>
                                                        <td class="text-nowrap">{{ $exp->staffName }}</td>
                                                        <td class="text-center"><a class="btn btn-primary btn-sm" href="#"><i class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
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
                                                        @foreach ($subBudgets as $item)
                                                            <option value="{{ $item->subBudgetId }}">{{ $item->description . ' , ' .  $item->sub_budget_code }}</option>
                                                        @endforeach
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
                                                        @foreach ($expensesTypes as $expense)
                                                            <option value="{{ $expense->id }}">{{ $expense->name }}</option>
                                                        @endforeach
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
                                                    <span class="input-group-text" id="inputGroup-sizing-default">Description
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

@stop
