<?php

namespace App\Http\Controllers\POS;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AzamPayService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PointOfSaleController extends Controller
{
    public function __construct(protected AzampayService $azampay) {}

    function shortNumberFormat($number, $precision = 1)
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, $precision) . 'B';
        } elseif ($number >= 1000000) {
            return round($number / 1000000, $precision) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, $precision) . 'K';
        }

        return $number;
    }
    public function pointOfSale()
    {
        $companyId = Auth::user()->company_id;

        $totalCustomers = DB::table('stakeholders')
            ->where('soft_delete', 0)
            ->where('company_id', $companyId)
            ->count();

        $ordersCounter = DB::table('orders')
            ->where('company_id', $companyId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $productsSold = DB::table('orders')
            ->select('product_id')
            ->where('company_id', $companyId)
            ->whereDate('created_at', Carbon::today())
            ->get();

        $productsSold = $productsSold->unique(function ($item) {
            return $item->product_id;
        })->count();

        $expensesCounter = DB::table('expenses AS EXP')
            ->join('budgets AS B', 'EXP.budget_id', '=', 'B.id')
            ->where('EXP.company_id', $companyId)
            ->where('B.budget_year', Carbon::now()->year)
            ->where('B.soft_delete', 0)
            ->where('EXP.status', 1)
            ->where('EXP.soft_delete', 0)
            ->whereDate('EXP.created_at', Carbon::today())
            ->count();

        $expsnesAmount = DB::table('expenses AS EXP')
            ->join('budgets AS B', 'EXP.budget_id', '=', 'B.id')
            ->where('EXP.company_id', $companyId)
            ->where('B.budget_year', Carbon::now()->year)
            ->where('B.soft_delete', 0)
            ->where('EXP.status', 1)
            ->where('EXP.soft_delete', 0)
            ->whereDate('EXP.created_at', Carbon::today())
            ->sum('amount');

        $salesAmount = DB::table('sales')
            ->whereDate('created_at', Carbon::today())
            ->where('company_id', $companyId)
            ->where('soft_delete', 0)
            ->sum('amount_paid');

        $shortSales = DB::table('sales')
            ->whereDate('created_at', Carbon::today())
            ->where('company_id', $companyId)
            ->where('soft_delete', 0)
            ->sum('amount_paid');

        $newIncome = $this->shortNumberFormat($salesAmount - $expsnesAmount);

        $todaySales = $this->shortNumberFormat($shortSales);

        $topSoldProducts = DB::table('products as p')
            ->join('orders as o', 'p.id', '=', 'o.product_id')
            ->select([
                'p.id as product_id',
                'p.name',
                'p.item_pic',
                DB::raw('COUNT(o.id) as total_orders')
            ])
            ->groupBy('p.id', 'p.name', 'p.item_pic')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();

        $salesTransactions = DB::table('sales AS ST')
            ->join('invoice AS I', 'ST.invoice_id', '=', 'I.id')
            ->select([
                'I.customer_id AS customerId',
                'ST.created_at AS createdDate',
                'ST.amount_paid AS amount',
                'ST.is_paid AS isPaid',
                'ST.status AS status',
            ])
            ->where('ST.company_id', $companyId)
            ->where('I.soft_delete', 0)
            ->where('ST.soft_delete', 0)
            ->orderBy('ST.id', 'DESC')
            ->get();

        $onlineUsers = DB::table('auth')
            ->where('is_online', 1)
            ->count();

        // dd($onlineUsers);

        $authUsers = DB::table('auth')->count();

        $startOfWeek = Carbon::now()->startOfWeek()->format('M d, Y');
        $endOfWeek = Carbon::now()->endOfWeek()->format('M d, Y');

        $start = Carbon::now()->startOfWeek();

        $end = Carbon::now()->endOfWeek();

        $weeklySales = DB::table('sales')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_paid) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->where('company_id', $companyId)
            ->where('soft_delete', 0)
            ->groupBy('date')
            ->pluck('total', 'date');

        $weeklyExpenses = DB::table('expenses')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->where('soft_delete', 0)
            ->where('status', 1)
            ->where('company_id', $companyId)
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $salesData = [];
        $expensesData = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dayName = $date->format('D');
            $labels[] = $dayName;
            $key = $date->toDateString();

            $salesData[] = $weeklySales[$key] ?? 0;
            $expensesData[] = $weeklyExpenses[$key] ?? 0;
        }

        $companyData = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select([
                'C.company_name	 AS companyName',
                'C.id AS companyId',
            ])
            ->where(function ($query) {
                $query->where('A.phone', Auth::user()->username)
                    ->orWhere('A.email', Auth::user()->username);
            })
            ->first();

        $productsInTransactions = DB::table('pos_transactions as POS')
            ->join('products AS PR', 'POS.product_id', '=', 'PR.id')
            ->leftJoin('stocks AS ST', function ($join) {
                $join->on('PR.id', '=', 'ST.storage_item_id')
                    ->on('POS.product_id', '=', 'ST.storage_item_id');
            })
            ->select([
                'POS.product_id',
                'PR.name AS productName',
                'PR.selling_price AS sellingPrice',
                'PR.item_pic AS picture',
                'ST.quantity_total AS availableQuantity',
                DB::raw("SUM(POS.quantity) AS quantity")
            ])
            ->groupBy('POS.product_id', 'PR.name', 'PR.selling_price', 'PR.item_pic', 'ST.quantity_total')
            ->orderBy('quantity', 'DESC')
            ->get();


        $topProductIds = $productsInTransactions->pluck('POS.product_id')
            ->toArray();

        $topProducts = DB::table('products AS PR')
            ->join('stocks AS ST', 'PR.id', '=', 'ST.storage_item_id')
            ->select([
                'PR.name AS productName',
                'PR.selling_price AS sellingPrice',
                'PR.item_pic AS picture',
                'ST.quantity_total AS availableQuantity',
            ])
            // ->whereIn('PR.id', $productsInTransactionsIds)
            ->where('PR.company_id', $companyId)
            ->where('PR.company_id', $companyId)
            ->orderBy('PR.name', 'ASC')
            ->limit(6)
            ->get();

        return view('pos.point-of-sale', [
            'topProducts' => $topProducts,
            'productsSold' => $productsSold,
            'ordersCounter' => $ordersCounter,
            'topSoldProducts' => $topSoldProducts,
        ], compact([
            'totalCustomers',
            'expensesCounter',
            'todaySales',
            'newIncome',
            'salesTransactions',
            'onlineUsers',
            'authUsers',
            'startOfWeek',
            'endOfWeek',
            'labels',
            'salesData',
            'expensesData',
            'companyData'
        ]));
    }

    public function posSales()
    {
        $companyId = Auth::user()->company_id;
        $availableProducts = DB::table('products AS PR')
            ->join('stocks AS ST', 'PR.id', '=', 'ST.storage_item_id')
            ->leftJoin('orders as o', 'PR.id', '=', 'o.product_id')
            ->select([
                'PR.id AS productId',
                'PR.name AS productName',
                'PR.selling_price AS sellingPrice',
                'PR.item_pic AS picture',
                'ST.quantity_total AS availableQuantity',
                DB::raw('COUNT(o.id) as total_orders')
            ])
            ->where('PR.company_id', $companyId)
            ->groupBy('PR.id', 'PR.name', 'PR.selling_price', 'PR.item_pic', 'ST.quantity_total', 'o.id')
            ->orderBy('total_orders', 'DESC')
            ->distinct()
            ->get();

        return view('pos.sales', compact([
            'availableProducts',
        ]));
    }

    public function posProductView($productId)
    {
        $productId = json_decode(Crypt::decrypt($productId), true);
        $companyId = Auth::user()->company_id;
        $product = DB::table('products AS PR')
            ->join('stocks AS ST', 'PR.id', '=', 'ST.storage_item_id')
            ->select([
                'PR.id AS productId',
                'PR.name AS productName',
                'PR.selling_price AS sellingPrice',
                'PR.item_pic AS picture',
                'ST.quantity_total AS qty',
            ])
            ->where('PR.company_id', $companyId)
            ->where('PR.id', $productId)
            ->orderBy('PR.name', 'ASC')
            ->first();

        return view('pos.order', compact([
            'product',
            'productId'
        ]));
    }

    public function checkOrder($productId)
    {
        $productId = json_decode(Crypt::decrypt($productId), true);
        // $companyId = Auth::user()->company_id;
        $product = DB::table('products AS PR')
            ->join('stocks AS ST', 'PR.id', '=', 'ST.storage_item_id')
            ->select([
                'PR.id AS productId',
                'PR.name AS productName',
                'PR.selling_price AS sellingPrice',
                'PR.item_pic AS picture',
                'ST.quantity_total AS qty',
            ])
            // ->where('PR.company_id', $companyId)
            ->where('PR.id', $productId)
            ->orderBy('PR.name', 'ASC')
            ->first();

        return view('pos.check-order', compact([
            'product',
            'productId'
        ]));
    }

    public function productLists()
    {
        $companyId = Auth::user()->company_id;

        $products = DB::table('products AS PR')
            ->join('stores AS S', 'PR.store_id', '=', 'S.id')
            ->join('stocks AS ST', 'PR.id', '=', 'ST.storage_item_id')
            ->select([
                'PR.serial_no AS serialNo',
                'PR.name AS name',
                'S.store_name AS store',
                'PR.item_pic AS picture',
                'PR.sku AS sku',
                'PR.id AS productId',
            ])
            ->whereNot('ST.quantity_total', 0)
            ->where('PR.company_id', $companyId)
            ->get();

        $counter = $products->count();

        return view('pos.product-lists', compact([
            'products',
            'counter'
        ]));
    }

    public function downloadQrCode(Request $request)
    {
        $request->validate([
            'check' => 'nullable|array',
            'check.*' => 'nullable|string',

            'productId' => 'nullable|string',
            'btn' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        if ($request->has('btn') && $request->btn === "btn") {
            $productId = json_decode(Crypt::decrypt($request->productId), true);

            if ($request->hasFile('image')) {
                $filePath = $request->file('image')->store('product_pics', 'public');
            }

            DB::table('products')->where('id', $productId)->update([
                'item_pic' => $filePath,
            ]);

            return redirect()->back()->with('success_msg', 'Image uploaded successfully!');
        } else {
            $productIds = [];
            $decodedIds = [];

            foreach ($request->check as $check) {
                $productIds[] = Crypt::decrypt($check);
            }

            foreach ($productIds as $id) {
                $decodedIds[] = json_decode($id, true);
            }

            $companyId = Auth::user()->company_id;

            $products = DB::table('products AS PR')
                ->join('stores AS S', 'PR.store_id', '=', 'S.id')
                ->select([
                    'PR.serial_no AS serialNo',
                    'PR.name AS name',
                    'S.store_name AS store',
                    'PR.item_pic AS picture',
                    'PR.sku AS sku',
                    'PR.id AS productId',
                ])
                ->whereIn('PR.id', $decodedIds)
                ->where('PR.company_id', $companyId)
                ->get();

            $products = $products->map(function ($product) {
                $productId = Crypt::encrypt($product->productId);
                $qrLink = route('check-pos-order', $productId);

                $qrSvg = QrCode::format('svg')->size(150)->generate($qrLink);
                $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

                $imagePath = storage_path('app/public/' . $product->picture);
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
                } else {
                    $imageBase64 = null;
                }

                return (object) [
                    'image' => $imageBase64,
                    'qrCode' => $qrBase64,
                    'serialNo' => $product->serialNo,
                    'item_name' => $product->name,
                    'sku' => $product->sku,
                    'store' => $product->store,
                ];
            });

            return Pdf::loadView('pdf.qrcodes', [
                'products' => $products,
            ])->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ])->download("QRCode-List.pdf");
        }
    }

    public function posSalesReport(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $from = null;
        $to = null;
        $orders = DB::table('orders AS O')
            ->leftJoin('products AS PR', function ($join) {
                $join->on('O.product_id', '=', 'PR.id')
                    ->on('O.company_id', '=', 'PR.company_id');
            })
            ->select([
                'O.id AS saleId',
                'O.ref AS referenceId',
                'O.created_at AS saleDate',
                'O.amount AS amount',
                'PR.name AS productName',
                'PR.serial_no AS serialNo',
                'O.phone AS customerPhone',
            ])
            ->where('O.company_id', $companyId)
            ->orderBy('O.created_at', 'DESC')
            ->get();

        if ($request->has('from') && $request->has('to') && $request->from != null && $request->to != null) {
            $from = $request->from;
            $to = $request->to;

            $orders = DB::table('orders AS O')
                ->leftJoin('products AS PR', function ($join) {
                    $join->on('O.product_id', '=', 'PR.id')
                        ->on('O.company_id', '=', 'PR.company_id');
                })
                ->select([
                    'O.id AS saleId',
                    'O.ref AS referenceId',
                    'O.created_at AS saleDate',
                    'O.amount AS amount',
                    'PR.name AS productName',
                    'PR.serial_no AS serialNo',
                    'O.phone AS customerPhone',
                ])
                ->whereBetween('O.created_at', [$from, $to])
                ->where('O.company_id', $companyId)
                ->orderBy('O.created_at', 'DESC')
                ->get();
        }

        return view('pos.sales-report', compact([
            'orders',
            'from',
            'to',
        ]));
    }

    public function downloadPOSReportSales($range)
    {
        $range = Crypt::decrypt($range);
        $range = json_decode($range, true);
        $from = $range['from'];
        $to = $range['to'];
        $companyId = Auth::user()->company_id;

        $orders = DB::table('orders AS O')
            ->leftJoin('products AS PR', function ($join) {
                $join->on('O.product_id', '=', 'PR.id')
                    ->on('O.company_id', '=', 'PR.company_id');
            })
            ->select([
                'O.id AS saleId',
                'O.ref AS referenceId',
                'O.created_at AS saleDate',
                'O.amount AS amount',
                'PR.name AS productName',
                'PR.serial_no AS serialNo',
                'O.phone AS customerPhone',
            ])
            ->whereBetween('O.created_at', [$from, $to])
            ->where('O.company_id', $companyId)
            ->orderBy('O.created_at', 'DESC')
            ->get();

        $companyName = DB::table('companies')
            ->select('company_name')
            ->where('id', $companyId)
            ->value('company_name');

        $totalAmount = 0;

        $response = new StreamedResponse(function () use ($orders, $companyName, $from, $to, $totalAmount) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Company Name:', $companyName ?? '']);

            fputcsv($handle, ['Sales Report:', Carbon::parse($from)->format('M d, Y') . ' - ' . Carbon::parse($to)->format('M d, Y') ?? '']);

            fputcsv($handle, []);

            fputcsv($handle, ['Ref ID', 'Product SN', 'Prdoduct Name', 'Customer Phone', 'Due Date', 'Amount']);

            foreach ($orders as $row) {
                $totalAmount += $row->amount;
                fputcsv($handle, [
                    $row->referenceId,
                    $row->serialNo,
                    $row->productName,
                    $row->customerPhone,
                    Carbon::parse($row->saleDate)->format('M d, Y'),
                    number_format($row->amount, 2),
                ]);
            }

            fputcsv($handle, [
                '',
                '',
                '',
                '',
                '',
                '',
            ]);

            fputcsv($handle, [
                'Total Amount',
                '',
                '',
                '',
                '',
                number_format($totalAmount, 2),
            ]);

            fclose($handle);
        });

        $filename = $companyName . ' ' . ' - ' . ' ' . Carbon::now()->format('Y_m_d_His') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename={$filename}");

        return $response;
    }

    public function posStockReport()
    {
        return redirect()->back();
    }

    public function ussdPush(Request $request)
    {
        $request->validate([
            'phone'   => 'required|string',
            'amount'  => 'required|numeric',
            'quantity' => 'required|numeric',
            'pay' => 'required|string',
            'productId' => 'required|string',
        ]);

        $azamPay = new AzamPayService();

        $phone = ltrim($request->phone, '0');
        $msisdn = "+255" . $phone;

        $amount = $request->amount;
        $reference = rand(100, 999999);
        $description = "Payment for #{$reference}";

        if ($request->has('pay') && $request->pay === "cash") {
            $productId = json_decode(Crypt::decrypt($request->productId), true);

            DB::table('stock_out_transaction')->insert([
                'product_id' => $productId,
                'stockout_quantity' => $request->quantity,
                'comments' => $description,
            ]);

            $stockData = DB::table('stocks')->where('storage_item_id', $productId)->first();
            $product = DB::table('products')->where('id', $productId)->first();

            DB::table('stocks')->where('storage_item_id', $productId)->update([
                'quantity_out' => $request->quantity,
                'quantity_total' => $stockData->quantity_total - $request->quantity,
            ]);

            DB::table('orders')->insert([
                'product_id' => $productId,
                'ref' => $reference,
                'phone' => $msisdn,
                'amount' => $amount,
                'company_id' => $product->company_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('sales')->insert([
                'amount_paid' => $amount,
                'payment_method' => 'cash',
                'is_paid' => 1,
                'status' => 1,
                'notes' => $description,
                'balance' => $amount,
                'company_id' => $product->company_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success_msg', 'Payment successfully!');
        }

        if ($request->has('pay') && $request->pay === "mobile") {
            $response = $azamPay->ussdPush(
                $msisdn,
                $amount,
                $reference,
                $description
            );

            // Check if AzamPay accepted the request
            if (isset($response['status']) && $response['status'] == 200) {
                $productId = json_decode(Crypt::decrypt($request->productId), true);

                // save stock transaction
                DB::table('stock_out_transaction')->insert([
                    'product_id'        => $productId,
                    'stockout_quantity' => $request->quantity,
                    'comments'          => $description,
                ]);

                $stockData = DB::table('stocks')->where('storage_item_id', $productId)->first();
                $product   = DB::table('products')->where('id', $productId)->first();

                DB::table('stocks')->where('storage_item_id', $productId)->update([
                    'quantity_out'   => $request->quantity,
                    'quantity_total' => $stockData->quantity_total - $request->quantity,
                ]);

                DB::table('orders')->insert([
                    'product_id' => $productId,
                    'ref'        => $reference,
                    'phone'      => $msisdn,
                    'amount'     => $amount,
                    'company_id' => $product->company_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::table('sales')->insert([
                    'amount_paid'   => $amount,
                    'payment_method' => 'mobile',
                    'is_paid'       => 0,
                    'status'        => 0,
                    'notes'         => $description,
                    'balance'       => $amount,
                    'company_id'    => $product->company_id,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]);

                return response()->json([
                    'message'  => 'Mobile payment initiated, waiting for customer confirmation.',
                    'azampay'  => $response,
                ]);
            }

            return response()->json([
                'error' => 'USSD Push failed',
                'azampay_response' => $response,
            ], 400);
        }
    }

    public function handleCallback(Request $request)
    {
        $payload = $request->all();

        if (($payload['transactionStatus'] ?? '') === 'success') {
            // TODO: save to DB, mark invoice as paid, etc.
            return response()->json(['message' => 'Payment confirmed']);
        }

        return response()->json(['message' => 'Payment not successful'], 400);
    }
}
