@extends('layouts.part')
@include('partials.nav-bar')
@section('content')

    <div class="container mb-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <div class="col-4 p-2">
                                Budget Name: <strong style="color: #0000FF;">{{ $budget->budget_name }}</strong>
                            </div>
                            <div class="col-4 p-2">
                                Project Name: <strong style="color: #0000FF;">{{ $budget->project_name }}</strong>
                            </div>
                            <div class="col-4 p-2">
                                Budget Status:
                                @if ($budget->status == false)
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fas fa-sync-alt fa-spin"></i> In Review
                                    </button>
                                @else
                                    Approved
                                @endif
                            </div>
                            {{-- <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Budget List List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Projects List</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Create New Budget</button>
                            </div> --}}
                        </div>
                        <x-messages />
                        <div class="card-body">
                            <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                aria-labelledby="nav-home-tab">
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
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $totalBudgetAmount = 0;
                                        @endphp
                                        <tbody>
                                            @foreach ($subBudgets as $subBudgetItem)
                                                @php
                                                    $totalBudgetAmount +=
                                                        $subBudgetItem->unit_cost * $subBudgetItem->quantity;
                                                @endphp
                                                <tr>
                                                    <td class="text-nowrap">{{ $loop->iteration }}</td>
                                                    <td class="text-nowrap">{{ $subBudgetItem->sub_budget_code }}</td>
                                                    <td class="text-nowrap">{{ $subBudgetItem->sub_budget_description }}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        {{ number_format($subBudgetItem->unit_cost, 2) }}</td>
                                                    <td class="text-nowrap">
                                                        {{ number_format($subBudgetItem->quantity) }}</td>
                                                    <td class="text-nowrap">{{ $subBudgetItem->unit_meausre }}</td>
                                                    <td class="text-nowrap">
                                                        {{ number_format($subBudgetItem->unit_cost * $subBudgetItem->quantity, 2) }}
                                                    </td>
                                                    <td class="text-nowrap text-center">
                                                        <form action="{{ route('remove.sub.budget') }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            @php
                                                                $encryptedSubBudgetId = Crypt::encrypt(
                                                                    $subBudgetItem->id,
                                                                );
                                                            @endphp
                                                            <input type="hidden" name="sub_budget_id" id=""
                                                                value="{{ $encryptedSubBudgetId }}">
                                                            <button type="submit" class="btn btn-sm"
                                                                style="background-color: red; color: #FFF;"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </form>
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
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row p-3 mt-3">

                                    <form action="{{ route('add.sub.codes') }}" method="POST">
                                        @csrf
                                        <div id="budget-container">

                                        </div>
                                        <input type="hidden" name="budget_code" id=""
                                            value="{{ $budget->budget_code }}">
                                        <input type="hidden" name="project_name" id=""
                                            value="{{ $budget->project_name }}">
                                        <div class="row mb-3 mt-1">
                                            <div class="col-6">
                                                <button type="button" class="btn btn-primary btn-sm mb-2" id="add-row"><i
                                                        class="fa fa-plus"></i>
                                                    Add Sub-budget Code</button>
                                                <button type="button" class="btn btn-sm"
                                                    style="background-color: red; color: #FFF; display: none;"
                                                    id="remove-row"><i class="fa fa-minus"></i>
                                                    Remove Row</button>
                                            </div>

                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary float-end btn-sm"
                                                    id="sbt-btn" style="display: none;"><i class="fa fa-save"></i> Save
                                                    Data</button>
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
        new DataTable('#basic-datatables0x');
        new DataTable('#basic-datatables-01x');
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('add-row');
            const removeBtn = document.getElementById('remove-row');
            const container = document.getElementById('budget-container');
            const submitBtn = document.getElementById('sbt-btn');

            removeBtn.style.display = 'none';
            submitBtn.style.display = 'none';

            function createSubBudgetRow() {
                return `
                <div class="row sub-budget-entry">
                    <div class="col-6 mb-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Code</span>
                            <input type="text" class="form-control" name="sub_budget_code[]" placeholder="sub-budget code">
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Description</span>
                            <input type="text" class="form-control" name="sub_budget_description[]" placeholder="sub-budget description">
                        </div>
                    </div>
                    <div class="col-4 mb-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Cost</span>
                            <input type="number" class="form-control" name="unit_cost[]" placeholder="unit cost of sub-budget code">
                        </div>
                    </div>
                    <div class="col-4 mb-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Quantity</span>
                            <input type="text" class="form-control" name="quantity[]" placeholder="quantity of sub-budget code">
                        </div>
                    </div>
                    <div class="col-4 mb-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Measure</span>
                            <input type="text" class="form-control" name="unit_meausre[]" placeholder="unit measue of sub-budget code">
                        </div>
                    </div>
                </div>
            `;
            }

            addBtn.addEventListener('click', function() {
                container.insertAdjacentHTML('beforeend', createSubBudgetRow());
                removeBtn.style.display = 'block';
                submitBtn.style.display = 'block';
            });

            removeBtn.addEventListener('click', function() {
                const allRows = container.querySelectorAll('.sub-budget-entry');
                if (allRows.length > 0) {
                    allRows[allRows.length - 1].remove();
                }
            });
        });
    </script>
@stop
