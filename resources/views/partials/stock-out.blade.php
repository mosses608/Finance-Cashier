<div class="d-flex gap-3" id="custom-invoice-section">
    <div class="w-50 border p-2 rounded">
        @csrf
        <div id="custom-product-container">
            <!-- First Product Entry -->
            <div class="row g-2 mb-3 custom-product-group">
                <div class="col-12 mb-2">
                    <label class="input-label p-2"><strong>Product Name</strong></label>
                    <select class="form-control select2 custom-product-select" name="product_id[]" style="width: 100%;">
                        <option value="" selected disabled>--select product--</option>
                        @foreach ($stockProducts as $stockPrd)
                            <option value="{{ $stockPrd->productId }}"
                                data-available-quantity="{{ $stockPrd->availableQuantity }}"
                                data-selling-price="{{ $stockPrd->sellingPrice }}">
                                {{ $stockPrd->productName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6">
                    <input type="number" name="available_quantity[]" class="form-control custom-available-quantity"
                        placeholder="Available Quantity" readonly>
                </div>

                <div class="col-6">
                    <input type="number" name="quantity_sell[]" class="form-control" placeholder="Stock out quantity">
                </div>
            </div>
        </div>

        <!-- Reason -->
        <div class="col-12 mb-2">
            <label class="input-label p-2"><strong>Reason for stock out</strong></label>
            <textarea class="form-control" name="reasons" placeholder="State reasons for stock out"></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="col-6 mt-3 w-100">
            <button type="button" id="custom-preview-btn" class="btn btn-warning">Preview Data</button>
            <button type="button" class="btn btn-primary float-end" id="custom-add-btn">
                <i class="fa fa-plus"></i>
            </button>
        </div>

        <div class="col-6 mt-3" id="custom-submit-container" style="display: none;">
            <button type="submit" class="btn btn-success w-100 float-start">Save Data</button>
        </div>
    </div>

    <!-- Invoice Preview -->
    <div class="w-50">
        <div class="border p-2 bg-light rounded mt-4" id="custom-invoice-preview">
            <p class="text-center p-5 blink">Stock out preview will appear here!</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('change', '.custom-product-select', function() {
            const selectedOption = $(this).find('option:selected');
            const availableQuantity = selectedOption.data('available-quantity');
            const container = $(this).closest('.row');
            container.find('.custom-available-quantity').val(availableQuantity);
        });

        $('.select2').select2({
            placeholder: '--select product--',
            width: '100%'
        });
    });

    document.getElementById('custom-add-btn').addEventListener('click', function() {
        const container = document.getElementById('custom-product-container');
        const productOptions = `@foreach ($stockProducts as $stockPrd)
            <option value="{{ $stockPrd->productId }}" data-available-quantity="{{ $stockPrd->availableQuantity }}" data-selling-price="{{ $stockPrd->sellingPrice }}">{{ $stockPrd->productName }}</option>
        @endforeach`;

        const newGroup = document.createElement('div');
        newGroup.classList.add('row', 'g-2', 'mb-3', 'custom-product-group');
        newGroup.innerHTML = `
            <hr class="mt-3" style="width: 95%; margin-left:2.5%;">
            <div class="col-12 mb-2">
                <label class="input-label p-2"><strong>Product Name</strong></label>
                <select class="form-control select2 custom-product-select" name="product_id[]" style="width: 100%;">
                    <option value="" selected disabled>--select product--</option>
                    ${productOptions}
                </select>
            </div>
            <div class="col-6">
                <input type="number" name="available_quantity[]" class="form-control custom-available-quantity" placeholder="Available Quantity" readonly>
            </div>
            <div class="col-6">
                <input type="number" name="quantity_sell[]" class="form-control" placeholder="Stock out quantity">
            </div>
        `;
        container.appendChild(newGroup);
        $('.select2').select2();
    });

    document.getElementById('custom-preview-btn').addEventListener('click', function() {
        const productGroups = document.querySelectorAll('#custom-product-container .custom-product-group');
        let rowsHtml = '';
        let totalQuantity = 0;

        productGroups.forEach(group => {
            const select = group.querySelector('.custom-product-select');
            const quantityInput = group.querySelector('[name="quantity_sell[]"]');

            if (!select || !quantityInput) return;

            const productName = select.options[select.selectedIndex]?.text || '';
            const quantity = parseFloat(quantityInput.value || 0);

            if (!select.value || !quantity) return;

            rowsHtml += `
                <tr>
                    <td>${productName}</td>
                    <td>${quantity}</td>
                </tr>`;

            totalQuantity += quantity;
        });

        if (!rowsHtml) {
            alert("Please select at least one product and quantity before preview.");
            return;
        }

        const previewHTML = `
            <div class="border rounded bg-white shadow-sm">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th class="text-end">Total Quantity</th>
                                <th>
                                    <input type="hidden" value="${totalQuantity}" name="total_quantity">
                                    ${totalQuantity.toLocaleString()}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        `;

        document.getElementById('custom-invoice-preview').innerHTML = previewHTML;
        document.getElementById('custom-submit-container').style.display = 'block';
        document.getElementById('custom-add-btn').style.display = 'none';
        document.getElementById('custom-preview-btn').style.display = 'none';

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
