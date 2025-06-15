<div class="d-flex gap-3" id="order-invoice-section">
    <div class="w-100 border p-2 rounded">
        @csrf
        <!-- First Product Entry -->
        <div class="row g-2 mb-3 order-product-group">
            <div class="row w-100">
                <div class="col-4">
                    <label class="input-label p-2"><strong>Service Name</strong></label>
                    <input type="text" class="form-control order-product-select" name="service_name[]"
                        placeholder="Service Name">
                </div>

                <div class="col-4">
                    <label class="input-label p-2"><strong>Service Cost / Price</strong></label>
                    <input type="text" name="amount[]" class="form-control order-selling-price"
                        placeholder="Service Cost">
                </div>

                <div class="col-4">
                    <label class="input-label p-2"><strong>Category</strong></label>
                    <select type="number" name="category[]" class="form-control">
                        <option value="" selected disabled>--select--</option>
                        <option value="Commercial">Commercial</option>
                        <option value="Residential">Residential</option>
                    </select>
                </div>
            </div>

            <div class="col-12">
                <label class="input-label p-2"><strong>Description</strong></label>
                <textarea name="description[]" class="form-control" placeholder="Description"></textarea>
            </div>
        </div>

        <div class="row w-100">
            <div id="order-product-container">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="col-6 mt-3 w-100">
            <button type="submit" id="order-preview-btn" class="btn btn-warning">Save Data</button>
            <button type="button" class="btn btn-primary float-end" id="order-add-btn">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new product row
        document.getElementById('order-add-btn').addEventListener('click', function() {
            const container = document.getElementById('order-product-container');
            const newGroup = document.createElement('div');
            newGroup.classList.add('row', 'g-2', 'mb-3', 'order-product-group');

            newGroup.innerHTML = `
        <div class="row w-100">
            <div class="col-4">
                <label class="input-label p-2"><strong>Service Name</strong></label>
                <input type="text" class="form-control order-product-select" name="service_name[]" placeholder="Service Name">
            </div>
            <div class="col-4">
                <label class="input-label p-2"><strong>Service Cost / Price</strong></label>
                <input type="text" name="amount[]" class="form-control order-selling-price" placeholder="Service Cost">
            </div>
            <div class="col-4">
                <label class="input-label p-2"><strong>Category</strong></label>
                <select name="category[]" class="form-control">
                    <option value="" selected disabled>--select--</option>
                </select>
            </div>
        </div>

        <div class="col-12">
            <label class="input-label p-2"><strong>Description</strong></label>
            <textarea name="description[]" class="form-control" placeholder="Description"></textarea>
        </div>
    `;

            container.appendChild(newGroup);
        });

    });
</script>
