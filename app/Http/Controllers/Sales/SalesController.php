<?php

namespace App\Http\Controllers\Sales;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesController extends Controller
{
    //
    // public function createSales(Request $request)
    // {
    //     $request->validate([
    //         'invoice_id' => 'integer|min:0',
    //     ]);

    //     $invoiceData = collect();

    //     if ($request->has('invoice_id') && $request->invoice_id != null) {
    //         $invoiceData = DB::table('invoice AS I')
    //             ->join('customer AS C', 'I.customer_id', '=', 'C.id')
    //             ->where('I.soft_delete', 0)
    //             ->where('C.soft_delete', 0)
    //             ->where('I.id', $request->invoice_id)
    //             ->first();
    //     }

    //     return view('sales.create-sales', compact('invoiceData'));
    // }

    public function createSales(Request $request)
    {
        $invoiceData = collect();
        $suupliers = collect();

        if ($request->ajax()) {
            $request->validate([
                'invoice_id' => 'required|integer|min:0',
            ]);

            $purchaseOrderNumber = $request->invoice_id;

            $invoiceId = DB::table('purchases_orders')
                ->select('invoice_id')
                ->where('po_number', $request->invoice_id)
                ->value('invoice_id');

            $suupliers = DB::table('stakeholders')
                ->select('name', 'id')
                ->where('stakeholder_category', 2)
                ->where('company_id', Auth::user()->company_id)
                ->get();

            if (!$invoiceId) {
                return response()->json(['html' => "<p class='text-danger'>No purchase order found.</p>"]);
            }

            $invoiceData = DB::table('invoice AS I')
                ->join('stakeholders AS C', 'I.customer_id', '=', 'C.id')
                ->join('invoice_status AS IST', 'I.status', '=', 'IST.id')
                ->join('purchases_orders AS PO', 'PO.invoice_id', '=', 'I.id')
                ->where('I.soft_delete', 0)
                ->where('C.soft_delete', 0)
                ->where('I.company_id', Auth::user()->company_id)
                ->where('I.id', $invoiceId)
                ->where('I.is_profoma', 0)
                ->select([
                    'I.*',
                    'C.name as customer_name',
                    'C.phone',
                    'C.TIN',
                    'IST.name AS statusName',
                    'C.vrn AS vrn',
                    'I.status AS status',
                    'PO.po_number AS po_number',
                ])
                ->first();

            if (!$invoiceData) {
                return response()->json(['html' => "<p class='text-danger'>No invoice found.</p>"]);
            }

            $hasVrn = DB::table('companies')->where('id', Auth::user()->company_id)->select('vrn')->value('vrn') ?? null;

            $html = view('partials.fetched-invoice', compact('invoiceData', 'hasVrn', 'purchaseOrderNumber', 'suupliers'))->render();
            return response()->json(['html' => $html]);
        }

        $currentDaySales = DB::table('sales AS S')
            ->join('purchases_orders AS PO', 'S.invoice_id', '=', 'PO.invoice_id')
            ->join('stakeholders AS SH', 'PO.supplier_id', '=', 'SH.id')
            ->select([
                'PO.po_number AS po_number',
                'S.invoice_id',
                'S.amount_paid',
                'SH.name AS stakeholder',
                'SH.phone AS phoneNumber',
                'S.status',
                'S.created_at',
                'S.id AS autoId',
                'S.updated_at',
            ])
            ->where('SH.stakeholder_category', 2)
            ->where('S.company_id', Auth::user()->company_id)
            ->where('S.soft_delete', 0)
            ->whereDate('S.created_at', Carbon::today())
            ->orderByDesc('S.id', 'DESC')
            ->get();

        return view('sales.create-sales', compact('invoiceData', 'currentDaySales'));
    }

    public function salesList()
    {
        $companyId = Auth::user()->company_id;

        $companySales = DB::table('sales')
            ->select([
                'invoice_id',
                'amount_paid',
                'payment_method',
                'status',
                'created_at',
                'id AS autoId',
                'updated_at',
            ])
            ->where('company_id', $companyId)
            ->where('soft_delete', 0)
            ->orderByDesc('id', 'DESC')
            ->get();

        return view('sales.sales-list', compact('companySales'));
    }

    public function salesReports(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $salesReports = DB::table('sales AS S')
            ->join('purchases_orders AS PO', 'S.invoice_id', '=', 'PO.invoice_id')
            ->join('stakeholders AS SH', 'PO.supplier_id', '=', 'SH.id')
            ->select([
                'S.invoice_id',
                'S.amount_paid',
                'SH.name AS stakeholder',
                'SH.phone AS phoneNumber',
                'S.status',
                'S.created_at',
                'S.id AS autoId',
                'S.updated_at',
                'PO.po_number AS po_number'
            ])
            ->where('SH.stakeholder_category', 2)
            ->where('S.company_id', $companyId)
            ->where('S.soft_delete', 0)
            ->orderByDesc('S.id', 'DESC')
            ->get();

        $from = null;
        $to = null;

        if ($request->has('from') && $request->has('to') && $request->from != null && $request->to != null) {
            $from = $request->from;
            $to = $request->to;

            $salesReports = DB::table('sales AS S')
                ->join('purchases_orders AS PO', 'S.invoice_id', '=', 'PO.invoice_id')
                ->join('stakeholders AS SH', 'PO.supplier_id', '=', 'SH.id')
                ->select([
                    'S.invoice_id',
                    'S.amount_paid',
                    'SH.name AS stakeholder',
                    'SH.phone AS phoneNumber',
                    'S.status',
                    'S.created_at',
                    'S.id AS autoId',
                    'S.updated_at',
                    'PO.po_number AS po_number'
                ])
                ->where('SH.stakeholder_category', 2)
                ->whereBetween('S.created_at', [$from, $to])
                ->where('S.company_id', $companyId)
                ->where('S.soft_delete', 0)
                ->orderByDesc('S.id', 'DESC')
                ->get();
        }

        return view('sales.sales-reports', compact('salesReports', 'from', 'to'));
    }

    public function downloadReport($validData)
    {
        try {
            $decryptedData = Crypt::decrypt($validData);
            $decodedData = json_decode($decryptedData, true);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $from = $decodedData['from'];
        $to = $decodedData['to'];
        $companyId = Auth::user()->company_id;

        $salesReports = DB::table('sales AS S')
            ->join('purchases_orders AS PO', 'S.invoice_id', '=', 'PO.invoice_id')
            ->join('stakeholders AS SH', 'PO.supplier_id', '=', 'SH.id')
            ->select([
                'S.invoice_id AS invoiceId',
                'S.amount_paid AS amount',
                'SH.name AS stakeholder',
                'SH.phone AS phoneNumber',
                'S.status AS status',
                'S.created_at AS date',
                'S.id AS autoId',
                'S.updated_at',
                'PO.po_number AS po_number'
            ])
            ->where('SH.stakeholder_category', 2)
            ->whereBetween('S.created_at', [$from, $to])
            ->where('S.company_id', $companyId)
            ->where('S.soft_delete', 0)
            ->orderByDesc('S.id', 'DESC')
            ->get();

        $response = new StreamedResponse(function () use ($salesReports, $from, $to) {
            set_time_limit(0);
            ini_set('output_buffering', 'off');
            ini_set('zlib.output_compression', false);
            ob_implicit_flush(true);

            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['From Date:', Carbon::parse($from)->format('M d, Y') ?? '']);
            fputcsv($handle, ['To Date:', Carbon::parse($to)->format('M d, Y') ?? '']);
            fputcsv($handle, []);
            fputcsv($handle, ['Purchase Order No', 'Invoice Number', 'Supplier', 'Amount', 'Status', 'Due Date']);

            $n = 1;
            foreach ($salesReports as $report) {
                fputcsv($handle, [
                    $n++,
                    $report->po_number,
                    $report->invoiceId,
                    $report->stakeholder . ' - ' . $report->phoneNumber,
                    number_format($report->amount),
                    $report->status,
                    Carbon::parse($report->date)->format('M d, Y'),
                ]);
                ob_flush();
                flush();
            }
            fclose($handle);
        });

        $filename = 'purchase-order-report.csv';

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    public function storeSales(Request $request)
    {
        try {
            $request->validate([
                'invoice_id' => 'required|integer|min:1',
                'tax' => 'nullable|string',
                'amount_paid' => 'required|numeric',
                'notes' => 'nullable|string|max:255',
                'checked' => 'required|integer',
                'expected_delivery_date' => 'required|date',
                'budget_year' => 'required|integer',
                'supplier_id' => 'required|integer',
                'purchase_order' => 'required|string',
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error_msg', 'Error:' . $th->getMessage());
        }

        try {
            $purchaseOrder = Crypt::decrypt($request->purchase_order);
        } catch (\Throwable $err) {
            return redirect()->back()->with('error_msg', 'Error:' . $err->getMessage());
        }

        $userId = Auth::user()->id ?? 1;

        $companyId = Auth::user()->company_id;

        $invoiceItems = DB::table('invoice_items')->select('item_id', 'quantity')
            ->where('invoice_id', $request->invoice_id)
            ->where('soft_delete', 0)
            ->get();

        if ($invoiceItems->count() > 0) {
            foreach ($invoiceItems as $item) {
                DB::table('stock_out_transaction')->insert([
                    'user_id' => Auth::user()->user_id,
                    'product_id' => $item->item_id,
                    'stockout_quantity' => $item->quantity,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        DB::table('sales')->insert([
            'company_id' => $companyId,
            'user_id' => $userId,
            'invoice_id' => $request->invoice_id,
            'tax' => str_replace(',', '', $request->tax),
            'balance' => $request->amount_paid,
            'amount_paid' => $request->amount_paid,
            'status' => 1,
            'is_paid' => 1,
            'notes' => $request->notes,
            'payment_method' => 'manual_pay',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('invoice')->where('id', $request->invoice_id)
            ->update([
                'status' => 3,
            ]);

        DB::table('purchases_orders')->where('po_number', $purchaseOrder)->update([
            'budget_year' => $request->budget_year,
            'supplier_id' => $request->supplier_id,
            'order_date' => Carbon::now(),
            'expected_delivery_date' => $request->expected_delivery_date,
            'status' => 'approved',
            'notes' => $request->notes,
            'issued_by' => Auth::user()->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success_msg', 'New sales created successfully!');
    }

    public function viewReceipt($encryptedSaleId)
    {
        try {
            $saleAutoId = Crypt::decrypt($encryptedSaleId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $invoiceIdFromSales = DB::table('sales')->where('id', $saleAutoId)->value('invoice_id');
        // dd($invoiceIdFromSales);

        $customerData = DB::table('stakeholders AS C')
            ->join('invoice AS I', 'C.id', '=', 'I.customer_id')
            ->select('C.*')
            ->where('I.soft_delete', 0)
            ->where('C.soft_delete', 0)
            ->where('I.id', $invoiceIdFromSales)
            ->first();

        $companyData = DB::table('companies AS C')
            ->select([
                'C.company_name AS name',
                'C.address AS address',
                'C.tin AS TIN',
                'C.vrn AS vrn',
                'C.company_email AS email',
                'C.logo AS logo',
                'C.website AS webiste'
            ])
            ->where('id', Auth::user()->company_id)
            ->where('C.soft_delete', 0)
            ->first();

        $hasVrn = $companyData->vrn ?? null;

        $paymentData = DB::table('sales')
            ->where('id', $saleAutoId)
            ->first();

        // dd($paymentData);

        $receiptSalesOutOfStore = DB::table('profoma_out_store AS OST')
            ->join('invoice AS I', 'OST.invoice_id', '=', 'I.id')
            ->select('OST.*')
            ->where('I.soft_delete', 0)
            ->where('OST.soft_delete', 0)
            ->where('I.id', $invoiceIdFromSales)
            ->get();

        $receiptSalesFromStore = DB::table('products AS PR')
            ->join('invoice_items AS ITM', 'PR.id', '=', 'ITM.item_id')
            ->join('invoice AS I', 'ITM.invoice_id', '=', 'I.id')
            ->select('PR.*', 'ITM.*')
            ->where('I.soft_delete', 0)
            ->where('PR.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->where('I.id', $invoiceIdFromSales)
            ->get();

        $salesReceiptFromServices = DB::table('invoive_service_items AS ISI')
            ->join('service AS SV', 'ISI.service_id', '=', 'SV.id')
            ->join('invoice AS INV', 'ISI.invoice_id', '=', 'INV.id')
            ->select('SV.*', 'ISI.*', 'SV.*')
            ->where('INV.soft_delete', 0)
            ->where('ISI.soft_delete', 0)
            ->where('INV.id', $invoiceIdFromSales)
            ->get();

        // dd($salesReceiptFromServices);

        return view('sales.sales-receipt', compact([
            'receiptSalesOutOfStore',
            'receiptSalesFromStore',
            'saleAutoId',
            'paymentData',
            'customerData',
            'salesReceiptFromServices',
            'companyData',
            'hasVrn'
        ]));
    }

    public function downloadReceipt($encryptedReceiptId)
    {
        $saleAutoId = Crypt::decrypt($encryptedReceiptId);

        $invoiceIdFromSales = DB::table('sales')->where('id', $saleAutoId)->value('invoice_id');

        // Fetch necessary data
        $customerData = DB::table('stakeholders AS C')
            ->join('invoice AS I', 'C.id', '=', 'I.customer_id')
            ->select('C.*')
            ->where('I.soft_delete', 0)
            ->where('C.soft_delete', 0)
            ->where('I.id', $invoiceIdFromSales)
            ->first();

        $companyData = DB::table('companies AS C')
            ->select([
                'C.company_name AS name',
                'C.address AS address',
                'C.tin AS TIN',
                'C.vrn AS vrn',
                'C.company_email AS email',
                'C.logo AS logo',
                'C.website AS webiste'
            ])
            ->where('id', Auth::user()->company_id)
            ->where('C.soft_delete', 0)
            ->first();

        $hasVrn = $companyData->vrn ?? null;

        $logoPath = storage_path('app/public/' . $companyData->logo);
        $base64Logo = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $base64Logo = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoData);
        }

        $paymentData = DB::table('sales')
            ->where('id', $saleAutoId)
            ->first();

        // dd($paymentData);

        $receiptSalesOutOfStore = DB::table('profoma_out_store AS OST')
            ->join('invoice AS I', 'OST.invoice_id', '=', 'I.id')
            ->select('OST.*')
            ->where('I.soft_delete', 0)
            ->where('OST.soft_delete', 0)
            ->where('I.id', $invoiceIdFromSales)
            ->get();

        $receiptSalesFromStore = DB::table('products AS PR')
            ->join('invoice_items AS ITM', 'PR.id', '=', 'ITM.item_id')
            ->join('invoice AS I', 'ITM.invoice_id', '=', 'I.id')
            ->select('PR.*', 'ITM.*')
            ->where('I.soft_delete', 0)
            ->where('PR.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->where('I.id', $invoiceIdFromSales)
            ->get();

        $salesReceiptFromServices = DB::table('invoive_service_items AS ISI')
            ->join('service AS SV', 'ISI.service_id', '=', 'SV.id')
            ->join('invoice AS INV', 'ISI.invoice_id', '=', 'INV.id')
            ->select('SV.*', 'ISI.*', 'SV.*')
            ->where('INV.soft_delete', 0)
            ->where('ISI.soft_delete', 0)
            ->where('INV.id', $invoiceIdFromSales)
            ->get();

        $totalAmount = 0;

        foreach ($receiptSalesOutOfStore as $item) {
            $totalAmount += $item->quantity * $item->amountPay;
        }
        foreach ($receiptSalesFromStore as $item) {
            $totalAmount += $item->quantity * $item->amount;
        }

        $pdf = Pdf::loadView('pdf.receipt', compact(
            'paymentData',
            'customerData',
            'receiptSalesOutOfStore',
            'receiptSalesFromStore',
            'totalAmount',
            'saleAutoId',
            'salesReceiptFromServices',
            'companyData',
            'base64Logo',
            'hasVrn'
        ));

        return $pdf->download('sales_receipt_' . str_pad($saleAutoId, 4, '0', STR_PAD_LEFT) . '.pdf');
    }
}
