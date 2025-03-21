<div class="container-form" id="container-t">
    <h3>{{ ('Create Transfer') }}</h3>
    <button type="button" class="close" onclick="collapseTrans(event)">&times;</button>
    <br><hr>
    <form action="{{ route('store.transfer') }}" method="POST">
        @csrf
        <input type="hidden" name="staff_id" id="" value="1">
        <!-- Name -->
        <div class="input-field">
            <label for="">From Account</label>
            <select name="from_account" id="">
                <option value="" selected disabled>--select--</option>
                @foreach($banks as $bank)
                <option value="{{ $bank->account_no }}">{{ $bank->account_name }} {{ $bank->account_no }}  </option>
                @endforeach
            </select>
        </div>

        <div class="input-field">
            <label for="">To Account</label>
            <select name="to_account" id="">
                <option value="" selected disabled>--select--</option>
                @foreach($banks as $bank)
                <option value="{{ $bank->account_no }}">{{ $bank->account_name }} {{ $bank->account_no }}  </option>
                @endforeach
            </select>
        </div>

        <div class="input-field">
            <label for="">Amount Transfered</label>
            <input type="number" name="amount" id="" placeholder="Amount">
        </div>

        <div class="input-field" style="width: 100%;">
            <label for="">Note</label>
            <textarea name="note" id="" placeholder="Write a transfer note...."></textarea>
        </div>

<br>
        <div class="input-field">
            <button type="submit" class="btn primart-btn" id="btn-save">Save Data</button>
        </div>
    </form>
</div>

<script>
    function createTransfer(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.getElementById("container-t");

        blurScreen.style.display='block';
        dataForm.style.display='block';
    }

    function collapseTrans(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.getElementById("container-t");

        blurScreen.style.display='none';
        dataForm.style.display='none';
    }
</script>