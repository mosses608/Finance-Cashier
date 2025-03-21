<form action="{{ route('store.sales') }}" method="POST" class="form-group">
    @csrf

    <div class="input-field">
        <label for="">Item Name</label>
        <select name="product_item_id" id="product_item_id">
            <option value="" selected disabled>--select item--</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" data-store-id="{{ $product->store_id }}">{{ $product->item_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="input-field">
        <label for="">Item Price</label>
        <input type="number" name="item_price" id="item_price" placeholder="Item Price" readonly>
    </div>

    <div class="input-field">
        <label for="">Store Name</label>
        <input type="text" name="store_name" id="store_name" placeholder="Store Name" readonly>
    </div>

    <div class="input-field">
        <label for="">Available Quantity</label>
        <input type="number" name="quantity_available" id="quantity_available" placeholder="Available Quantity" readonly>
    </div>

    <div class="input-field">
        <label for="">StockOut Quantity</label>
        <input type="number" name="stockout_quantity" id="stockout_quantity" placeholder="StockOut Quantity">
    </div>

    <div class="input-field">
        <label for="">Customer Name</label>
        <input type="text" name="customer_name" id="customer_name" placeholder="Customer Name">
    </div>

    <div class="input-field">
        <label for="">Selling Price</label>
        <input type="number" name="selling_price" placeholder="Selling Price">
    </div>

    <div class="input-field">
        <label for="">Stock Out Mode</label>
        <select name="stock_out_mode" class="mode-stock-out">
            <option value="" selected disabled>--select mode--</option>
            <option value="1">Full Paid</option>
            <option value="2">Partial Paid</option>
            <option value="3">Loan</option>
        </select>
    </div>

    <div class="input-field" id="dead-line" style="display: none;">
        <label for="">Dead-Line Date</label>
        <input type="date" name="deadline_date">
    </div>

    <input type="hidden" name="user_id" value="1">

    <div class="input-field">
        <button type="submit" class="btn-save primary-btn">Save Data</button>
    </div>
</form>