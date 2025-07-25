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
                                    <h4 class="p-3 fs-5">Budgets</h4>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-secondary btn-sm float-end mt-3" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"><i class="fa fa-plus"></i> New Project</button>
                                </div>
                            </div>
                            <x-messages />
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Budget List</button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Projects List</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-new-data" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Create New Budget</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-list" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatablesxxx" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Budget Year</th>
                                                    <th>Budget Name</th>
                                                    <th>Budget Code</th>
                                                    <th>Project</th>
                                                    <th>Cost Type</th>
                                                    <th>Sub-Codes</th>
                                                    <th>Budget Cost</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($budgets as $budget)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap">{{ $budget->budgetYear }}</td>
                                                        <td class="text-nowrap">{{ $budget->budgetName }}</td>
                                                        <td class="text-nowrap">{{ $budget->budgetCode }}</td>
                                                        <td class="text-nowrap">{{ $budget->projectName }}</td>
                                                        <td class="text-nowrap">{{ $budget->costType }}</td>
                                                        <td class="text-nowrap">{{ number_format($budget->subCodes) }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $budget->currency . ' ' . number_format($budget->totalBudgetCost, 2) }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            @if ($budget->status == false)
                                                                <button class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-circle-notch fa-spin"></i> Under Review
                                                                </button>
                                                            @else
                                                                <i class="fas fa-check-circle text-success"></i>
                                                                Approved
                                                            @endif
                                                        </td>
                                                        @php
                                                            $encryptedId = Crypt::encrypt($budget->autoId);
                                                        @endphp
                                                        <td class="text-nowrap">
                                                            <a href="{{ route('view.budget', $encryptedId) }}"
                                                                class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table id="basic-datatables0x" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Project Name</th>
                                                    <th>Date Created</th>
                                                    <th>Edit</th>
                                                    <th>Erase</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($projects as $project)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap">{{ $project->name }}</td>
                                                        <td class="text-nowrap">
                                                            {{ \Carbon\Carbon::parse($project->created_at)->format('M d, Y') }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <button class="btn btn-primary btn-sm"><i
                                                                    class="fa fa-edit"></i></button>
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <button class="btn btn-sm"
                                                                style="background-color: red; color: #FFF;"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-new-data" role="tabpanel"
                                    aria-labelledby="nav-profile-tab">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button btn-primary" style="color: #FFF;"
                                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                    aria-expanded="true" aria-controls="collapseOne">
                                                    Create Single Budget Code
                                                </button>
                                            </h2>

                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    @include('partials.single-budget')
                                                </div>
                                            </div>

                                            <div class="accordion-item mt-2">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button btn-primary collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                        aria-expanded="false" aria-controls="collapseTwo"
                                                        style="color: #FFF;">
                                                        Create Bulk Budget Codes
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo" class="accordion-collapse collapse"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        @include('partials.bulk-budget')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Project</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('store.project') }}" method="POST" class="modal-body">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">Name</span>
                                        <input type="text" class="form-control" id="type"
                                            aria-label="project-name" aria-describedby="inputGroup-sizing-default"
                                            placeholder="project name" name="project_name" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sm">Save Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#basic-datatables0x');
        new DataTable('#basic-datatablesxxx');
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('budget-container');
            const addBtn = document.getElementById('add-row');
            const removeBtn = document.getElementById('remove-row');

            addBtn.addEventListener('click', () => {
                const firstRow = container.querySelector('.sub-budget-entry');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelectorAll('input').forEach(input => input.value = '');
                container.appendChild(newRow);
            });

            removeBtn.addEventListener('click', () => {
                const rows = container.querySelectorAll('.sub-budget-entry');
                if (rows.length > 1) {
                    rows[rows.length - 1].remove();
                }
            });
        });
    </script>

@stop
