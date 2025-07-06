<div class="d-flex gap-3">
    <div class="w-50 border p-2 rounded">
        @csrf
        <div class="row g-2">
            <div id="product-container">
                <div class="row g-2 mb-3">
                    <div class="col-12 mb-2">
                        <label class="input-label p-2"><strong>Product Name</strong></label>
                        <select class="form-control select2 product-select" name="product_id[]">
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
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" name="available_quantity[]" class="form-control available-quantity"
                                placeholder="Available quantity" readonly>
                        </div>

                        <div class="col-6">
                            <input type="text" name="selling_price[]" class="form-control selling-price"
                                placeholder="Selling Price" readonly>
                        </div>
                    </div>

                    <div class="row g-2">

                        <div class="col-6">
                            <label class="input-label p-2"><strong>Quantity to sale</strong></label>
                            <input type="number" name="quantity_sell[]" class="form-control form-control"
                                placeholder="Quantity">
                        </div>

                        <div class="col-6">
                            <label class="input-label p-2"><strong>Item Discount</strong></label>
                            <input type="text" name="discount[]" class="form-control form-control"
                                placeholder="Sale At Discount @ eg 2.5">
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 mb-2" id="selected">
                <label class="input-label p-2"><strong>Customer Section</strong></label>
                <select class="form-control">
                    <option value="" selected disabled>--select--</option>
                    <option value="1">Existing Customer</option>
                    <option value="2">New Customer</option>
                </select>
            </div>

            <div class="col-12 mb-2" id="selected1" style="display:none;">
                <label class="input-label p-2"><strong>Customer Name (Company
                        Name)</strong></label>
                <select name="customer_id" class="form-control select2">
                    <option value="" selected disabled>--select--</option>
                    @foreach ($customers as $custome)
                        <option value="{{ $custome->id }}">{{ $custome->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- New Customer Fields -->
            <div class="col-6" id="name" style="display:none;">
                <label class="input-label p-2"><strong>Customer Name</strong></label>
                <input type="text" name="name" class="form-control" placeholder="Customer Name">
            </div>

            <div class="col-6" id="phone" style="display:none;">
                <label class="input-label p-2"><strong>Phone Number</strong></label>
                <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
            </div>

            <div class="col-6" id="TIN" style="display:none;">
                <label class="input-label p-2"><strong>TIN</strong></label>
                <input type="text" name="TIN" class="form-control" placeholder="Tax Identification Number">
            </div>

            <div class="col-6" id="address" style="display:none;">
                <label class="input-label p-2"><strong>Address</strong></label>
                <input type="text" name="price" class="form-control" placeholder="Customer Address">
            </div>

            <div class="col-6 mt-3 w-100">
                <button type="button" id="previewBtn" class="btn btn"
                    style="background-color: orange; border-color: orange; color: #FFFF;">
                    Preview Invoice
                </button>
                <button type="button" class="btn btn float-end" id="addProductBtn"
                    style="background-color: #0000FF; border-color: #0000FF; color: #FFFF;">
                    <i class="fa fa-plus"></i>
                </button>
            </div>

            <div class="col-6 mt-3" id="submitContainer" style="display: none;">
                <button type="submit" class="btn btn-success w-100 float-start">Save
                    Invoice</button>
            </div>

        </div>
    </div>

    <div class="w-50">
        <div class="border p-2 bg-light rounded mt-4" id="invoicePreview">
            <p class="text-center p-5 blink">Invoice Preview will appear here!</p>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('previewBtn').addEventListener('click', function() {
            console.log('Preview button clicked');

            const productGroups = document.querySelectorAll('#product-container .row.g-2.mb-3');
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
                const select = group.querySelector('.product-select');
                const quantityInput = group.querySelector('[name="quantity_sell[]"]');
                const priceInput = group.querySelector('.selling-price');
                const discountInput = group.querySelector('[name="discount[]"]');

                if (!select || !quantityInput || !priceInput || !discountInput) return;

                const productName = select.options[select.selectedIndex]?.text || '';
                const quantity = parseFloat(quantityInput.value || 0);
                const price = parseFloat(priceInput.value || 0);
                const discount = parseFloat(discountInput.value || 0);

                if (!select.value || !quantity) return;

                const totalPrice = price * quantity;
                const discountPrice = discount * price;
                const finalTotal = totalPrice - discountPrice;
                grandTotal += finalTotal;

                rowsHtml += `
                    <tr>
                        <td>${productName}</td>
                        <td>${quantity}</td>
                        <td>${price.toLocaleString()}</td>
                        <td>${totalPrice.toLocaleString()}</td>
                        <td>${discount || '0'}</td>
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

            document.getElementById('invoicePreview').innerHTML = previewHTML;
            document.getElementById('submitContainer').style.display = 'block';
            document.getElementById('previewBtn').style.display = 'none';
            document.getElementById('addProductBtn').style.display = 'none';

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>


<script>
    document.getElementById('addProductBtn').addEventListener('click', function() {
        const container = document.getElementById('product-container');

        const productSet = document.createElement('div');
        productSet.classList.add('row', 'g-2', 'mb-3');

        productSet.innerHTML = `
    <hr class="mt-3" style="width: 95%; margin-left:2.5%;">
    <div class="col-12 mb-2">
        <label class="input-label p-2"><strong>Product Name</strong></label>
        <select class="form-control select2 product-select" name="product_id[]" style="width: 100%;">
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
        <input type="number" name="available_quantity[]" class="form-control available-quantity" placeholder="Available quantity" readonly>
    </div>
    <div class="col-6">
        <input type="text" name="selling_price[]" class="form-control selling-price" placeholder="Selling Price" readonly>
    </div>
    <div class="col-6">
        <label class="input-label p-2"><strong>Quantity to sale</strong></label>
        <input type="number" name="quantity_sell[]" class="form-control" placeholder="Quantity">
    </div>
    <div class="col-6">
        <label class="input-label p-2"><strong>Item Discount</strong></label>
        <input type="text" name="discount[]" class="form-control" placeholder="Sale At Discount @ eg 2.5">
    </div>
`;
        container.appendChild(productSet);

        // Re-initialize Select2 for newly added select
        $('.select2').select2();
    });
</script>
