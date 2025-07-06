<div class="d-flex gap-3" id="order-invoice-section">
    <div class="w-50 border p-2 rounded">
        @csrf
        <div id="order-product-container">
            <!-- First Product Entry -->
            <div class="row g-2 mb-3 order-product-group">
                <div class="col-6">
                    <label class="input-label p-2"><strong>Product Name</strong></label>
                    <input type="text" class="form-control order-product-select" name="product_name[]"
                        placeholder="Product name">
                </div>

                <div class="col-6">
                    <label class="input-label p-2"><strong>Item Price</strong></label>
                    <input type="text" name="amountPay[]" class="form-control order-selling-price"
                        placeholder="Item Price">
                </div>

                <input type="hidden" name="order_status" value="pending">

                <div class="col-6">
                    <label class="input-label p-2"><strong>Quantity</strong></label>
                    <input type="number" name="quantity[]" class="form-control" placeholder="Quantity">
                </div>

                <div class="col-6">
                    <label class="input-label p-2"><strong>Item Discount</strong></label>
                    <input type="text" name="discount[]" class="form-control" placeholder="Discount @ eg 2.5">
                </div>
            </div>
        </div>

        <!-- Customer Section -->
        <div class="col-12 mb-2">
            <label class="input-label p-2"><strong>Customer Section</strong></label>
            <select class="form-control" id="order-customer-selector">
                <option value="" selected disabled>--select--</option>
                <option value="1">Existing Customer</option>
                <option value="2">New Customer</option>
            </select>
        </div>

        <div class="col-12 mb-2" id="order-existing-customer" style="display:none;">
            <label class="input-label p-2"><strong>Customer Name (Company Name)</strong></label>
            <select name="customer_id" class="form-control select2">
                <option value="" selected disabled>--select--</option>
                @foreach ($customers as $custome)
                    <option value="{{ $custome->id }}">{{ $custome->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6" id="order-name" style="display:none;">
            <label class="input-label p-2"><strong>Customer Name</strong></label>
            <input type="text" name="order_name" class="form-control" placeholder="Customer Name">
        </div>

        <div class="col-6" id="order-phone" style="display:none;">
            <label class="input-label p-2"><strong>Phone Number</strong></label>
            <input type="tel" name="order_phone" class="form-control" placeholder="Phone Number">
        </div>

        <div class="col-6" id="order-tin" style="display:none;">
            <label class="input-label p-2"><strong>TIN</strong></label>
            <input type="text" name="order_TIN" class="form-control" placeholder="Tax Identification Number">
        </div>

        <div class="col-6" id="order-address" style="display:none;">
            <label class="input-label p-2"><strong>Address</strong></label>
            <input type="text" name="order_address" class="form-control" placeholder="Customer Address">
        </div>

        <!-- Action Buttons -->
        <div class="col-6 mt-3 w-100">
            <button type="button" id="order-preview-btn" class="btn btn-warning">Preview Order</button>
            <button type="button" class="btn btn-primary float-end" id="order-add-btn">
                <i class="fa fa-plus"></i>
            </button>
        </div>

        <div class="col-6 mt-3" id="order-submit-container" style="display: none;">
            <button type="submit" class="btn btn-success w-100 float-start">Save Order</button>
        </div>
    </div>

    <!-- Order Preview -->
    <div class="w-50">
        <div class="border p-2 bg-light rounded mt-4" id="order-invoice-preview">
            <p class="text-center p-5 blink">Order Preview will appear here!</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Customer selector behavior
        document.getElementById('order-customer-selector').addEventListener('change', function () {
            const selected = this.value;
            const show = id => document.getElementById(id).style.display = 'block';
            const hide = id => document.getElementById(id).style.display = 'none';

            if (selected === '1') {
                show('order-existing-customer');
                hide('order-name'); hide('order-phone'); hide('order-tin'); hide('order-address');
            } else if (selected === '2') {
                hide('order-existing-customer');
                show('order-name'); show('order-phone'); show('order-tin'); show('order-address');
            } else {
                hide('order-existing-customer'); hide('order-name'); hide('order-phone'); hide('order-tin'); hide('order-address');
            }
        });

        // Add new product row
        document.getElementById('order-add-btn').addEventListener('click', function () {
            const container = document.getElementById('order-product-container');
            const newGroup = document.createElement('div');
            newGroup.classList.add('row', 'g-2', 'mb-3', 'order-product-group');
            newGroup.innerHTML = `
                <hr class="mt-3" style="width: 95%; margin-left:2.5%;">
                <div class="col-6">
                    <label class="input-label p-2"><strong>Product Name</strong></label>
                    <input type="text" class="form-control order-product-select" name="product_name[]" placeholder="Product name">
                </div>
                <div class="col-6">
                    <label class="input-label p-2"><strong>Item Price</strong></label>
                    <input type="text" name="amountPay[]" class="form-control order-selling-price" placeholder="Item Price">
                </div>
                <div class="col-6">
                    <label class="input-label p-2"><strong>Quantity</strong></label>
                    <input type="number" name="quantity[]" class="form-control" placeholder="Quantity">
                </div>
                <div class="col-6">
                    <label class="input-label p-2"><strong>Item Discount</strong></label>
                    <input type="text" name="discount[]" class="form-control" placeholder="Discount @ eg 2.5">
                </div>
            `;
            container.appendChild(newGroup);
        });

        // Preview invoice
        document.getElementById('order-preview-btn').addEventListener('click', function () {
            const productGroups = document.querySelectorAll('.order-product-group');
            const customerSelect = document.querySelector('[name="order_customer_id"]');
            const customerInput = document.querySelector('[name="order_name"]');
            let customerName = 'N/A';

            if (customerSelect && customerSelect.value) {
                customerName = customerSelect.options[customerSelect.selectedIndex].text;
            } else if (customerInput && customerInput.value.trim() !== '') {
                customerName = customerInput.value;
            }

            let rowsHtml = '';
            let grandTotal = 0;

            productGroups.forEach(group => {
                const product = group.querySelector('[name="product_name[]"]')?.value || '';
                const qty = parseFloat(group.querySelector('[name="quantity[]"]')?.value || 0);
                const price = parseFloat(group.querySelector('[name="amountPay[]"]')?.value || 0);
                const discount = parseFloat(group.querySelector('[name="discount[]"]')?.value || 0);

                if (!product || qty === 0) return;

                const total = price * qty;
                const discountTotal = discount * price;
                const final = total - discountTotal;
                grandTotal += final;

                rowsHtml += `
                    <tr>
                        <td>${product}</td>
                        <td>${qty}</td>
                        <td>${price.toLocaleString()}</td>
                        <td>${total.toLocaleString()}</td>
                        <td>${discount.toLocaleString()}</td>
                        <td>${discountTotal.toLocaleString()}</td>
                        <td>${final.toLocaleString()}</td>
                    </tr>`;
            });

            const previewHTML = `
                <div class="border rounded bg-white shadow-sm">
                    <div class="bg-primary text-white p-2 rounded-top">
                        <h5 class="mb-0">Customer: ${customerName}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Discount Unit</th>
                                    <th>Discount Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>${rowsHtml}</tbody>
                            <tfoot>
                                <tr class="table-success">
                                    <th colspan="6" class="text-end">Total Amount</th>
                                    <th>
                                        <input type="hidden" name="amount" value="${grandTotal}">
                                        ${grandTotal.toLocaleString()}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>`;

            document.getElementById('order-invoice-preview').innerHTML = previewHTML;
            document.getElementById('order-submit-container').style.display = 'block';
            document.getElementById('order-add-btn').style.display = 'none';
            document.getElementById('order-preview-btn').style.display = 'none';

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
