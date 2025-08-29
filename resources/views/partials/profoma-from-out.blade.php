<div class="container-fluid" id="service-invoice-section">
    @csrf

    <!-- ================= CUSTOMER SECTION ================= -->
    <div class="card p-3 mb-3 shadow-sm">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label"><strong>Customer Type</strong></label>
                <select class="form-control" id="service-customer-selector">
                    <option value="" selected disabled>--select--</option>
                    <option value="1">Existing Customer</option>
                    <option value="2">New Customer</option>
                </select>
            </div>
            <div class="col-md-6" id="service-existing-customer" style="display:none;">
                <label class="form-label"><strong>Customer Name (Company)</strong></label>
                <select name="customer_id" class="form-control select2">
                    <option value="" selected disabled>--select--</option>
                    @foreach ($customers as $custome)
                        <option value="{{ $custome->id }}">{{ $custome->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6" id="service-name" style="display:none;">
                <label class="form-label"><strong>Customer Name</strong></label>
                <input type="text" name="service_name" class="form-control" placeholder="Customer Name">
            </div>
            <div class="col-md-6" id="service-phone" style="display:none;">
                <label class="form-label"><strong>Phone Number</strong></label>
                <input type="tel" name="service_phone" class="form-control" placeholder="Phone Number">
            </div>
            <div class="col-md-6" id="service-tin" style="display:none;">
                <label class="form-label"><strong>TIN</strong></label>
                <input type="text" name="service_TIN" class="form-control" placeholder="Tax Identification Number">
            </div>
            <div class="col-md-6" id="service-address" style="display:none;">
                <label class="form-label"><strong>Address</strong></label>
                <input type="text" name="service_address" class="form-control" placeholder="Customer Address">
            </div>
        </div>
    </div>

    <!-- ================= SERVICES TABLE ================= -->
    <div class="card p-3 mb-3 shadow-sm">
        <h5 class="mb-3">Product Lines</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Discount (%)</th>
                    </tr>
                </thead>
                <tbody id="service-product-container">
                    <tr class="service-product-group">
                        <td><input type="text" name="product_name[]" class="form-control" placeholder="Product Name"></td>
                        <td><input type="number" step="0.01" name="amountPay[]" class="form-control" placeholder="Price"></td>
                        <td><input type="number" name="quantity[]" class="form-control" placeholder="Qty"></td>
                        <td><input type="number" step="0.01" name="discount[]" class="form-control" placeholder="0"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-danger btn-sm mt-2 float-start" id="service-remove-btn">
                    <i class="fa fa-minus"></i> Remove Row
                </button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary btn-sm mt-2 float-end" id="service-add-btn">
                    <i class="fa fa-plus"></i> Add Row
                </button>
            </div>
        </div>
    </div>

    <!-- ================= PREVIEW + SAVE ================= -->
    <div class="card p-3 shadow-sm">
        <div id="service-invoice-preview" class="empty">
            <p class="text-center text-muted p-4">Product Invoice Preview will appear here!</p>
        </div>
        <div class="mt-3 d-flex justify-content-between">
            <button type="button" id="service-preview-btn" class="btn btn-warning">Preview Invoice</button>
            <div id="service-submit-container" style="display:none;">
                <button type="submit" class="btn btn-success">Save Invoice</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    // Toggle customer fields
    $('#service-customer-selector').change(function() {
        if (this.value === '1') {
            $('#service-existing-customer').show();
            $('#service-name,#service-phone,#service-tin,#service-address').hide();
        } else if (this.value === '2') {
            $('#service-existing-customer').hide();
            $('#service-name,#service-phone,#service-tin,#service-address').show();
        } else {
            $('#service-existing-customer,#service-name,#service-phone,#service-tin,#service-address').hide();
        }
    });

    // Add row
    $('#service-add-btn').click(function() {
        let row = $('#service-product-container .service-product-group:first').clone();
        row.find('input').val('');
        $('#service-product-container').append(row);
    });

    // Remove row
    $('#service-remove-btn').click(function() {
        let rows = $('#service-product-container .service-product-group');
        if (rows.length > 1) rows.last().remove();
    });

    // Preview
    $('#service-preview-btn').click(function() {
        let previewDiv = $('#service-invoice-preview');
        previewDiv.removeClass('empty').addClass('show-preview').empty();

        let customerName = 'N/A';
        if ($('select[name="customer_id"]').val()) {
            customerName = $('select[name="customer_id"] option:selected').text();
        } else if ($('input[name="service_name"]').val()) {
            customerName = $('input[name="service_name"]').val();
        }

        let rowsHtml = '';
        let grandTotal = 0;

        $('#service-product-container .service-product-group').each(function(index) {
            let name = $(this).find('input[name="product_name[]"]').val();
            let price = parseFloat($(this).find('input[name="amountPay[]"]').val()) || 0;
            let qty = parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
            let disc = parseFloat($(this).find('input[name="discount[]"]').val()) || 0;
            if (!name || qty === 0) return;

            let total = price * qty;
            let discAmt = total * (disc/100);
            let final = total - discAmt;
            grandTotal += final;

            rowsHtml += `
                <tr data-row-index="${index}">
                    <td>${name}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${qty}</td>
                    <td>${disc}%</td>
                    <td>${discAmt.toFixed(2)}</td>
                    <td>${final.toFixed(2)}</td>
                    <td><button type="button" class="btn btn-sm btn-danger service-delete-btn">&times;</button></td>
                </tr>`;
        });

        let tableHtml = `
            <h5 class="mb-3 text-primary">Product Invoice Preview</h5>
            <p><strong>Customer:</strong> ${customerName}</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Discount %</th>
                        <th>Discount Amt</th>
                        <th>Final</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>${rowsHtml}</tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Grand Total:</th>
                        <th colspan="2">
                            <input type="hidden" name="amount" value="${grandTotal}">
                            ${grandTotal.toFixed(2)}
                        </th>
                    </tr>
                </tfoot>
            </table>`;
        previewDiv.html(tableHtml);
        $('#service-submit-container').show();
    });

    // Delete row from preview + original
    $(document).on('click','.service-delete-btn',function(){
        let index = $(this).closest('tr').data('row-index');
        $(this).closest('tr').remove();
        $('#service-product-container .service-product-group').eq(index).remove();

        let total=0;
        $('#service-invoice-preview tbody tr').each(function(){
            total += parseFloat($(this).find('td:nth-child(6)').text()) || 0;
        });
        $('#service-invoice-preview tfoot th:last').html(`<input type="hidden" name="amount" value="${total}">${total.toFixed(2)}`);
        if ($('#service-invoice-preview tbody tr').length===0){
            $('#service-submit-container').hide();
            $('#service-invoice-preview').removeClass('show-preview').addClass('empty')
            .html('<p class="text-center text-muted p-4">Product Invoice Preview will appear here!</p>');
        }
    });
});
</script>

<style>
#service-invoice-preview.empty {
    min-height: 150px;
    background: #f9f9f9;
    border: 2px dashed #ccc;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #888;
    font-style: italic;
    transition: all .5s ease;
    position: relative;
    overflow: hidden;
}
#service-invoice-preview.empty::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: linear-gradient(120deg,rgba(255,255,255,0) 30%,rgba(255,255,255,.3) 50%,rgba(255,255,255,0) 70%);
    animation: shimmer 2s infinite;
}
@keyframes shimmer {
    0%{transform: rotate(25deg) translateX(-100%);}
    100%{transform: rotate(25deg) translateX(100%);}
}
#service-invoice-preview.show-preview {
    background: #fff;
    border: 2px solid #0d6efd;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(13,110,253,.3);
    padding: 15px;
}
#service-invoice-preview table {
    opacity: 0;
    transform: translateY(-10px);
    animation: fadeInTable .5s forwards;
}
@keyframes fadeInTable {to{opacity:1;transform:translateY(0);}}
</style>
