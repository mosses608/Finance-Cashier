<div class="container-fluid" id="custom-service-section">
    @csrf

    <!-- ================= CUSTOMER + INVOICE DATE ================= -->
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

    <!-- ================= SERVICES TABLE ================= -->
    <div class="card p-3 mb-3 shadow-sm">
        <h5 class="mb-3">Service Lines</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Discount (%)</th>
                    </tr>
                </thead>
                <tbody id="custom-service-container">
                    <tr class="custom-service-group">
                        <td style="min-width:200px;">
                            <select class="form-control select2 custom-service-select" name="service_id[]">
                                <option value="" selected disabled>--select service--</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" data-selling-price="{{ $service->price }}">
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="price[]" class="form-control custom-selling-price"></td>
                        <td><input type="number" name="quantity[]" class="form-control" placeholder="Qty"></td>
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
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // Add new service row
        $('#custom-add-btn').click(function() {
            let firstRow = $('#custom-service-container .custom-service-group:first');
            firstRow.find('select').select2('destroy');
            let newRow = firstRow.clone();
            newRow.find('select').val('');
            newRow.find('input').val('');
            $('#custom-service-container').append(newRow);
            firstRow.find('select').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            newRow.find('select').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });

        // Remove last row
        $('#custom-remove-btn').click(function() {
            let rows = $('#custom-service-container .custom-service-group');
            if (rows.length > 1) rows.last().remove();
            else alert("You need at least one service row.");
        });

        // Fill price on service select
        $(document).on('change', '.custom-service-select', function() {
            let price = $(this).find('option:selected').data('selling-price') || 0;
            $(this).closest('tr').find('.custom-selling-price').val(price);
        });

        // Preview invoice
        $('#custom-preview-btn').click(function() {
            let previewDiv = $('#custom-invoice-preview');
            previewDiv.removeClass('empty').addClass('show-preview').empty();

            let customerName = $('select[name="customer_id"] option:selected').text();
            let invoiceDate = $('input[name="invoice_date"]').val();
            if (!customerName) {
                alert("Please select a customer.");
                return;
            }

            let previewHtml = `<h5 class="mb-3 text-primary">Service Proforma Preview</h5>
            <p><strong>Customer:</strong> ${customerName}</p>
            <p><strong>Invoice Date:</strong> ${invoiceDate}</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Discount (%)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Grand Total:</th>
                        <th>0.00</th>
                    </tr>
                </tfoot>
            </table>`;

            previewDiv.html(previewHtml);

            let totalAmount = 0;
            $('#custom-service-container .custom-service-group').each(function(index) {
                let service = $(this).find('.custom-service-select option:selected').text();
                let unitPrice = parseFloat($(this).find('.custom-selling-price').val()) || 0;
                let qty = parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
                let discount = parseFloat($(this).find('input[name="discount[]"]').val()) || 0;
                if (!service) return;

                let lineTotal = (unitPrice * qty) * (1 - discount / 100);
                totalAmount += lineTotal;

                previewDiv.find('tbody').append(`<tr>
                <td>${service}</td>
                <td>${unitPrice.toFixed(2)}</td>
                <td>${qty}</td>
                <td>${discount}</td>
                <td>${lineTotal.toFixed(2)}</td>
            </tr>`);
            });

            previewDiv.find('tfoot th:last').text(totalAmount.toFixed(2));
            $('#custom-submit-container').show();
        });
    });
</script>


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
