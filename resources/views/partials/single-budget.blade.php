<form action="{{ route('budget.create') }}" method="POST">
    @csrf
    <h4 class="p-2 mt-0 fs-5"><strong style="color: #007BFF;"><i class="fa fa-check"></i></strong> Budget Information
    </h4>
    <div class="row">
        <div class="col-4 mb-3">
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Year</span>
                <select class="form-control" aria-label="Sizing example input" name="budget_year"
                    aria-describedby="inputGroup-sizing-default" required>
                    <option value="" selected disabled>--select
                        budget
                        year--
                    </option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">
                            {{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-4 mb-3">
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Currency</span>
                <select class="form-control" aria-label="Sizing example input" name="currency"
                    aria-describedby="inputGroup-sizing-default" required>
                    <option value="" selected disabled>--select
                        currency--
                    </option>
                    <option value="TZS">TZS</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
        </div>
        <div class="col-4 mb-3">
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Cost</span>
                <select class="form-control" aria-label="Sizing example input" name="cost_type"
                    aria-describedby="inputGroup-sizing-default">
                    <option value="" selected disabled>--select
                        cost
                        type--
                    </option>
                    @foreach ($costTypes as $cost)
                        <option value="{{ $cost->name }}">
                            {{ $cost->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-4 mb-3">
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Budget</span>
                <input type="text" class="form-control" aria-label="Sizing example input" name="budget_name"
                    aria-describedby="inputGroup-sizing-default" placeholder="budget name">
            </div>
        </div>
        <div class="col-4 mb-3">
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Code</span>
                <input type="text" class="form-control" id="code" aria-label="code" name="budget_code"
                    aria-describedby="inputGroup-sizing-default" placeholder="budget code">
            </div>
        </div>
        <div class="col-4 mb-3">
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Project</span>
                <select type="text" class="form-control" id="project" name="project_name" aria-label="Project Id"
                    aria-describedby="inputGroup-sizing-default">
                    <option value="" selected disabled>--select
                        project--
                    </option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->name }}">
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <h4 class="p-2 mt-0 fs-5"><strong style="color: #007BFF;"><i class="fa fa-check"></i></strong> Sub-Budget
            Information</h4>

        <div id="budget-container">
            <div class="row sub-budget-entry">
                <div class="col-6 mb-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Code</span>
                        <input type="text" class="form-control" name="sub_budget_code[]"
                            placeholder="sub-budget code">
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Description</span>
                        <input type="text" class="form-control" name="sub_budget_description[]"
                            placeholder="sub-budget description">
                    </div>
                </div>

                <div class="col-4 mb-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Cost</span>
                        <input type="number" class="form-control" name="unit_cost[]"
                            placeholder="unit cost of sub-budget code">
                    </div>
                </div>

                <div class="col-4 mb-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Quantity</span>
                        <input type="text" class="form-control" name="quantity[]"
                            placeholder="quantity of sub-budget code">
                    </div>
                </div>

                <div class="col-4 mb-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Measure</span>
                        <input type="text" class="form-control" name="unit_meausre[]"
                            placeholder="unit measue of sub-budget code">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-12 mb-3">
            <button type="button" class="btn btn-primary btn-sm" id="add-row"><i class="fa fa-plus"></i>
                Row</button>
            <button type="button" class="btn btn-sm" style="background-color: red; color: #FFF;" id="remove-row"><i
                    class="fa fa-minus"></i>
                Row</button>
            <button class="btn btn-primary float-end btn-sm"><i class="fa fa-save"></i> Save
                Data</button>
        </div>
    </div>
</form>
