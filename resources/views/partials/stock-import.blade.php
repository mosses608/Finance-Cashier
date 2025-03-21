<div class="container-form" id="container-n">
    <h3>{{ ('Import Product') }}</h3>
    <button type="button" class="close" onclick="fadeForm(event)">&times;</button>
    <br><hr>
    <form action="{{ route('stock.store') }}" method="POST">
        @csrf
        <!-- <div class="input-field">
            <label for="">Storage Item Id</label>
            <input type="hidden" name="storage_item_id" id="" value="{{ $product->id }}" readonly>
        </div> -->
        <input type="hidden" name="storage_item_id" id="" value="{{ $product->id }}" readonly>

        <!-- Name -->
        <div class="input-field">
            <label for="">Stock In</label>
            <input type="number" name="quantity_in" id="" placeholder="Stock In Quantity">
        </div>

        <!-- Profile Image -->
        <div class="input-field">
            <label for="">Item Price</label>
            <input type="number" name="item_price" id="" placeholder="Item Price">
        </div>

        <div class="input-field">
            <label for="">Remarks</label>
            <input type="text" name="remarks" id="" placeholder="Remarks">
        </div>

        <div class="input-field">
            <button type="submit" class="btn primart-btn" id="btn-save">Save Data</button>
        </div>
    </form>
</div>

<script>
    function stockIn(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.getElementById("container-n");

        blurScreen.style.display='block';
        dataForm.style.display='block';
    }

    function fadeForm(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.getElementById("container-n");

        blurScreen.style.display='none';
        dataForm.style.display='none';
    }
</script>