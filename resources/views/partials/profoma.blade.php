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
                        placeholder="available quantity" readonly>
                </div>

                <div class="col-6">
                    <input type="text" name="selling_price[]" class="form-control custom-selling-price"
                        placeholder="selling Price" readonly>
                </div>

                <input type="hidden" name="category_id" value="1">
                <input type="hidden" name="profoma_status" value="pending">

                <div class="col-6">
                    <label class="input-label p-2"><strong>Quantity to sale</strong></label>
                    <input type="number" name="quantity_sell[]" class="form-control" placeholder="quantity">
                </div>

                <div class="col-6">
                    <label class="input-label p-2"><strong>Item Discount</strong></label>
                    <input type="text" name="discount[]" class="form-control" placeholder="discount @ eg 0.5">
                </div>
            </div>
        </div>

        <!-- Customer Section -->
        <div class="col-12 mb-2">
            <label class="input-label p-2"><strong>Customer Section</strong></label>
            <select class="form-control" id="custom-customer-selector">
                <option value="" selected disabled>--select--</option>
                <option value="1">Existing Customer</option>
                <option value="2">New Customer</option>
            </select>
        </div>

        <div class="col-12 mb-2" id="custom-existing-customer" style="display:none;">
            <label class="input-label p-2"><strong>Customer Name (Company Name)</strong></label>
            <select name="customer_id" class="form-control select2">
                <option value="" selected disabled>--select--</option>
                @foreach ($customers as $custome)
                    <option value="{{ $custome->id }}">{{ $custome->name }}</option>
                @endforeach
            </select>
        </div>

        @if (false)
            <div class="row">

                <div class="col-6" id="custom-name" style="display:none;">
                    <label class="input-label p-2"><strong>Customer Name</strong></label>
                    <input type="text" name="name" class="form-control" placeholder="Customer Name">
                </div>

                <div class="col-6" id="custom-phone" style="display:none;">
                    <label class="input-label p-2"><strong>Phone Number</strong></label>
                    <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
                </div>

                <div class="col-6" id="custom-tin" style="display:none;">
                    <label class="input-label p-2"><strong>TIN</strong></label>
                    <input type="text" name="TIN" class="form-control" placeholder="Tax Identification Number">
                </div>

                <div class="col-6" id="custom-address" style="display:none;">
                    <label class="input-label p-2"><strong>Address</strong></label>
                    <input type="text" name="address" class="form-control" placeholder="Customer Address">
                </div>

            </div>
        @endif

        <!-- Action Buttons -->
        <div class="col-6 mt-3 w-100">
            <button type="button" id="custom-preview-btn" class="btn btn-warning">
                Preview Profoma
            </button>
            <button type="button" class="btn btn-primary float-end" id="custom-add-btn">
                <i class="fa fa-plus"></i>
            </button>
        </div>

        <div class="col-6 mt-3" id="custom-submit-container" style="display: none;">
            <button type="submit" class="btn btn-success w-100 float-start">Save Invoice</button>
        </div>
    </div>

    <!-- Invoice Preview -->
    <div class="w-50">
        <div class="border p-2 bg-light rounded mt-4" id="custom-invoice-preview">
            <p class="text-center p-5 blink">Profoma Invoice Preview will appear here!</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('change', '.custom-product-select', function() {
            const selectedOption = $(this).find('option:selected');

            const availableQuantity = selectedOption.data('available-quantity');
            const sellingPrice = selectedOption.data('selling-price');

            const container = $(this).closest('.row');

            container.find('.custom-available-quantity').val(availableQuantity);
            container.find('.custom-selling-price').val(sellingPrice);
        });

        // Initialize select2
        $('.select2').select2({
            placeholder: '--select product--',
            width: '100%'
        });
    });
</script>


<script>
    document.getElementById('custom-add-btn').addEventListener('click', function() {
        const container = document.getElementById('custom-product-container');

        const productOptions = `@foreach ($stockProducts as $stockPrd)
            <option value="{{ $stockPrd->productId }}"
                data-available-quantity="{{ $stockPrd->availableQuantity }}"
                data-selling-price="{{ $stockPrd->sellingPrice }}">
                {{ $stockPrd->productName }}
            </option>
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
                <input type="number" name="available_quantity[]" class="form-control custom-available-quantity" placeholder="available Quantity" readonly>
            </div>
            <div class="col-6">
                <input type="text" name="selling_price[]" class="form-control custom-selling-price" placeholder="selling Price" readonly>
            </div>
            <div class="col-6">
                <label class="input-label p-2"><strong>Quantity to sale</strong></label>
                <input type="number" name="quantity_sell[]" class="form-control" placeholder="quantity">
            </div>
            <div class="col-6">
                <label class="input-label p-2"><strong>Item Discount</strong></label>
                <input type="text" name="discount[]" class="form-control" placeholder="discount @ eg 0.5">
            </div>
        `;
        container.appendChild(newGroup);
        $('.select2').select2();
    });

    document.getElementById('custom-preview-btn').addEventListener('click', function() {
        const productGroups = document.querySelectorAll('#custom-product-container .custom-product-group');
        const customerSelect = document.querySelector('[name="customer_id"]');
        const customerDataName = document.querySelector('[name="name"]');
        let customerName = 'N/A';

        if (customerSelect && customerSelect.value) {
            customerName = customerSelect.options[customerSelect.selectedIndex].text;
        } else if (customerDataName && customerDataName.value.trim() !== '') {
            customerName = customerDataName.value;
        }

        let rowsHtml = '';
        let grandTotal = 0;

        productGroups.forEach(group => {
            const select = group.querySelector('.custom-product-select');
            const quantityInput = group.querySelector('[name="quantity_sell[]"]');
            const priceInput = group.querySelector('.custom-selling-price');
            const discountInput = group.querySelector('[name="discount[]"]');

            if (!select || !quantityInput || !priceInput || !discountInput) return;

            const productName = select.options[select.selectedIndex]?.text || '';
            const quantity = parseFloat(quantityInput.value || 0);
            const price = parseFloat(priceInput.value || 0);
            const discount = parseFloat(discountInput.value || 0);

            if (!select.value || !quantity) return;

            const totalPrice = price * quantity;
            const discountPrice = (discount / 100) * totalPrice;
            const finalTotal = totalPrice - discountPrice;
            grandTotal += finalTotal;

            rowsHtml += `
                    <tr>
                        <td>${productName}</td>
                        <td>${quantity}</td>
                        <td>${price.toLocaleString()}</td>
                        <td>${totalPrice.toLocaleString()}</td>
                        <td>${discount || '0'}%</td>
                        <td>${discountPrice.toLocaleString()}</td>
                        <td>${finalTotal.toLocaleString()}</td>
                    </tr>`;
        });

        if (!rowsHtml) {
            alert("Please select at least one product and quantity before preview.");
            return;
        }

        const previewHTML = `
            <div class="border rounded bg-white shadow-sm">
                <div class="bg-primary text-white p-2 rounded-top">
                    <h5 class="mb-0">Customer: ${customerName}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Discount Unit</th>
                                <th>Discount Price</th>
                                <th>Final Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th colspan="6" class="text-end">Total Amount</th>
                                <th>
                                    <input type="hidden" value="${grandTotal}" name="amount">
                                    ${grandTotal.toLocaleString()}
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

    document.getElementById('custom-customer-selector').addEventListener('change', function() {
        const val = this.value;
        document.getElementById('custom-existing-customer').style.display = val == '1' ? 'block' : 'none';
        document.getElementById('custom-name').style.display = val == '2' ? 'block' : 'none';
        document.getElementById('custom-phone').style.display = val == '2' ? 'block' : 'none';
        document.getElementById('custom-tin').style.display = val == '2' ? 'block' : 'none';
        document.getElementById('custom-address').style.display = val == '2' ? 'block' : 'none';
    });

    // Update selling price and available quantity based on selected product
    // document.addEventListener('change', function(e) {
    //     if (e.target.classList.contains('custom-product-select')) {
    //         const option = e.target.selectedOptions[0];
    //         const group = e.target.closest('.custom-product-group');
    //         if (group) {
    //             group.querySelector('.custom-available-quantity').value = option.getAttribute(
    //                 'data-available-quantity');
    //             group.querySelector('.custom-selling-price').value = option.getAttribute('data-selling-price');
    //         }
    //     }
    // });
</script>
