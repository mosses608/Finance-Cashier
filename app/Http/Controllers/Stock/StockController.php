<?php

namespace App\Http\Controllers\Stock;

use Carbon\Carbon;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockController extends Controller
{
    //
    public function stock(Request $request)
    {
        $stockDetails = $request->validate([
            'storage_item_id' => 'required|integer',
            'quantity_in' => 'required|integer',
            'item_price' => 'required|integer',
            'remarks' => 'nullable|string|max:255',
        ]);

        $existsingProductName = Stock::where('storage_item_id', $request->input('storage_item_id'))->first();

        if ($existsingProductName) {
            return redirect()->back('error_msg', 'Stock details exists!');
        }

        Stock::create($stockDetails);
        return redirect()->back()->with('success_msg', 'Product imported successfully!');
    }

    public function stockInMethod()
    {
        $companyId = Auth::user()->company_id;

        $products = DB::table('products AS PR')
            ->join('stores AS ST', 'PR.store_id', '=', 'ST.id')
            // ->join('stock_out_transaction AS STK', 'PR.id', '=', 'STK.product_id')
            ->select([
                'PR.serial_no  AS serialNumber',
                'PR.name AS productName',
                'PR.sku AS sku',
                'PR.cost_price AS cost_price',
                'PR.selling_price AS selling_price',
                'ST.store_name AS storeName',
                'PR.id AS id',
                // DB::raw('SUM(STK.stockout_quantity) AS stockQuantity')
            ])
            ->where('PR.company_id', $companyId)
            ->where('PR.soft_delete', 0)
            ->where('ST.soft_delete', 0)
            // ->groupBy('PR.id', 'PR.name', 'PR.sku', 'PR.cost_price', 'PR.selling_price', 'ST.store_name', 'ST.id')
            ->get();

        $stocks = DB::table('stocks')->select('storage_item_id', 'quantity_total')->where('soft_delete', 0)->get();

        $stockOutTransactions = DB::table('stock_out_transaction')
            ->select('stockout_quantity', 'product_id')
            ->where('status', 1)
            ->where('soft_delete', 0)
            ->get();

        // dd($stockOutTransactions);

        return view('inc.stock-in', compact('products', 'stocks', 'stockOutTransactions'));
    }

    public function stockInQuantity(Request $request)
    {
        $request->validate([
            'auto_id' => 'required',
            'quantity' => 'required',
            'seling_price' => 'required',
            // 'available_quantity' => 'nullable',
        ]);

        $availableQuantity = DB::table('stocks')
            ->select([
                'quantity_total'
            ])
            ->where('storage_item_id', $request->auto_id)->value('quantity_total');

        $stockFinder = DB::table('stocks')
            ->where('storage_item_id', $request->auto_id)->first();

        if ($stockFinder) {

            DB::table('stocks')->where('storage_item_id', $request->auto_id)->update(
                [
                    'quantity_in' => $request->quantity + $availableQuantity,
                    'quantity_out' => $stockFinder->quantity_out,
                    'quantity_total' => $request->quantity + $availableQuantity - $stockFinder->quantity_out,
                ]
            );
        }

        if (!$stockFinder) {
            DB::table('stocks')->where('storage_item_id', $request->auto_id)->insert(
                [
                    'storage_item_id' => $request->auto_id,
                    'item_price' => $request->seling_price,
                    'quantity_in' => $request->quantity,
                    'quantity_total' => $request->quantity,
                ]
            );
            // DB::table('stocks')->where('storage_item_id', $request->auto_id)->update(['quantity_in' => $request->quantity]);
        }

        return redirect()->back()->with('success_msg', 'Data saved successfully!');
    }

    public function stockOut()
    {
        $companyId = Auth::user()->company_id;

        $stockProducts = DB::table('products as PR')
            ->join('stocks AS STK', 'PR.id', '=', 'STK.storage_item_id')
            ->select([
                'PR.id AS productId',
                'PR.name AS productName',
                'STK.quantity_total AS availableQuantity',
                'STK.item_price AS sellingPrice',
                'PR.serial_no AS serialNo'
            ])
            ->where('PR.company_id', $companyId)
            ->where('PR.soft_delete', 0)
            ->where('STK.soft_delete', 0)
            ->orderBy('PR.name', 'ASC')
            ->get();

        $customers = DB::table('stakeholders')
            ->select('id', 'name')
            ->where('soft_delete', 0)
            ->where('company_id', $companyId)
            ->orderBy('name', 'ASC')
            ->get();

        $todayStockOuts = DB::table('stock_out_transaction AS SOUT')
            ->join('products AS PR', 'SOUT.product_id', '=', 'PR.id')
            ->leftJoin('emplyees AS E', 'SOUT.user_id', '=', 'E.id')
            ->leftJoin('administrators AS A', 'SOUT.user_id', '=', 'A.id')
            ->select([
                'PR.name AS productName',
                'SOUT.stockout_quantity AS quantityOut',
                DB::raw("COALESCE(E.first_name, A.names) AS userName"),
                'SOUT.created_at AS dueDate',
                'SOUT.id AS autoId',
                'PR.serial_no AS serialNo',
                'SOUT.status AS status',
            ])
            ->where('PR.company_id', $companyId)
            ->where('SOUT.soft_delete', 0)
            ->where('PR.soft_delete', 0)
            ->whereDate('SOUT.created_at', Carbon::today())
            ->orderBy('SOUT.id', 'DESC')
            ->get();

        $stockOutExistsIds = DB::table('stock_out_transaction')->select('id')
            ->whereIn('status', [1, 2])
            ->pluck('id')
            ->toArray();

        // dd($todayStockOuts);

        return view('inc.stock-out', compact([
            'stockProducts',
            'customers',
            'todayStockOuts',
            'stockOutExistsIds'
        ]));
    }

    public function approveRejectTransactions(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|array',
            'transaction_id.*' => 'required|string',

            'approve_comment' => 'nullable|string',
            'action' => 'required|string',
            'reject_comment' => 'nullable|string',
        ]);

        $transactionIds = [];
        $decodedIds = [];

        foreach ($request->transaction_id as $tranx) {
            $transactionIds[] = Crypt::decrypt($tranx);
        }

        foreach ($transactionIds as $id) {
            $decodedIds[] = json_decode($id);
        }

        if ($request->has('action') && $request->action == 'accept') {
            foreach ($decodedIds as $id) {
                DB::table('stock_out_transaction')->where('id', $id->tranxt)->update([
                    'status' => 1,
                    'comments' => $request->approve_comment,
                ]);
            }

            return redirect()->back()->with('success_msg', 'Transaction accepted successfully!');
        }

        if ($request->has('action') && $request->action == 'reject') {
            foreach ($decodedIds as $id) {
                DB::table('stock_out_transaction')->where('id', $id->tranxt)->update([
                    'status' => 2,
                    'comments' => $request->reject_comment,
                ]);
            }

            return redirect()->back()->with('success_msg', 'Transaction rejected successfully!');
        }
    }

    public function stockOutProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'required|integer',

            'stockout_quantity' => 'required|array',
            'stockout_quantity.*' => 'required|integer',
        ]);

        $userId = Auth::user()->id;

        foreach ($request->product_id as $key => $productId) {
            DB::table('stock_out_transaction')->insert([
                'user_id' => $userId,
                'product_id' => $request->product_id[$key],
                'stockout_quantity' => $request->stockout_quantity[$key],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return redirect()->back()->with('success_msg', 'Products stock out successfully done!');
        // dd($request->all());
    }

    public function stockOutReceipt($encryptedId)
    {
        try {
            $stockId = Crypt::decrypt($encryptedId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $companyId = Auth::user()->company_id;

        $stockOuts = DB::table('stock_out_transaction AS SOUT')
            ->join('products AS PR', 'SOUT.product_id', '=', 'PR.id')
            ->join('stocks AS SK', 'PR.id', '=', 'SK.storage_item_id')
            ->leftJoin('emplyees AS E', 'SOUT.user_id', '=', 'E.id')
            ->leftJoin('administrators AS A', 'SOUT.user_id', '=', 'A.id')
            ->select([
                'PR.name AS productName',
                'SOUT.stockout_quantity AS quantityOut',
                DB::raw("COALESCE(E.first_name, A.names) AS userName"),
                'SK.item_price AS price',
                'SOUT.created_at AS dueDate',
                'SOUT.status AS status'
            ])
            ->where('SOUT.id', $stockId)
            ->where('SOUT.soft_delete', 0)
            ->where('PR.soft_delete', 0)
            // ->where('U.soft_delete', 0)
            ->get();


        return view('inc.stock-out-receipt', compact('stockOuts'));
    }

    public function downloadStockImportFile(Request $request)
    {
        $request->validate([
            'storage_item_id' => 'required|array',
            'storage_item_id.*' => 'required|integer',
        ]);

        $productIds = $request->storage_item_id;

        $productData = DB::table('products')
            ->select('id', 'name', 'sku', 'cost_price', 'selling_price', 'serial_no')
            ->whereIn('id', $productIds)
            ->where('soft_delete', 0)
            ->get();

        $companyName = DB::table('companies')
            ->select('company_name')
            ->where('id', Auth::user()->company_id)
            ->value('company_name');

        $response = new StreamedResponse(function () use ($productData, $companyName) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Company Name:', $companyName ?? '']);

            fputcsv($handle, []);

            fputcsv($handle, ['Storage Id', 'Serial No', 'SKU', 'Product Name', 'Cost Price', 'Selling Price', 'Stock-in Quantity']);

            foreach ($productData as $index => $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->serial_no,
                    $row->sku,
                    $row->name,
                    number_format($row->cost_price, 2),
                    number_format($row->selling_price, 2),
                    '',
                ]);
            }

            fclose($handle);
        });

        $filename = $companyName . ' ' . ' - ' . ' ' . Carbon::now()->format('Y_m_d_His') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename={$filename}");

        return $response;
    }

    public function uploadCSVFile(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file',
        ]);

        $csvData = [];

        if (($handle = fopen($request->file('file_upload')->getRealPath(), 'r')) !== false) {
            fgetcsv($handle);
            fgetcsv($handle);
            fgetcsv($handle);

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($row) < 7) {
                    continue;
                }

                $csvData[] = [
                    'storageId'  => $row[0],
                    'itemPrice'  => round((float)str_replace(',', '', $row[5]), 2),
                    'quantityIn' => (int)$row[6],
                ];
            }

            fclose($handle);
        }

        $inserted = false;
        $updated = false;

        foreach ($csvData as $entry) {
            if (!empty($entry['quantityIn']) && is_numeric($entry['quantityIn']) && $entry['quantityIn'] >= 0) {
                $stock = DB::table('stocks')
                    ->where('storage_item_id', $entry['storageId'])
                    ->where('soft_delete', 0)
                    ->first();

                if ($stock) {
                    DB::table('stocks')
                        ->where('storage_item_id', $entry['storageId'])
                        ->update([
                            'quantity_in' => $stock->quantity_in + $entry['quantityIn'],
                            'quantity_total' => $stock->quantity_total + $entry['quantityIn'],
                            'updated_at' => now(),
                        ]);
                    $updated = true;
                } else {
                    DB::table('stocks')->insert([
                        'storage_item_id' => $entry['storageId'],
                        'quantity_in' => $entry['quantityIn'],
                        'quantity_total' => $entry['quantityIn'],
                        'item_price' => $entry['itemPrice'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $inserted = true;
                }
            }
        }

        if ($inserted) {
            return redirect()->back()->with('success_msg', 'Product stocks saved successfully!');
        } elseif ($updated) {
            return redirect()->back()->with('success_msg', 'Stock quantities updated successfully!');
        } else {
            return redirect()->back()->with('error_msg', 'No valid data found in CSV.');
        }
    }
}
