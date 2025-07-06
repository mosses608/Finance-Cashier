@extends('layouts.part')
@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h4 class="fs-5">Budget Review</h4>
                            <form class="row mt-3 mb-0" action="{{ route('budget.review') }}" method="GET">
                                <div class="col-5 mb-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">Project</span>
                                        <select class="form-control" aria-label="Sizing example input" name="searchProject"
                                            aria-describedby="inputGroup-sizing-default" required>
                                            <option value="" selected disabled>--select
                                                project name--
                                            </option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project }}">{{ $project }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-5 mb-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">Year</span>
                                        <select class="form-control" aria-label="Sizing example input" name="searchYear"
                                            aria-describedby="inputGroup-sizing-default" required>
                                            <option value="" selected disabled>--select
                                                budget year--
                                            </option>
                                            @foreach ($budgetYears as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2 mb-3">
                                    <div class="input-group mb-3">
                                        <button type="submit" class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                            @if (isset($budget) && $budget != null)
                                <div class="row p-3 py-0 mb-0">
                                    <hr>
                                    <div class="col-4">
                                        {{ 'Budget Name: ' }} <strong
                                            style="color: #0000FF;">{{ $budget->budget_name }}</strong>
                                    </div>
                                    <div class="col-4">
                                        {{ 'Project: ' }} <strong
                                            style="color: #0000FF;">{{ $budget->project_name }}</strong>
                                    </div>
                                    <div class="col-4">
                                        {{ 'Budget Year: ' }} <strong
                                            style="color: #0000FF;">{{ $budget->budget_year }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('approve.budget') }}" method="POST" class="card-body">
                            @csrf
                            @method('PUT')
                            <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                aria-labelledby="nav-home-tab">
                                @if (isset($budget) && $budget != null)
                                    <div class="table-responsive">
                                        <table id="basic-datatables-01x" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th class="text-nowrap">Sub-Budget Code</th>
                                                    <th class="text-nowrap">Description</th>
                                                    <th class="text-nowrap">Unit Cost</th>
                                                    <th class="text-nowrap">Quantity</th>
                                                    <th class="text-nowrap">Unit Measure</th>
                                                    <th class="text-nowrap">Budget Amount</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $totalBudgetAmount = 0;
                                            @endphp
                                            <tbody>
                                                @foreach ($subudgets as $subBudgetItem)
                                                    @php
                                                        $totalBudgetAmount +=
                                                            $subBudgetItem->unit_cost * $subBudgetItem->quantity;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-nowrap">{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap">{{ $subBudgetItem->sub_budget_code }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $subBudgetItem->sub_budget_description }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <input type="text" class="form-control" style="width: 150px;"
                                                                id="exampleFormControlInput1" name="unit_cost[]"
                                                                value="{{ number_format($subBudgetItem->unit_cost, 2) }}"
                                                                placeholder="{{ number_format($subBudgetItem->unit_cost, 2) }}">
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <input type="text" class="form-control text-center"
                                                                id="exampleFormControlInput1" name="quantity[]"
                                                                value="{{ $subBudgetItem->quantity }}"
                                                                placeholder="{{ number_format($subBudgetItem->quantity) }}">
                                                        </td>
                                                        <td class="text-nowrap">{{ $subBudgetItem->unit_meausre }}</td>
                                                        <td class="text-nowrap">
                                                            <input type="hidden" name="sub_budget_code[]"
                                                                value="{{ $subBudgetItem->sub_budget_code }}">

                                                            <input type="number" class="form-control"
                                                                style="width: 160px; color: #000; font-weight: 900;"
                                                                id="exampleFormControlInput1"
                                                                value="{{ number_format($subBudgetItem->unit_cost * $subBudgetItem->quantity, 2) }}"
                                                                placeholder="{{ number_format($subBudgetItem->unit_cost * $subBudgetItem->quantity, 2) }}"
                                                                disabled>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6">Total in <strong
                                                            style="color: #0000FF;">{{ $budget->currency }}</strong>
                                                    </td>
                                                    <td class="text-nowrap text-center">
                                                        <strong
                                                            style="color: #0000FF;">{{ number_format($totalBudgetAmount, 2) }}</strong>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="6"><strong>Budget Status</strong></td>
                                                    <td class="text-center text-nowrap">
                                                        @if ($budget->is_approved == false)
                                                            <button class="btn btn-warning btn-sm">
                                                                <i class="fas fa-circle-notch fa-spin"></i> In Review
                                                            </button>
                                                        @else
                                                            <button class="btn btn-primary btn-sm">
                                                                <i class="fas fa-check-circle text-success"></i> Budget
                                                                Approved
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @else
                                    <center>
                                        <span class="mt-3 mb-3 p-3">Budget Preview will appear here!</span>
                                    </center>
                                @endif
                            </div>
                            @if ($budget && $budget->is_approved == false)
                                <div class="row mt-4 p-3">
                                    @php
                                        $encryptedProject = Crypt::encrypt($budget->project_name);
                                        $encryptedBudgetYear = Crypt::encrypt($budget->budget_year);
                                    @endphp
                                    <input type="hidden" name="project_name" id=""
                                        value="{{ $encryptedProject }}">
                                    <input type="hidden" name="budget_year" id=""
                                        value="{{ $encryptedBudgetYear }}">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ Crypt::encrypt(1) }}" id="checkDefault" name="confirm"
                                                required>
                                            <label class="form-check-label" for="checkDefault">
                                                I, hereby approve this budget.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <button type="submit" class="btn btn-primary float-start">Approve Budget</button>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
