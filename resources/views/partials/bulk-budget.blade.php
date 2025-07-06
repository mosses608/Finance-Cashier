<form action="{{ route('stakeholder.create') }}" method="POST">
    @csrf
    <h4 class="p-2 mt-0 fs-5"><strong style="color: #007BFF;"><i class="fa fa-check"></i></strong> Budget
        Information
    </h4>
    <div class="row">
        <div class="col-4 mb-3">
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Year</span>
                <select class="form-control" aria-label="Sizing example input" name="budget_year"
                    aria-describedby="inputGroup-sizing-default" required>
                    <option value="" selected disabled>
                        --select
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
                    <option value="" selected disabled>
                        --select
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
                <span class="input-group-text" id="inputGroup-sizing-default">Project</span>
                <select class="form-control" id="project" name="project_id" aria-label="Project Id"
                    aria-describedby="inputGroup-sizing-default">
                    <option value="" selected disabled>
                        --select
                        project--
                    </option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="input-group mb-3">
                <a href="#" type="button" class="btn btn-secondary"><i class="fa fa-download"></i> Sample Budget
                    File</a>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="input-group mb-3">
                {{-- <span class="input-group-text"
                                                                            id="inputGroup-sizing-default">File</span> --}}
                <input type="file" class="form-control" id="project" name="subCodeFile" aria-label="Project Id"
                    aria-describedby="inputGroup-sizing-default" />

            </div>
        </div>

        <div class="col-12 mb-1">
            <div class="input-group mb-1">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Data</button>
            </div>

        </div>
</form>
