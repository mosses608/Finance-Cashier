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
                ->join('customer AS C', 'I.customer_id', '=', 'C.id')
                ->join('invoice_status AS IST', 'I.status', '=', 'IST.id')
                ->where('I.soft_delete', 0)
                ->where('C.soft_delete', 0)
                ->where('I.id', $request->invoice_id)
                ->select('I.*', 'C.name as customer_name', 'C.phone', 'C.TIN', 'IST.name AS statusName')
                ->first();

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
            ])
            ->where('soft_delete', 0)
            ->whereDate('created_at', Carbon::now())
            ->orderByDesc('id')
            ->get();

        // dd($currentDaySales);

        return view('sales.create-sales', compact('invoiceData', 'currentDaySales'));
    }

    public function storeSales(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer|min:1',
            'tax' => 'nullable|numeric',
            'amount_paid' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
        ]);

        $userId = Auth::user()->id ?? 1;

        DB::table('sales')->insert([
            'user_id' => $userId,
            'invoice_id' => $request->invoice_id,
            'tax' => $request->tax,
            'balance' => $request->amount_paid,
            'notes' => $request->notes,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // dd($userId);

        return redirect()->back()->with('success_msg', 'New sale created successfully!');
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

        $customerData = DB::table('customer AS C')
            ->join('invoice AS I', 'C.id', '=', 'I.customer_id')
            ->select('C.*')
            ->where('I.soft_delete', 0)
            ->where('C.soft_delete', 0)
            ->where('I.id', $invoiceIdFromSales)
            ->first();

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

        // dd($receiptSalesFromStore);

        return view('sales.sales-receipt', compact('receiptSalesOutOfStore', 'receiptSalesFromStore', 'saleAutoId', 'paymentData', 'customerData'));
    }

    public function downloadReceipt($encryptedReceiptId)
    {
        $saleAutoId = Crypt::decrypt($encryptedReceiptId);

        $invoiceIdFromSales = DB::table('sales')->where('id', $saleAutoId)->value('invoice_id');

        // Fetch necessary data
        $customerData = DB::table('customer AS C')
            ->join('invoice AS I', 'C.id', '=', 'I.customer_id')
            ->select('C.*')
            ->where('I.soft_delete', 0)
            ->where('C.soft_delete', 0)
            ->where('I.id', $invoiceIdFromSales)
            ->first();

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
            'saleAutoId'
        ));

        return $pdf->download('sales_receipt_' . str_pad($saleAutoId, 4, '0', STR_PAD_LEFT) . '.pdf');
    }
}
