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
                                    <h4 class="p-3 fs-5">Staff Budget Codes</h4>
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
                                    aria-selected="true">Staff Budget Codes</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Assign Budget Codes</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>S/N</th>
                                                    <th>Project</th>
                                                    <th>Program</th>
                                                    <th>Budget Code</th>
                                                    <th>Budget Name</th>
                                                    <th>Staff Name</th>
                                                    <th>Sub-Budget Code</th>
                                                    <th>Budget Year</th>
                                                    <th>Cost</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $n = 1;
                                                $totalCost = 0;
                                            @endphp
                                            <tbody>
                                                @foreach ($stafBudgetCodes as $code)
                                                @php
                                                    $totalCost += $code->budget_cost;
                                                @endphp
                                                    <tr class="text-nowrap">
                                                        <td>{{ $n++ }}</td>
                                                        <td>{{ $code->project_name }}</td>
                                                        <td>{{ $code->project_name }}</td>
                                                        <td>{{ $code->budget_code_name }}</td>
                                                        <td>{{ $code->budget_name }}</td>
                                                        <td>{{ $code->first_name . ' ' . $code->last_name}}</td>
                                                        <td>{{ $code->sub_budget_code }}</td>
                                                        <td>{{ $code->budget_year }}</td>
                                                        <td>{{ number_format($code->budget_cost, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8"><strong>Total Staff Budget (TZS)</strong></td>
                                                    <td><strong>{{ number_format($totalCost, 2) }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    @if ($projectName == null && $budgetYear == null)

                                        <form action="{{ route('assign.budget.code') }}" method="GET" id="searchForm"
                                            class="row">

                                            <div class="col-5 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">Project</span>
                                                    <select class="form-control" name="projectSearch" id="projectSearch"
                                                        required>
                                                        <option value="" selected disabled>--project name--</option>
                                                        @foreach ($projects as $proj)
                                                            <option value="{{ $proj->proj }}">{{ $proj->proj }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-5 mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">Year</span>
                                                    <select class="form-control" name="budgetYrSearch" id="budgetYrSearch"
                                                        required>
                                                        <option value="" selected disabled>--budget year--</option>
                                                        @foreach ($years as $yr)
                                                            <option value="{{ $yr->yr }}">{{ $yr->yr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                <div class="input-group mb-3">
                                                    <button type="submit" class="btn btn-primary">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                    <div id="data-result" class="mt-0 mb-2">
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
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const projectSearch = document.getElementById('projectSearch').value;
            const budgetYrSearch = document.getElementById('budgetYrSearch').value;

            const url = this.action + '?projectSearch=' + encodeURIComponent(projectSearch) +
                '&budgetYrSearch=' + encodeURIComponent(budgetYrSearch);

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('data-result').innerHTML = data.html;

                    attachRowEvents();
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        });
    </script>

    <script>
        function attachRowEvents() {
            const appendBtn = document.getElementById("appendRow");
            const removeBtn = document.getElementById("removeRow");
            const formContainer = document.getElementById("formContainer");

            if (!appendBtn || !removeBtn || !formContainer) return;

            appendBtn.addEventListener("click", function() {
                const rowGroups = formContainer.querySelectorAll(".row-group");
                const lastGroup = rowGroups[rowGroups.length - 1];
                const clone = lastGroup.cloneNode(true);

                clone.querySelectorAll("input, select").forEach(el => el.value = '');
                formContainer.appendChild(clone);

                bindStaffChangeEvent();
                bindBudgetChangeEvent();
            });

            removeBtn.addEventListener("click", function() {
                const rowGroups = formContainer.querySelectorAll(".row-group");
                if (rowGroups.length > 1) {
                    rowGroups[rowGroups.length - 1].remove();
                }
            });

            bindStaffChangeEvent();
            bindBudgetChangeEvent();
        }

        function bindStaffChangeEvent() {
            document.querySelectorAll(".staff-select").forEach(select => {
                select.removeEventListener("change", handleStaffChange);
                select.addEventListener("change", handleStaffChange);
            });
        }

        function handleStaffChange(e) {
            const selectedOption = e.target.selectedOptions[0];
            const salary = selectedOption.getAttribute("data-salary");

            const row = e.target.closest(".row-group");
            if (row && salary) {
                const budgetCostInput = row.querySelector('input[name="budget_cost[]"]');
                if (budgetCostInput) {
                    budgetCostInput.value = salary;
                }
            }
        }

        function bindBudgetChangeEvent() {
            document.querySelectorAll(".budget-select").forEach(select => {
                select.removeEventListener("change", handleBudgetChange);
                select.addEventListener("change", handleBudgetChange);
            });
        }

        function handleBudgetChange(e) {
            const selectedOption = e.target.selectedOptions[0];
            const subBudget = selectedOption.getAttribute("data-subbudget");

            const row = e.target.closest(".row-group");
            if (row && subBudget) {
                const subBudgetInput = row.querySelector('input[name="sub_budget_code[]"]');
                if (subBudgetInput) {
                    subBudgetInput.value = subBudget;
                }
            }
        }
    </script>

@stop
