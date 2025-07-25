@extends('layouts.part')
@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <x-messages />
                            <h4 class="fs-5">Budget Code Roll Out</h4>
                            @if ($budget === null)
                                <form class="row mt-3 mb-0" action="{{ route('budget.roll.out') }}" method="GET">
                                    <div class="col-3 mb-3">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">Project</span>
                                            <select class="form-control" aria-label="Sizing example input"
                                                name="searchProject" aria-describedby="inputGroup-sizing-default" required>
                                                <option value="" selected disabled>--select
                                                    project--
                                                </option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project }}">{{ $project }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">Code</span>
                                            <select class="form-control" aria-label="Sizing example input" name="searchCode"
                                                aria-describedby="inputGroup-sizing-default" id="searchCode" required>
                                                <option value="" selected disabled>--
                                                    budget code--
                                                </option>
                                                @foreach ($budgetCodes as $code)
                                                    <option value="{{ $code->budget_code }}"
                                                        data-year="{{ $code->budget_year }}">{{ $code->budget_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">Current
                                                Year</span>
                                            <input type="year" class="form-control" aria-label="Sizing example input"
                                                name="searchYear" aria-describedby="inputGroup-sizing-default"
                                                placeholder="budget year" id="searchYear" required>
                                        </div>
                                    </div>
                                    <script>
                                        document.getElementById('searchCode').addEventListener('change', function() {
                                            const selectOption = this.options[this.selectedIndex];

                                            const selectedValue = selectOption.getAttribute('data-year');

                                            document.getElementById('searchYear').value = selectedValue;
                                        });
                                    </script>
                                    <div class="col-3 mb-3">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">New Year</span>
                                            <select class="form-control" aria-label="Sizing example input" name="newYear"
                                                aria-describedby="inputGroup-sizing-default" required>
                                                <option value="{{ \Carbon\Carbon::now()->format('Y') + 1 }}">
                                                    {{ \Carbon\Carbon::now()->format('Y') + 1 }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2 mb-3">
                                        <div class="input-group mb-3">
                                            <button type="submit" class="btn btn-primary float-end"
                                                type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            @if (isset($budget) && $budget != null)
                                <div class="row p-3 py-0 mb-0">
                                    <hr>
                                    <div class="col-3">
                                        {{ 'Budget Name: ' }} <strong
                                            style="color: #0000FF;">{{ $budget->budget_name }}</strong>
                                    </div>
                                    <div class="col-3">
                                        {{ 'Project: ' }} <strong
                                            style="color: #0000FF;">{{ $budget->project_name }}</strong>
                                    </div>
                                    <div class="col-3">
                                        {{ 'Budget Year: ' }} <strong
                                            style="color: #0000FF;">{{ $budget->budget_year }}</strong>
                                    </div>
                                    <div class="col-3">
                                        {{ 'New Budget Year: ' }} <strong
                                            style="color: #0000FF;">{{ $budget->budget_year + 1 }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if (isset($budget) && $budget != null)
                            <form action="{{ route('budget.roll.out') }}" method="POST" class="card-body">
                                @csrf
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <input type="hidden" name="currency" id="" value="{{ $budget->currency }}">
                                    <input type="hidden" name="new_budget_year" id=""
                                        value="{{ $newYear }}">
                                    <input type="hidden" name="budget_name" id=""
                                        value="{{ $budget->budget_name }}">
                                    <input type="hidden" name="budget_year" id=""
                                        value="{{ $budget->budget_year }}">
                                    <input type="hidden" name="project_name" id=""
                                        value="{{ $budget->project_name }}">
                                    <input type="hidden" name="old_budget_code" id=""
                                        value="{{ $budget->budget_code }}">
                                    @if (isset($budget) && $budget != null)
                                        <div class="table-responsive">
                                            <table id="basic-datatables-01x"
                                                class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="text-nowrap">Sub-Budget Code</th>
                                                        <th class="text-nowrap">Description</th>
                                                        <th class="text-nowrap">Unit Cost</th>
                                                        <th class="text-nowrap">Quantity</th>
                                                        <th class="text-nowrap">Unit Measure</th>
                                                        <th class="text-nowrap">Budget Amount <strong class="text-secondary">({{ $budget->currency }})</strong></th>
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
                                                            <td>
                                                                <input type="checkbox" name="sub_budget_code[]"
                                                                    class="row-check"
                                                                    value="{{ $subBudgetItem->sub_budget_code }}">
                                                            </td>
                                                            <td>{{ $subBudgetItem->sub_budget_code }}</td>
                                                            <td>
                                                                <input type="hidden" name="sub_budget_description[]"
                                                                    value="{{ $subBudgetItem->sub_budget_description }}">
                                                                {{ $subBudgetItem->sub_budget_description }}
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control"
                                                                    style="width: 150px;" name="unit_cost[]"
                                                                    value="{{ $subBudgetItem->unit_cost }}">
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    class="form-control text-center quantity-input"
                                                                    name="quantity[]"
                                                                    value="{{ $subBudgetItem->quantity }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control text-center"
                                                                    name="unit_meausre[]"
                                                                    value="{{ $subBudgetItem->unit_meausre }}">
                                                            </td>
                                                            <td class="text-center text-secondary">
                                                                {{-- <input type="text" class="form-control"
                                                                    value="{{ number_format($subBudgetItem->unit_cost * $subBudgetItem->quantity, 2) }}"
                                                                    disabled> --}}
                                                                <strong>
                                                                    {{ number_format($subBudgetItem->unit_cost * $subBudgetItem->quantity, 2) }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                document.querySelectorAll('.row-check').forEach(function(checkbox) {
                                                    checkbox.addEventListener('change', function() {
                                                        const row = this.closest('tr');
                                                        row.querySelectorAll('input:not([type="checkbox"])').forEach(input => {
                                                            input.disabled = !this.checked;
                                                        });
                                                    });

                                                    checkbox.dispatchEvent(new Event('change'));
                                                });
                                            });
                                        </script>

                                        <div class="row mb-3 mt-3 p-3">
                                            <div class="col-4">
                                                <input type="text" class="form-control" id="exampleFormControlInput1"
                                                    name="budget_code" placeholder="enter new budget code" required>
                                            </div>
                                            <div class="col-8">
                                                <div class="form-check float-end">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ Crypt::encrypt(1) }}" id="checkDefault" name="confirm"
                                                        required>
                                                    <label class="form-check-label text-primary" for="checkDefault">
                                                        I, hereby confirm budget code roll out.
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save"></i>
                                                    Save
                                                    Data</button>
                                            </div>
                                        </div>
                                    @else
                                        <center>
                                            <span class="mt-3 mb-3 p-3">Budget Preview will appear here!</span>
                                        </center>
                                    @endif
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
