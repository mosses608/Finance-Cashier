<div class="container-form" id="container-p">
    <h3>{{ ('Add Bank') }}</h3>
    <button type="button" class="close" onclick="collapseAdd(event)">&times;</button>
    <br><hr>
    <form action="{{ route('store.bank') }}" method="POST">
        @csrf

        <!-- Name -->
        <div class="input-field">
            <label for="">Bank Name</label>
            <input type="text" name="bank_name" id="" placeholder="Bank Name" autofocus="true">
        </div>

        <div class="input-field">
            <label for="">Branch</label>
            <input type="text" name="branch" id="" placeholder="Branch">
        </div>

        <div class="input-field">
            <label for="">Account Name</label>
            <input type="text" name="account_name" id="" placeholder="Account Name">
        </div>

        <div class="input-field">
            <label for="">Account No</label>
            <input type="text" name="account_no" id="" placeholder="Account No">
        </div>

        <div class="input-field">
            <label for="">Account Holder</label>
            <input type="text" name="acc_holder" id="" placeholder="Account Holder">
        </div>

        <div class="input-field">
            <label for="">Phone</label>
            <input type="tel" name="phone" id="" placeholder="Phone No">
        </div>

        <div class="input-field">
            <label for="">Initial Balance</label>
            <input type="number" name="balance" id="" placeholder="Initial Balance">
        </div>
<br>
        <div class="input-field">
            <button type="submit" class="btn primart-btn" id="btn-save">Save Data</button>
        </div>
    </form>
</div>

<script>
    function addNewBnak(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.getElementById("container-p");

        blurScreen.style.display='block';
        dataForm.style.display='block';
    }

    function collapseAdd(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.getElementById("container-p");

        blurScreen.style.display='none';
        dataForm.style.display='none';
    }
</script>