<form action="{{ route('store.ledgers') }}" method="POST" class="form-group">
    @csrf

    <div class="input-field">
        <label for="">Date</label>
        <input type="date" name="date" id="date" autofocus="true">
    </div>

    <div class="input-field">
        <label for="">Ledger Name</label>
        <input type="text" name="customer_name" id="customer_name" placeholder="Ledger Name">
    </div>

    <div class="input-field">
        <label for="">Ledger Type</label>
        <input type="text" name="ledger_type" id="ledger_type" placeholder="Ledger Type">
    </div>

    <div class="input-field">
        <label for="">Ledger Group</label>
        <select name="ledger_group" id="">
            <option value="" selected disabled>--select--</option>
            @foreach($ledgerGroups as $group)
            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="input-field">
        <label for="">Mode</label>
        <select name="mode" id="">
            <option value="" selected disabled>--select--</option>
            <option value="Cr">Credit</option>
            <option value="Dr">Debit</option>
        </select>
    </div>

    <div class="input-field">
        <label for="">Amount</label>
        <input type="number" name="amount" id="" placeholder="Amount">
    </div>

    <div class="input-field">
        <button type="submit" class="btn-save primary-btn">Save Data</button>
    </div>
</form>