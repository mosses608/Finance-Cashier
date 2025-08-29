<div class="container-fluid" id="custom-invoice-section">
    @csrf
    <!-- ================= CUSTOMER SECTION ================= -->
    <div class="card p-3 mb-3 shadow-sm">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label"><strong>Customer Name (Company)</strong></label>
                <select name="customer_id" class="form-control select2">
                    <option value="" selected disabled></option>
                    @foreach ($customers as $custome)
                        <option value="{{ $custome->id }}">{{ $custome->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label"><strong>Invoice Date</strong></label>
                <input type="date" name="invoice_date" class="form-control" value="{{ now()->toDateString() }}">
            </div>
        </div>
    </div>

    <!-- ================= PRODUCTS TABLE ================= -->
    <div class="card p-3 mb-3 shadow-sm">
        <h5 class="mb-3">Order Lines</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Available Qty</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Discount (%)</th>
                    </tr>
                </thead>
                <tbody id="custom-product-container">
                    <tr class="custom-product-group">
                        <td style="min-width: 200px;">
                            <select class="form-control select2 custom-product-select" name="product_id[]">
                                <option value="" selected disabled>--select product-- </option>
                                @foreach ($stockProducts as $stockPrd)
                                    <option value="{{ $stockPrd->productId }}"
                                        data-available-quantity="{{ $stockPrd->availableQuantity }}"
                                        data-selling-price="{{ $stockPrd->sellingPrice }}">
                                        {{ $stockPrd->productName }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="available_quantity[]"
                                class="form-control custom-available-quantity" readonly></td>
                        <td><input type="number" name="selling_price[]" class="form-control custom-selling-price"></td>
                        <td><input type="number" name="quantity_sell[]" class="form-control" placeholder="Qty"></td>
                        <td><input type="text" name="discount[]" class="form-control" placeholder="0"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-danger btn-sm mt-2 float-start" id="custom-remove-btn">
                    <i class="fa fa-minus"></i> Remove Row
                </button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary btn-sm mt-2 float-end" id="custom-add-btn">
                    <i class="fa fa-plus"></i> Add Row
                </button>
            </div>
        </div>
    </div>

    <!-- ================= PREVIEW + SAVE ================= -->
    <div class="card p-3 shadow-sm">
        <div id="custom-invoice-preview" class="empty">
            <p class="text-center text-muted p-4">Profoma Invoice Preview will appear here!</p>
        </div>
        <div class="mt-3 d-flex justify-content-between">
            <button type="button" id="custom-preview-btn" class="btn btn-warning">Preview Profoma</button>
            <div id="custom-submit-container" style="display:none;">
                <button type="submit" class="btn btn-success">Save Invoice</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Function to initialize Select2 with available qty inside dropdown
        function initSelect2(element) {
            if ($.fn.select2) {
                // Destroy previous Select2 instance if exists
                if (element.hasClass('select2-hidden-accessible')) {
                    element.select2('destroy');
                }

                element.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    templateResult: function(data) {
                        if (!data.id) return data.text;
                        let available = $(data.element).data('available-quantity') || 0;
                        return $('<span>' + data.text + ' <small class="text-muted">(Available: ' +
                            available + ')</small></span>');
                    }
                });
            }
        }

        // Initialize the first row
        initSelect2($('.custom-product-select'));

        // Add new product row
        $('#custom-add-btn').click(function() {
            let firstRow = $('#custom-product-container .custom-product-group:first');

            // Destroy Select2 before cloning to prevent broken layout
            firstRow.find('select').select2('destroy');

            // Clone the row
            let newRow = firstRow.clone();

            // Reset all inputs and remove any available text
            newRow.find('select').val('');
            newRow.find('input').val('');
            newRow.find('.available-text').remove();

            // Append new row
            $('#custom-product-container').append(newRow);

            // Re-initialize Select2 for both first and new row
            initSelect2(firstRow.find('select'));
            initSelect2(newRow.find('select'));
        });

        // Remove last product row
        $('#custom-remove-btn').click(function() {
            let rows = $('#custom-product-container .custom-product-group');
            if (rows.length > 1) {
                rows.last().remove();
            } else {
                alert("You need at least one product row.");
            }
        });

        $(document).on('change', '.custom-product-select', function() {
            let option = $(this).find('option:selected');
            let availableQty = option.data('available-quantity') || 0;
            let sellingPrice = option.data('selling-price') || 0;

            let row = $(this).closest('.custom-product-group');
            row.find('.custom-available-quantity').val(availableQty);
            row.find('.custom-selling-price').val(sellingPrice);
        });

    });


    $('#custom-preview-btn').click(function() {
        let previewDiv = $('#custom-invoice-preview');
        previewDiv.removeClass('empty').addClass('show-preview').empty();

        let customerName = $('select[name="customer_id"] option:selected').text();
        let invoiceDate = $('input[name="invoice_date"]').val();

        if (!customerName || customerName === "--select--") {
            alert("Please select a customer.");
            return;
        }

        let previewHtml = `
        <h5 class="mb-3 text-primary">Proforma Invoice Preview</h5>
        <p><strong>Customer:</strong> ${customerName}</p>
        <p><strong>Invoice Date:</strong> ${invoiceDate}</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Discount (%)</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Grand Total:</th>
                    <th>0.00</th>
                </tr>
            </tfoot>
        </table>
    `;

        previewDiv.html(previewHtml);

        let totalAmount = 0;

        $('#custom-product-container .custom-product-group').each(function(index) {
            let product = $(this).find('.custom-product-select option:selected').text();
            let availableQty = $(this).find('.custom-available-quantity').val();
            let unitPrice = parseFloat($(this).find('.custom-selling-price').val()) || 0;
            let quantity = parseFloat($(this).find('input[name="quantity_sell[]"]').val()) || 0;
            let discount = parseFloat($(this).find('input[name="discount[]"]').val()) || 0;

            if (!product || product === "--select product--") return;

            let lineTotal = (unitPrice * quantity) * (1 - discount / 100);
            totalAmount += lineTotal;

            previewDiv.find('tbody').append(`
            <tr data-row-index="${index}">
                <td>${product}</td>
                <td>${unitPrice.toFixed(2)}</td>
                <td>${quantity}</td>
                <td>${discount}</td>
                <td>${lineTotal.toFixed(2)}</td>
                <td><button type="button" class="btn btn-sm btn-danger preview-delete-btn">&times;</button></td>
            </tr>
        `);
        });

        previewDiv.find('tfoot th:last').text(totalAmount.toFixed(2));
        $('#custom-submit-container').show();
    });

    // Delete row from preview and main product table
    $(document).on('click', '.preview-delete-btn', function() {
        let row = $(this).closest('tr');
        let index = row.data('row-index');

        // Remove from preview table
        row.remove();

        // Remove from original appended product table
        let productRows = $('#custom-product-container .custom-product-group');
        if (productRows.eq(index)) {
            productRows.eq(index).remove();
        }

        let total = 0;
        $('#custom-invoice-preview tbody tr').each(function() {
            let lineTotal = parseFloat($(this).find('td:nth-child(5)').text()) || 0;
            total += lineTotal;
        });
        $('#custom-invoice-preview tfoot th:last').text(total.toFixed(2));

        if ($('#custom-invoice-preview tbody tr').length === 0) {
            $('#custom-submit-container').hide();
            $('#custom-invoice-preview').removeClass('show-preview').addClass('empty')
                .html('<p class="text-center text-muted p-4">Proforma Invoice Preview will appear here!</p>');
        }
    });
</script>


<style>
    /* Make Select2 match original select exactly */
    .select2-container--bootstrap-5 .select2-selection {
        height: calc(2.25rem + 2px);
        /* matches .form-control */
        padding: 0.375rem 0.75rem;
        /* matches .form-control */
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        font-size: 1rem;
        line-height: 1.5;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
        padding-right: 0;
    }
</style>

<style>
    /* Empty state */
    #custom-invoice-preview.empty {
        min-height: 150px;
        background: #f9f9f9;
        border: 2px dashed #ccc;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #888;
        font-style: italic;
        font-size: 1.1rem;
        transition: all 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    /* Shimmer effect for empty state */
    #custom-invoice-preview.empty::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(120deg, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0) 70%);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            transform: rotate(25deg) translateX(-100%);
        }

        100% {
            transform: rotate(25deg) translateX(100%);
        }
    }

    /* Preview state */
    #custom-invoice-preview.show-preview {
        min-height: auto;
        background: #fff;
        border: 2px solid #0d6efd;
        border-radius: 8px;
        color: #000;
        font-style: normal;
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
        padding: 15px;
        transition: all 0.5s ease;
        display: block;
        /* allow table to render properly */
    }

    /* Table fade-in */
    #custom-invoice-preview table {
        opacity: 0;
        transform: translateY(-10px);
        animation: fadeInTable 0.5s forwards;
    }

    @keyframes fadeInTable {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
