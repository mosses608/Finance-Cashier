<?php

namespace App\Http\Controllers\Storage;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Department;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageController extends Controller
{
    //
    public function storeManage()
    {
        $companyId = Auth::user()->company_id;
        $roles = Role::all();
        $departments = Department::all();
        $stores = DB::table('stores')
            ->select('*')
            ->where('company_id', $companyId)
            ->orderBy('store_name', 'ASC')
            ->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $products = Product::orderBy('id', 'asc')->get();
        $stocks = Stock::all();
        $transactions = Transaction::all();
        return view('inc.store', compact([
            'roles',
            'departments',
            'stores',
            'categories',
            'products',
            'stocks',
            'transactions'
        ]));
    }

    public function register(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'store_keeper' => 'nullable|string',
            'phone' => 'nullable',
        ]);

        $companyId = Auth::user()->company_id;

        $existingStore = DB::table('stores')
            ->where('company_id', $companyId)
            ->where('store_name', $request->store_name)
            ->first();

        $phoneNoExists = DB::table('stores')
            ->where('phone', $request->phone)
            ->exists();

        if ($existingStore || $phoneNoExists === true) {
            return redirect()->back()->with('error_msg', 'Seems like some of these informations already exists in our database store!');
        }

        Store::create([
            'store_name' => $request->store_name,
            'city' => $request->city,
            'location' => $request->location,
            'store_keeper' => $request->store_keeper,
            'phone' => $request->phone,
            'company_id' => $companyId,
        ]);

        return redirect()->route('store.list')->with('success_msg', 'Store registered successfully!');
    }

    public function storePage()
    {
        $cities = DB::table('city')
            ->select('*')
            ->where('soft_delete', 0)
            ->get();
        // dd($cities);
        return view('inc.add-store', compact('cities'));
    }

    public function storeLists()
    {
        $companyId = Auth::user()->company_id;

        $stores = DB::table('stores AS ST')
            ->join('products AS PR', 'ST.id', '=', 'PR.store_id')
            ->select([
                'ST.id AS autoId',
                'ST.store_name AS store_name',
                'ST.city AS city',
                'ST.location AS location',
                'ST.store_keeper AS store_keeper',
                'ST.phone AS phone',
                DB::raw('COUNT(PR.id) AS totalItems'),
            ])
            ->where('ST.company_id', $companyId)
            ->where('ST.soft_delete', 0)
            ->groupBy(
                'ST.store_name',
                'ST.city',
                'ST.location',
                'ST.store_keeper',
                'ST.phone',
                'ST.id',
            )
            ->orderBy('ST.store_name', 'ASC')
            ->get();

        // dd($stores);

        return view('inc.store-list', compact(['stores']));
    }

    public function viewStore($encryptedStoreId)
    {
        try {
            $storeId = Crypt::decrypt($encryptedStoreId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $companyId = Auth::user()->company_id;

        $storageData = DB::table('products AS PR')
            ->select([
                'PR.name AS productName',
                'PR.sku AS sku',
                'PR.description AS description',
                'PR.cost_price AS costPrice',
                'PR.selling_price AS sellingPrice',
                'PR.id AS productAutoId',
            ])
            ->where('PR.store_id', $storeId)
            ->where('PR.soft_delete', 0)
            ->orderBy('PR.name', 'ASC')
            ->get();

        $productStocks = DB::table('stocks')
            ->select([
                'storage_item_id',
                'quantity_total',
            ])
            ->where('soft_delete', 0)
            ->get();

        $itemsCounter = $storageData->count();

        $storeName = DB::table('stores')->where('id', $storeId)->value('store_name');

        $storeData = DB::table('stores')
            ->select('store_name', 'id')
            ->whereNot('id', $storeId)
            ->where('soft_delete', 0)
            ->where('company_id', $companyId)
            ->get();

        return view('inc.view-store', compact([
            'storageData',
            'storeName',
            'itemsCounter',
            'productStocks',
            'storeData',
            'storeId',
        ]));
    }

    public function storeChangeLogs(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'selling_price' => 'required|string',
            'store_id' => 'nullable|string',
        ]);

        $storeId = Crypt::decrypt($request->store_id);
        $decodedStoreId = json_decode($storeId, true);

        $productId = Crypt::decrypt($request->product_id);

        $decodedProductId = json_decode($productId, true);
        $createdBy = Auth::user()->user_id;
        $changePrice = str_replace(',', '', $request->selling_price);
        $companyId = Auth::user()->company_id;

        $inserted = false;
        DB::table('storage_change_logs')->insert([
            'product_id' => $decodedProductId,
            'created_by' => $createdBy,
            'date_created' => Carbon::now(),
            'change_price' => $changePrice,
            'change_store_id' => $decodedStoreId,
            'company_id' => $companyId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        $inserted = true;

        if ($inserted) {
            return redirect()->back()->with('success_msg', 'Request to update product sent successfully!');
        }

        return redirect()->back()->with('error_msg', 'Failed to send product update request!');
    }

    public function stockChange()
    {
        $companyId = Auth::user()->company_id;
        $stockChangeLogs = DB::table('storage_change_logs AS SCL')
            ->join('products AS P', 'SCL.product_id', '=', 'P.id')
            ->join('stores AS S', 'SCL.change_store_id', '=', 'S.id')
            ->select([
                'P.name AS productName',
                'S.store_name AS storeName',
                'SCL.change_price AS changePrice',
                'SCL.status AS status',
                'SCL.date_created AS dateCreated',
                'SCL.id AS logId'
            ])
            ->where('SCL.soft_delete', 0)
            ->where('SCL.company_id', $companyId)
            ->orderBy('SCL.id', 'DESC')
            ->get();

        $approvedOrRejectedStockChangeIds = DB::table('storage_change_logs')
            ->select('id')
            ->whereIn('status', ['approved', 'rejected'])
            ->where('company_id', $companyId)
            ->where('soft_delete', 0)
            ->pluck('id')
            ->toArray();

        return view('inc.stock-change-logs', compact([
            'stockChangeLogs',
            'approvedOrRejectedStockChangeIds',
        ]));
    }

    public function stockOutReport(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $reports = DB::table('stock_out_transaction AS SOUT')
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
            ->orderBy('SOUT.id', 'DESC')
            ->get();

        $fromDate = null;
        $toDate = null;

        if ($request->has('date_from') && $request->has('date_to') && $request->date_from != null && $request->date_to != null) {
            $fromDate = $request->date_from;
            $toDate = $request->date_to;

            $reports = DB::table('stock_out_transaction AS SOUT')
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
                ->whereBetween('SOUT.created_at', [$fromDate, $toDate])
                ->where('PR.company_id', $companyId)
                ->where('SOUT.soft_delete', 0)
                ->where('PR.soft_delete', 0)
                ->orderBy('SOUT.id', 'DESC')
                ->get();
        }

        return view('inc.stock-iut-report', compact([
            'reports',
            'fromDate',
            'toDate'
        ]));
    }

    public function downloadStockOutReport($validData)
    {
        $decryptedData = Crypt::decrypt($validData);
        $decodedData = json_decode($decryptedData, true);

        $fromDate = $decodedData['from'];
        $toDate = $decodedData['to'];
        $companyId = Auth::user()->company_id;
        $companyName = DB::table('companies')->select('company_name')->where('id', $companyId)->value('company_name');

        $reports = DB::table('stock_out_transaction AS SOUT')
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
            ->whereBetween('SOUT.created_at', [$fromDate, $toDate])
            ->where('PR.company_id', $companyId)
            ->where('SOUT.soft_delete', 0)
            ->where('PR.soft_delete', 0)
            ->orderBy('SOUT.id', 'DESC')
            ->get();

        $response = new StreamedResponse(function () use ($reports, $companyName, $fromDate, $toDate) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Company Name:', $companyName ?? '']);

            fputcsv($handle, ['Stock Out Report:', Carbon::parse($fromDate)->format('M d, Y') . ' - ' . Carbon::parse($toDate)->format('M d, Y') ?? '']);

            fputcsv($handle, []);

            fputcsv($handle, ['Serial No', 'Item Name', 'Quantity', 'Staff Name', 'Due Date', 'Status']);

            foreach ($reports as $row) {
                fputcsv($handle, [
                    $row->serialNo ?? '####',
                    $row->productName,
                    number_format($row->quantityOut),
                    $row->userName,
                    Carbon::parse($row->dueDate)->format('M d, Y'),
                    $row->status ?? 'pending',
                ]);
            }

            fclose($handle);
        });

        $filename = $companyName . ' ' . ' - ' . ' ' . Carbon::now()->format('Y_m_d_His') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename={$filename}");

        return $response;
    }

    public function approveRejectStockChange(Request $request)
    {
        $request->validate([
            'log_id' => 'required|array',
            'log_id.*' => 'required|string',

            'approve_comment' => 'nullable|string',
            'action' => 'required|string',
            'reject_comment' => 'nullable|string',
        ]);

        $logIds = [];
        $decodedIds = [];
        foreach ($request->log_id as $logId) {
            $logIds[] = Crypt::decrypt($logId);
        }

        foreach ($logIds as $id) {
            $decodedIds[] = json_decode($id, true);
        }

        if ($request->has('action') && $request->action == 'reject') {
            foreach ($decodedIds as $log) {
                DB::table('storage_change_logs')->where('id', $log['logId'])->update([
                    'status' => 'rejected',
                    'comments' => $request->reject_comment,
                ]);
            }

            return redirect()->back()->with('success_msg', 'Requests rejected sucessfully!');
        }

        if ($request->has('action') && $request->action == 'accept') {
            foreach ($decodedIds as $log) {
                $product = DB::table('storage_change_logs')->where('id', $log['logId'])->first();
                $existingProductData =  DB::table('products')->where('id', $product->product_id)->first();
                DB::table('products')->where('id', $product->product_id)->update([
                    'selling_price' => $product->change_price ?? $existingProductData->selling_price,
                    'store_id' => $log->change_store_id ?? $existingProductData->store_id,
                ]);

                DB::table('storage_change_logs')->where('id', $log['logId'])->update([
                    'status' => 'approved',
                    'comments' => $request->approve_comment,
                ]);
            }

            return redirect()->back()->with('success_msg', 'Requests approved sucessfully!');
        }

        return redirect()->back()->with('error_msg', 'Failed to approve or reject stock change requests!');
    }
}
