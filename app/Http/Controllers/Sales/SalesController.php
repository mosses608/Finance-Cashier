<?php

namespace App\Http\Controllers\Sales;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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

        if ($request->ajax()) {
            $request->validate([
                'invoice_id' => 'required|integer|min:0',
            ]);

            $invoiceData = DB::table('invoice AS I')
                ->join('stakeholders AS C', 'I.customer_id', '=', 'C.id')
                ->join('invoice_status AS IST', 'I.status', '=', 'IST.id')
                ->where('I.soft_delete', 0)
                ->where('C.soft_delete', 0)
                ->where('I.id', $request->invoice_id)
                ->where('I.is_profoma', 0)
                ->select([
                    'I.*',
                    'C.name as customer_name',
                    'C.phone',
                    'C.TIN',
                    'IST.name AS statusName',
                    'C.vrn AS vrn',
                    'I.status AS status'
                ])
                ->first();

            $vrn = $invoiceData->vrn;

            if (!$invoiceData) {
                return response()->json(['html' => "<p class='text-danger'>No invoice found.</p>"]);
            }

            $html = view('partials.fetched-invoice', compact('invoiceData'))->render();
            return response()->json(['html' => $html]);
        }

        $currentDaySales = DB::table('sales')
            ->select([
                'invoice_id',
                'amount_paid',
                'payment_method',
                'status',
                'created_at',
                'id AS autoId',
                'updated_at',
            ])
            ->where('soft_delete', 0)
            ->whereDate('created_at', Carbon::today())
            ->orderByDesc('id', 'DESC')
            ->get();

        // dd($currentDaySales);

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

        $salesReports = DB::table('sales')
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

        if ($request->has('from') && $request->has('to') && $request->from != null && $request->to != null) {
            $from = $request->from;
            $to = $request->to;

            $salesReports = DB::table('sales')
                ->select([
                    'invoice_id',
                    'amount_paid',
                    'payment_method',
                    'status',
                    'created_at',
                    'id AS autoId',
                    'updated_at',
                ])
                ->whereBetween('created_at', [$from, $to])
                ->where('company_id', $companyId)
                ->where('soft_delete', 0)
                ->orderByDesc('id', 'DESC')
                ->get();
        }

        return view('sales.sales-reports', compact('salesReports'));
    }

    public function storeSales(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer|min:1',
            'tax' => 'nullable|numeric',
            'amount_paid' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
            'checked' => 'required|integer',
        ]);

        $userId = Auth::user()->id ?? 1;

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

        // dd('Mohammed');

        $companyId = Auth::user()->company_id;

        DB::table('sales')->insert([
            'company_id' => $companyId,
            'user_id' => $userId,
            'invoice_id' => $request->invoice_id,
            'tax' => $request->tax,
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
