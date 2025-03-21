<div class="container-form" id="container-form">
    <h3>{{ ('Create Journal') }}</h3>
    <button type="button" class="close" onclick="hide(event)">&times;</button>
    <br><hr>
    <form action="{{ route('store.journals') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="input-field">
            <label for="">Date</label>
            <input type="date" name="date" id="">
        </div>

        <!-- Profile Image -->
        <div class="input-field">
            <label for="">Account Name</label>
            <select name="ledger_id" id="">
                <option value="" selected disabled>--select a/c--</option>
                @foreach($ledgers as $ledger)
                <option value="{{ $ledger->id }}">{{ $ledger->customer_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-field">
            <label for="">Particular</label>
            <input type="text" name="particular" id="" placeholder="Write a particular note....">
        </div>

        <div class="input-field">
            <label for="">Mode</label>
            <select name="mode" id="">
                <option value="" selected disabled>--select--</option>
                <option value="Cr">Credit</option>
                <option value="Dr">Debit</option>
            </select>
        </div>
<!-- 
        <div class="input-field">
            <label for="">Amount</label>
            <input type="number" name="amount" id="" placeholder="Amount">
        </div> -->
<br>
        <div class="input-field">
            <button type="submit" class="btn primart-btn" id="btn-save">Save Data</button>
        </div>

    </form>
</div>

<script>
    function addJournal(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.querySelector('.container-form');

        blurScreen.style.display='block';
        dataForm.style.display='block';
    }

    function hide(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.querySelector('.container-form');

        blurScreen.style.display='none';
        dataForm.style.display='none';
    }
</script>