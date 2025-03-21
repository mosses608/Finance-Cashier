<div class="md-4 mt-0">
    <h3>{{ __('Ledger Group') }}</h3>
    <form action="{{ route('ledgers.group') }}" method="POST" class="form-group" id="form-grouper">
        @csrf

        
        <div class="input-field" style="width: 100%;">
            <label for="">Group Type</label>
            <select name="group_type" id="">
                <option value="" selected disabled>--select--</option>
                <option value="Assets">Assets</option>
                <option value="Liabilities">Liabilities</option>
            </select>
        </div>
        
        <div class="input-field" style="width: 100%;">
            <label for="">Ledger Group</label>
            <input type="text" name="group_name" id="name" placeholder="Ledger Group Name" required>
        </div>

        <div class="input-field">
            <button type="submit" class="btn primart-btn">Save Data</button>
        </div>
        
    </form>
</div>

<div class="md-5">
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>S/N</th>
                <th>Group Name</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ledgerGroups as $lgroup)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $lgroup->group_name }}</td>
                <td>{{ $lgroup->group_type }}</td>
                <td class="action-btn">
                    <button class="edit-usr" style="color: #008800;"><i class="fa fa-pencil"></i></button>
                    <button class="delete-usr" style="color: red;"><i class="fa fa-trash"></i></button> 
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>