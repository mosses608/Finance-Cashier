@extends('layouts.mainLayout')

@section('content')

<div class="transparent" onclick="hideAll(event)"></div>

@include('partials.sideNav')

<x-messages />

<div class="shortcut-report">
    <div class="md-7">
        <h3>{{ __('Sales & Stock Out') }}</h3>
        @include('partials.stockOut')
        <br><br>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function () {
        let stocks = @json($stocks);
        let products = @json($products);
        console.log("Products data: ", products);
        console.log("Stocks data: ", stocks); 

        $('#product_item_id').on('change', function () {
            let productId = $(this).val(); 
            console.log("Selected Product ID: ", productId);

            let selectedProduct = products.find(product => product.id == productId);
            console.log("Selected Product: ", selectedProduct);

            let selectedStock = stocks.find(stock => stock.storage_item_id == productId);
            console.log("Selected Stock: ", selectedStock);

            if (selectedStock && selectedProduct) {
                $('#item_price').val(selectedStock.item_price);
                $('#quantity_available').val(selectedStock.quantity_in);

                let storeId = selectedProduct.store_id;

                let storeName = "Store " + storeId;

                $('#store_name').val(storeName); 
            } else {
                $('#item_price').val('');
                $('#quantity_available').val('');
                $('#store_name').val('');
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function(){
        const modeChanger = document.querySelector('.mode-stock-out');
        const selectedItem = document.getElementById("dead-line");

        modeChanger.addEventListener('change', function(){
            const selectedValue = modeChanger.value;
            
            selectedItem.style.display='none';

            if(selectedValue === '2' || selectedValue === '3'){
                selectedItem.style.display='block';
            }else{
                selectedItem.style.display='none';
            }
        })
    })
</script>

@stop