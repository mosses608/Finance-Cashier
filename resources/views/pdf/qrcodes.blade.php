<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Items</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .table thead {
            background-color: #f1f3f5; /* light gray instead of blue */
            color: #212529; /* dark text */
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6 !important;
            vertical-align: middle;
            padding: 12px;
        }

        .table-hover tbody tr:hover {
            background-color: #f9fafb;
        }

        .barcode-box {
            text-align: center;
            padding: 6px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            display: inline-block;
        }

        .item-pic {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mb-4 text-center text-dark fw-bold">Inventory Items</h2>
        <div class="shadow-sm p-3">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="text-center">
                        <tr>
                            <th>SN</th>
                            <th>Picture</th>
                            <th>Serial No</th>
                            <th>Item Name</th>
                            <th>SKU</th>
                            <th>Store</th>
                            <th>BarCode</th>
                        </tr>
                    </thead>
                    @php
                        $n = 1;
                    @endphp
                    <tbody>
                        @foreach ($products as $list)
                            <tr>
                                <td class="text-center">{{ $n++ }}</td>
                                <td class="text-center">
                                    <img src="{{ $list->image }}" alt="Item Picture" class="item-pic">
                                </td>
                                <td class="text-center">{{ $list->serialNo }}</td>
                                <td>{{ $list->item_name }}</td>
                                <td>{{ $list->sku }}</td>
                                <td>{{ $list->store }}</td>
                                <td class="text-center">
                                    <div class="barcode-box">
                                        <img src="{{ $list->qrCode }}" width="80" height="80" alt="Barcode">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
