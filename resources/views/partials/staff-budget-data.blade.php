<div class="row">
    <div class="col-6">
        Project Name: <strong style="color: #007bff;">{{ $projectName }}</strong>
    </div>
    <div class="col-6">
        Budget Year: <strong style="color: #007bff">{{ $budgetYear }}</strong>
    </div>
</div>

<form action="{{ route('staff.budget.codes') }}" method="POST" class="mt-3" id="budgetForm">
    @csrf
    <div id="formContainer">
        <div class="row row-group mb-3">
            <input type="hidden" name="project_name" id="" value="{{ $projectName }}">
            <input type="hidden" name="budget_year" id="" value="{{ $budgetYear }}">
            <div class="col-3">
                <div class="input-group">
                    <span class="input-group-text">Staff</span>
                    <select class="form-control staff-select" name="staff_id[]" required>
                        <option value="" selected disabled>--staff name--</option>
                        @foreach ($emplyees as $employee)
                            <option value="{{ $employee->id }}" data-salary="{{ $employee->salary_amount }}">
                                {{ $employee->first_name . ' ' . $employee->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="input-group">
                    <span class="input-group-text">Cost</span>
                    <input type="number" class="form-control" name="budget_cost[]" required placeholder="budget cost">
                </div>
            </div>
            <div class="col-3">
                <div class="input-group">
                    <span class="input-group-text">Budget</span>
                    <select class="form-control budget-select" name="budget_code[]" required>
                        <option value="" selected disabled>--budget code--</option>
                        @foreach ($budgets as $budget)
                            <option value="{{ $budget->id }}" data-subbudget="{{ $budget->sub_budget_code }}">
                                {{ $budget->budget_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="input-group">
                    <span class="input-group-text">Sub-budget</span>
                    <input type="text" class="form-control" name="sub_budget_code[]" required
                        placeholder="sub budget code">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-2">
            <button type="button" class="btn btn-primary btn-sm" id="appendRow"><i class="fa fa-plus"></i> row</button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger btn-sm" id="removeRow"><i class="fa fa-minus"></i> row</button>
        </div>
        <div class="col-8 text-end">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>
</form>
