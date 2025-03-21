<div class="container-form">
    <h3>{{ ('Product Registration') }}</h3>
    <button type="button" class="close" onclick="hideForm(event)">&times;</button>
    <br><hr>
    <form action="{{ route('store.products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="item_prefix" id="" value="STDR">
        <!-- Name -->
        <div class="input-field">
            <label for="">Item Name</label>
            <input type="text" name="item_name" id="" placeholder="Item Name">
        </div>

        <!-- Profile Image -->
        <div class="input-field">
            <label for="">Item Specifications</label>
            <input type="text" name="item_specs" id="" placeholder="Item specifications">
        </div>

        <div class="input-field">
            <label for="">Quantity Units</label>
            <input type="text" name="item_quantity_unit" id="" placeholder="Quantity Unit">
        </div>

        <div class="input-field">
            <label for="">Item Category</label>
            <select name="item_category" id="">
                <option value="" selected disabled>--select--</option>
                @foreach($categories as $category)
                <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-field">
            <label for="">Store Name</label>
            <select name="store_id" id="">
                <option value="" selected disabled>--select--</option>
                @foreach($stores as $store)
                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-field">
            <label for="">Item Picture</label>
            <input type="file" name="item_pic" id="" accept="image/*">
        </div>

        <div class="input-field">
            <button type="submit" class="btn primart-btn" id="btn-save">Save Data</button>
        </div>

    </form>
</div>

<script>
    function RegProdForm(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.querySelector('.container-form');

        blurScreen.style.display='block';
        dataForm.style.display='block';
    }

    function hideForm(event){
        event.preventDefault();
        const blurScreen = document.querySelector('.transparent');
        const dataForm = document.querySelector('.container-form');

        blurScreen.style.display='none';
        dataForm.style.display='none';
    }
</script>