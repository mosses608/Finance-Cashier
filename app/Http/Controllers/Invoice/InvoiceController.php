<?php

namespace App\Http\Controllers\Invoice;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    //
    public function storeInvoice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'required|integer',

            'available_quantity' => 'required|array',
            'available_quantity.*' => 'required',

            'selling_price' => 'required|array',
            'selling_price.*' => 'required',

            'quantity_sell' => 'required|array',
            'quantity_sell.*' => 'required|integer|min:1',

            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric',

            'customer_id' => 'nullable|integer',
            'amount' => 'required',
        ]);
        // dd($request->all());
        $amount = str_replace(',', '', $request->amount);
        $customerId = $request->customer_id;

        // Create new customer if TIN is provided
        if ($request->filled('TIN')) {
            $existingCustomer = DB::table('customer')->where('TIN', $request->TIN)->first();
            if ($existingCustomer) {
                return redirect()->back()->with('error_msg', 'Customer information already exists in our database!');
            }

            $customerId = DB::table('customer')->insertGetId([
                'name' => $request->name,
                'phone' => $request->phone,
                'TIN' => $request->TIN,
                'address' => $request->address,
            ]);
        }

        $companyId = Auth::user()->company_id;

        // Insert main invoice record
        $invoiceId = DB::table('invoice')->insertGetId([
            'customer_id' => $customerId,
            'billId' => null,
            'amount' => $amount,
            'company_id' => $companyId,
        ]);

        foreach ($request->product_id as $index => $productId) {
            $quantitySell = $request->quantity_sell[$index] ?? 0;
            $availableQty = $request->available_quantity[$index] ?? 0;

            $existingStock = DB::table('stocks')->where('storage_item_id', $productId)->first();
            if ($quantitySell > $existingStock->quantity_total) {
                return redirect()->back()->with('error_msg', "Quantity for product ID $productId is greater than available.");
            }

            DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceId,
                'item_id' => $productId,
                'amount' => $amount,
                'quantity' => $quantitySell,
                'discount' => $request->discount[$index] ?? null,
            ]);
        }

        return redirect()->route('invoice.list')->with('success_msg', 'Invoice created successfully!');
    }

    public function invoiceList()
    {
        $companyId = Auth::user()->company_id;

        $invoices = DB::table('invoice AS I')
            ->join('invoice_status AS IST', 'I.status', '=', 'IST.id')
            ->join('stakeholders AS C', 'I.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'I.amount AS amountPaid',
                'IST.name AS invoiceStatus',
                'I.created_at AS invoiceDate',
                'I.id AS invoiceId'
            ])
            ->where('I.company_id', $companyId)
            ->where('I.soft_delete', 0)
            ->where('C.soft_delete', 0)
            ->where('I.status', 1)
            ->where('I.is_profoma', 0)
            ->orderBy('I.updated_at', 'DESC')
            ->get();

        $paidinvoices = DB::table('invoice AS I')
            ->join('invoice_status AS IST', 'I.status', '=', 'IST.id')
            ->join('stakeholders AS C', 'I.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'I.amount AS amountPaid',
                'IST.name AS invoiceStatus',
                'I.created_at AS invoiceDate',
                'I.id AS invoiceId'
            ])
            ->where('I.company_id', $companyId)
            ->where('I.soft_delete', 0)
            ->where('C.soft_delete', 0)
            ->where('I.status', 3)
            ->where('I.is_profoma', 0)
            ->orderBy('I.id', 'DESC')
            ->get();

        $cancelledinvoices = DB::table('invoice AS I')
            ->join('invoice_status AS IST', 'I.status', '=', 'IST.id')
            ->join('stakeholders AS C', 'I.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'I.amount AS amountPaid',
                'IST.name AS invoiceStatus',
                'I.updated_at AS cancelledDate',
                'I.id AS invoiceId'
            ])
            ->where('I.company_id', $companyId)
            ->where('I.soft_delete', 0)
            ->where('C.soft_delete', 0)
            ->where('I.status', 2)
            ->where('I.is_profoma', 0)
            ->orderBy('I.id', 'DESC')
            ->get();

        $totalInvoices = $invoices->count() + $paidinvoices->count() + $cancelledinvoices->count();
        $paidInvoice = $paidinvoices->count();
        $unpaidInvoice = $invoices->count();
        $cancelledInvoice = $cancelledinvoices->count();
        // dd($invoices);

        return view('inc.invoice-list', compact(
            'invoices',
            'paidinvoices',
            'cancelledinvoices',
            'totalInvoices',
            'paidInvoice',
            'unpaidInvoice',
            'cancelledInvoice',
        ));
    }

    public function viewInvoice($encryptedInvoiceId)
    {
        try {
            $invoiceId = Crypt::decrypt($encryptedInvoiceId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        // dd($invoiceId);

        $invoiceItems = DB::table('invoice_items AS ITM')
            ->join('products AS PR', 'ITM.item_id', '=', 'PR.id')
            ->join('invoice AS I', 'ITM.invoice_id', '=', 'I.id')
            ->join('stakeholders AS STH', 'I.customer_id', '=', 'STH.id')
            ->select([
                'PR.name AS itemName',
                'PR.selling_price AS unitPrice',
                'ITM.quantity AS quantity',
                'ITM.amount AS invoiceAmount',
                'ITM.discount AS discount',
                'STH.tin AS tin',
                'STH.vrn AS vrn',
            ])
            ->where('I.id', $invoiceId)
            ->where('I.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->get();

        $invoiceServiceItems = DB::table('invoive_service_items AS ITM')
            ->join('service AS SV', 'ITM.service_id', '=', 'SV.id')
            ->join('invoice AS I', 'ITM.invoice_id', '=', 'I.id')
            ->join('stakeholders AS STH', 'I.customer_id', '=', 'STH.id')
            ->select([
                'SV.name AS itemName',
                'SV.price AS unitPrice',
                'SV.description AS description',
                'ITM.quantity AS quantity',
                'ITM.amount AS invoiceAmount',
                'ITM.discount AS discount',
                'STH.tin AS tin',
                'STH.vrn AS vrn',
            ])
            ->where('SV.active', 1)
            ->where('I.id', $invoiceId)
            ->where('I.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->get();

        $itemsOutOfStore = DB::table('profoma_out_store AS POS')
            ->join('invoice AS INV', 'POS.invoice_id', '=', 'INV.id')
            ->join('stakeholders AS STH', 'INV.customer_id', '=', 'STH.id')
            ->select([
                'POS.product_name AS itemName',
                'POS.amountPay AS unitPrice',
                'POS.quantity AS quantity',
                'POS.amountPay AS invoiceAmount',
                'POS.discount AS discount',
                'STH.tin AS tin',
                'STH.vrn AS vrn',
            ])
            ->where('INV.id', $invoiceId)
            ->where('INV.soft_delete', 0)
            ->get();

        // dd($itemsOutOfStore);

        $transaction = DB::table('sales')
            ->select([
                'amount_paid',
                'payment_method',
                'notes',
                'tax',
                'created_at',
                'status'
            ])
            ->where('invoice_id', $invoiceId)
            ->first();

        $companyData = DB::table('companies')
            ->where('id', Auth::user()->company_id)
            ->first();

        $hasVrn = $companyData->vrn ?? null;

        return view('inc.view-invoice', compact([
            'invoiceId',
            'invoiceItems',
            'invoiceServiceItems',
            'itemsOutOfStore',
            'transaction',
            'hasVrn'
        ]));
    }

    public function cancelInvoice(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer',
        ]);

        $invoiceId = $request->invoice_id;
        // dd($invoiceId);

        $invoiceExists = DB::table('invoice')->where('id', $invoiceId)->first();

        if ($invoiceExists) {
            DB::table('invoice')->where('id', $invoiceId)->update([
                'status' => 2,
            ]);
        }

        return redirect()->route('invoice.list')->with('success_msg', 'Invoice with ID' . ' ' . $invoiceId . ' ' . 'cancelled successfully!');

        // dd($invoiceId);
    }

    public function createProfomaInvoice(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|integer',
                'invoice_date' => 'required|date',

                'product_id' => 'required|array',
                'product_id.*' => 'required|integer|exists:products,id',

                'available_quantity' => 'required|array',
                'available_quantity.*' => 'required|numeric|min:0',

                'quantity_sell' => 'required|array',
                'quantity_sell.*' => 'required|integer|min:1',

                'selling_price' => 'required|array',
                'selling_price.*' => 'required|numeric|min:0',

                'discount' => 'nullable|array',
                'discount.*' => 'nullable|numeric|min:0',

                'category_id' => 'nullable|integer',
                'profoma_status' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->with('error_msg', $e->getMessage());
        }

        $customerId = $request->customer_id;

        DB::beginTransaction();

        try {
            $companyId = Auth::user()->company_id;

            $invoiceId = DB::table('invoice')->insertGetId([
                'customer_id' => $customerId,
                'billId' => null,
                'amount' => 0,
                'is_profoma' => 1,
                'company_id' => $companyId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $grandTotal = 0;
            $lastItemId = null;

            foreach ($request->product_id as $index => $productId) {
                $quantitySell = $request->quantity_sell[$index] ?? 0;
                $availableQty = $request->available_quantity[$index] ?? 0;
                $unitPrice = $request->selling_price[$index] ?? 0;
                $discount = $request->discount[$index] ?? 0;

                $existingStock = DB::table('stocks')->where('storage_item_id', $productId)->first();
                if (!$existingStock || $quantitySell > $existingStock->quantity_total) {
                    DB::rollBack();
                    return back()->with('error_msg', "Quantity for product ID $productId is greater than available.");
                }

                $lineTotal = ($unitPrice * $quantitySell) * (1 - $discount / 100);
                $grandTotal += $lineTotal;

                $lastItemId = DB::table('invoice_items')->insertGetId([
                    'invoice_id' => $invoiceId,
                    'item_id' => $productId,
                    'amount' => $lineTotal,
                    'quantity' => $quantitySell,
                    'discount' => $discount,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            DB::table('invoice')->where('id', $invoiceId)->update([
                'amount' => $grandTotal,
            ]);

            DB::table('profoma_invoice')->insert([
                'invoice_id' => $invoiceId,
                'category_id' => $request->category_id ?? 1,
                'invoice_item_id' => $lastItemId,
                'profoma_status' => $request->profoma_status ?? 1,
                'customer_id' => $customerId,
                'amount' => $grandTotal,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();
            return redirect()->route('profoma.invoice')->with('success_msg', 'Profoma Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error_msg', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    public function profomaOutStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_name' => 'required|array',
                'product_name.*' => 'required|string|max:255',

                'order_status' => 'required|string',
                // 'order_status.*' => 'required|string|max:255',

                'quantity' => 'required|array',
                'quantity.*' => 'required|integer',

                'amountPay' => 'required|array',
                'amountPay.*' => 'required|numeric',

                'discount' => 'nullable|array',
                'discount.*' => 'nullable|numeric|min:0',

                'customer_id' => 'nullable|integer',

                'TIN' => 'nullable|string',
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',

                'amount' => 'required|numeric|min:0',
            ]);

            // dd($validated);

        } catch (ValidationException $e) {
            dd($e->errors());
        }

        $amount = str_replace(',', '', $request->amount);
        $customerId = $request->customer_id;

        // dd($customerId);

        DB::beginTransaction();

        try {
            if ($request->filled('TIN')) {
                $existingCustomer = DB::table('customer')->where('TIN', $request->TIN)->first();
                if ($existingCustomer) {
                    return back()->with('error_msg', 'Customer information already exists!');
                }

                $customerId = DB::table('customer')->insertGetId([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'TIN' => $request->TIN,
                    'address' => $request->address,
                ]);
            }

            $companyId = Auth::user()->company_id;

            $invoiceId = DB::table('invoice')->insertGetId([
                'customer_id' => $customerId,
                'billId' => null,
                'amount' => $amount,
                'company_id' => $companyId,
                'is_profoma' => 1,
            ]);

            // dd($invoiceId);

            foreach ($request->product_name as $index => $productName) {

                // $itemId = DB::table('invoice_items')->insertGetId([
                //     'invoice_id' => $invoiceId,
                //     'item_id' => $productId,
                //     'amount' => $request->selling_price[$index] * $quantitySell,
                //     'quantity' => $quantitySell,
                //     'discount' => $request->discount[$index] ?? 0,
                // ]);

                DB::table('profoma_out_store')->insert([
                    'invoice_id' => $invoiceId,
                    'product_name' => $request->product_name[$index],
                    'order_status' => $request->order_status,
                    'customer_id' => $customerId,
                    'amountPay' => $request->amountPay[$index],
                    'discount' => $request->discount[$index],
                    'quantity' => $request->quantity[$index],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            // dd('Mohammed is genius!');


            DB::commit();
            return redirect()->route('profoma.invoice')->with('success_msg', 'Profoma Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error_msg', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    public function profomaInvoice()
    {
        $companyId = Auth::user()->company_id;

        $prodomaInvoiceFromStore = DB::table('profoma_invoice AS PI')
            ->join('invoice AS I', 'PI.invoice_id', '=', 'I.id')
            ->join('stakeholders AS C', 'PI.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'PI.amount AS amount',
                'PI.profoma_status AS statusInvoice',
                'PI.created_at AS dateCreated',
                'PI.id AS profomaId',
            ])
            // ->where('PI.category_id', 1)
            ->where('I.company_id', $companyId)
            ->where('PI.soft_delete', 0)
            ->orderByDesc('PI.id')
            ->get();

        // dd($prodomaInvoiceFromStore);

        $profomaInvoiceOutOfStore = DB::table('profoma_out_store AS POS')
            ->join('invoice AS I', 'POS.invoice_id', '=', 'I.id')
            ->join('stakeholders AS C', 'POS.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'I.amount AS amount',
                'POS.order_status AS profomaStatus',
                'POS.created_at AS dateCreated',
                'POS.id AS autoId',
            ])
            ->where('I.company_id', $companyId)
            ->where('I.soft_delete', 0)
            ->where('POS.soft_delete', 0)
            ->where('C.soft_delete', 0)
            ->orderByDesc('POS.id')
            ->get();

        $profomaOutStore = $profomaInvoiceOutOfStore->count();

        // dd($profomaInvoiceOutOfStore);

        $acceptedProfomaInvoice = DB::table('profoma_invoice AS PI')
            ->join('invoice AS I', 'PI.invoice_id', '=', 'I.id')
            ->join('stakeholders AS C', 'PI.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'PI.amount AS amount',
                'PI.profoma_status AS statusInvoice',
                'PI.created_at AS dateCreated',
                'PI.id AS profomaId'
            ])
            // ->where('PI.category_id', 1)
            ->where('I.company_id', $companyId)
            ->where('PI.soft_delete', 0)
            ->where('PI.profoma_status', 'Accepted')
            ->orderByDesc('PI.id')
            ->count();

        $pendingProfomaInvoice = DB::table('profoma_invoice AS PI')
            ->join('invoice AS I', 'PI.invoice_id', '=', 'I.id')
            ->join('stakeholders AS C', 'PI.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'PI.amount AS amount',
                'PI.profoma_status AS statusInvoice',
                'PI.created_at AS dateCreated',
                'PI.id AS profomaId'
            ])
            // ->where('PI.category_id', 1)
            ->where('I.company_id', $companyId)
            ->where('PI.soft_delete', 0)
            ->where('PI.profoma_status', 'Pending')
            ->orderByDesc('PI.id')
            ->count();

        $rejectedProfomaInvoice = DB::table('profoma_invoice AS PI')
            ->join('invoice AS I', 'PI.invoice_id', '=', 'I.id')
            ->join('stakeholders AS C', 'PI.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'PI.amount AS amount',
                'PI.profoma_status AS statusInvoice',
                'PI.created_at AS dateCreated',
                'PI.id AS profomaId'
            ])
            // ->where('PI.category_id', 1)
            ->where('I.company_id', $companyId)
            ->where('PI.soft_delete', 0)
            ->where('PI.profoma_status', 'Rejected')
            ->orderByDesc('PI.id')
            ->count();

        $totalProfomaInvoice = $rejectedProfomaInvoice + $pendingProfomaInvoice + $acceptedProfomaInvoice;
        // dd($prodomaInvoiceFromStore);
        return view('inc.profoma-invoice', compact(
            'prodomaInvoiceFromStore',
            'totalProfomaInvoice',
            'rejectedProfomaInvoice',
            'pendingProfomaInvoice',
            'acceptedProfomaInvoice',
            'profomaInvoiceOutOfStore',
            'profomaOutStore',
        ));
    }

    public function viewProfoma($encryptedInvoiceId)
    {
        try {
            $profomaInvoiceId = Crypt::decrypt($encryptedInvoiceId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $profomaAccepted = DB::table('profoma_invoice AS PI')
            ->join('invoice AS I', 'PI.invoice_id', '=', 'I.id')
            ->where('I.is_profoma', 0)
            ->where('PI.id', $profomaInvoiceId)
            ->first();

        $serviceProfomas = DB::table('invoive_service_items AS ITM')
            ->join('service AS SV', 'ITM.service_id', '=', 'SV.id')
            ->join('profoma_invoice AS PI', 'ITM.invoice_id', '=', 'PI.invoice_id')
            ->join('stakeholders AS STK', 'STK.id', '=', 'PI.customer_id')
            ->select([
                'SV.name AS itemName',
                'SV.price AS unitPrice',
                'ITM.quantity AS quantity',
                'ITM.amount AS invoiceAmount',
                'ITM.discount AS discount',
                'STK.tin AS tin',
                'STK.vrn AS vrn'
            ])
            ->where('PI.id', $profomaInvoiceId)
            ->where('PI.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->get();

        // dd($serviceProfomas);

        $profomaInvoiceItems = DB::table('invoice_items AS ITM')
            ->join('products AS PR', 'ITM.item_id', '=', 'PR.id')
            ->join('profoma_invoice AS PI', 'ITM.invoice_id', '=', 'PI.invoice_id')
            ->join('stakeholders AS STK', 'STK.id', '=', 'PI.customer_id')
            ->select([
                'PR.name AS itemName',
                'PR.selling_price AS unitPrice',
                'ITM.quantity AS quantity',
                'ITM.amount AS invoiceAmount',
                'ITM.discount AS discount',
                'STK.tin AS tin',
                'STK.vrn AS vrn'
            ])
            ->where('PI.id', $profomaInvoiceId)
            ->where('PI.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->get();

        $companyData = DB::table('companies')
            ->where('id', Auth::user()->company_id)
            ->first();

        $hasVrn = $companyData->vrn ?? null;

        return view('inc.view-profoma', compact([
            'profomaAccepted',
            'profomaInvoiceItems',
            'profomaInvoiceId',
            'serviceProfomas',
            'hasVrn'
        ]));
    }

    public function cancelProfoma(Request $request)
    {
        $request->validate([
            'profoma_invoice_id' => 'required|integer',
        ]);

        $profomaId = $request->profoma_invoice_id;
        // dd($profomaId);

        $profomaInvoice = DB::table('profoma_invoice')
            ->where('id', $profomaId)->first();

        DB::table('profoma_invoice')->where('id', $profomaId)
            ->update([
                'soft_delete' => 1,
            ]);

        return redirect()->route('profoma.invoice')->with('success_msg', 'Profoma invoice cancelled successfully!');
    }

    public function downloadProfoma($encryptedPrpfomaId)
    {
        try {
            $profomaInvoiceId = Crypt::decrypt($encryptedPrpfomaId);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Invalid Invoice ID'], 400);
        }

        $profomaInvoiceItems = DB::table('invoice_items AS ITM')
            ->join('products AS PR', 'ITM.item_id', '=', 'PR.id')
            ->join('profoma_invoice AS PI', 'ITM.invoice_id', '=', 'PI.invoice_id')
            ->join('stakeholders AS C', 'PI.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'PI.created_at AS issuedDate',
                'PR.name AS itemName',
                'PR.selling_price AS unitPrice',
                'ITM.quantity AS quantity',
                'ITM.amount AS invoiceAmount',
                'ITM.discount AS discount'
            ])
            ->where('PI.id', $profomaInvoiceId)
            ->where('PI.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->get();

        $customerDetails = DB::table('stakeholders AS C')
            ->join('invoice AS I', 'I.customer_id', '=', 'C.id')
            ->join('profoma_invoice AS PI', 'I.id', '=', 'PI.invoice_id')
            ->select([
                'C.name AS customerName',
                'C.phone AS phoneNumber',
                'C.address AS address',
                'C.tin as TIN',
                'C.vrn as VRN',
            ])
            ->where('PI.id', $profomaInvoiceId)
            ->first();
        // dd($customerDetails);

        // $companyData = DB::table('companies AS C')
        //     ->join('administrators AS A', 'C.id', '=', 'A.company_id')
        //     ->select('C.*')
        //     ->where(function ($query) {
        //         $query->where('A.phone', Auth::user()->username)
        //             ->orWhere('A.email', Auth::user()->username);
        //     })
        //     ->first();

        $companyData = DB::table('companies')
            ->select('*')
            ->where('id', Auth::user()->company_id)
            ->first();

        $hasVrn = $companyData->vrn ?? null;

        $bankInformation = DB::table('banks')
            ->select([
                'bank_name',
                'account_name',
                'account_number',
                'bank_code',
            ])
            ->where('company_id', $companyData->id)
            ->where('soft_delete', 0)
            ->first();

        $logoPath = storage_path('app/public/' . $companyData->logo);
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoData);
        }


        $issuesDate = DB::table('profoma_invoice')
            ->where('id', $profomaInvoiceId)->first();

        // dd($profomaInvoiceItems);

        if ($profomaInvoiceItems->isEmpty()) {
            return response()->json(['error' => 'No invoice items found.'], 404);
        }

        $qrText = "Invoice ID: $profomaInvoiceId\n";
        $totalDiscount = 0;
        $totalAmountWithoutDiscount = 0;

        foreach ($profomaInvoiceItems as $item) {
            $totalDiscount += $item->quantity * $item->discount;
            $totalAmountWithoutDiscount = $item->unitPrice * $item->quantity;
            $qrText .= "Item: {$item->itemName}, Qty: {$item->quantity}, Price: {$item->unitPrice}\n";
        }

        $qrText .= "Total Price: TSH " . number_format($totalAmountWithoutDiscount - $totalDiscount, 2);

        // Use SVG instead of PNG
        $qrSvg = QrCode::format('svg')->size(150)->generate($qrText);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return Pdf::loadView('inc.download-profoma', [
            'profomaInvoiceId' => $profomaInvoiceId,
            'profomaInvoiceItems' => $profomaInvoiceItems,
            'qrImageBase64' => $qrBase64,
            'issuesDate' => $issuesDate,
            'customerDetails' => $customerDetails,
            'logoBase64' => $logoBase64,
            'companyData' => $companyData,
            'bankInformation' => $bankInformation,
            'hasVrn' => $hasVrn,
        ])->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ])->download("Profoma-Invoice-$profomaInvoiceId.pdf");
    }

    public function viewProfomaOutStore($encryptedInvoiceuto)
    {
        try {
            $profomaAutoId = Crypt::decrypt($encryptedInvoiceuto);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $profomaInvoiceItems = DB::table('profoma_out_store AS POS')
            ->select([
                'POS.product_name AS itemName',
                'POS.amountPay as unitPrice',
                'POS.quantity AS quantity',
                'POS.discount AS discount',
            ])
            ->where('POS.id', $profomaAutoId)
            ->get();

        $companyData = DB::table('companies')
            ->where('id', Auth::user()->company_id)
            ->first();

        $hasVrn = $companyData->vrn ?? null;

        // dd($profomaInvoiceItems);

        return view('inc.out-store-profoma', compact([
            'profomaAutoId',
            'profomaInvoiceItems',
            'hasVrn'
        ]));
        // dd($profomaAutoId);
    }

    public function downloadInvoiceProfoma($encryptedPrpfomaAutoId)
    {
        try {
            $invoiceProfomaOutId = Crypt::decrypt($encryptedPrpfomaAutoId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $profomaInvoiceItems = DB::table('profoma_out_store AS POS')
            ->select([
                'POS.product_name AS itemName',
                'POS.amountPay as unitPrice',
                'POS.quantity AS quantity',
                'POS.discount AS discount',
            ])
            ->where('POS.id', $invoiceProfomaOutId)
            ->get();

        $customerDetails = DB::table('stakeholders AS C')
            ->join('invoice AS I', 'I.customer_id', '=', 'C.id')
            ->join('profoma_out_store AS POS', 'I.id', '=', 'POS.invoice_id')
            ->select([
                'C.name AS customerName',
                'C.phone AS phoneNumber',
                'C.address AS address',
                'C.tin as TIN',
                'C.vrn as VRN',
            ])
            ->where('POS.id', $invoiceProfomaOutId)
            ->first();
        // dd($customerDetails);

        $companyData = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.*')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $bankInformation = DB::table('banks')
            ->select([
                'bank_name',
                'account_name',
                'account_number',
                'bank_code',
            ])
            ->where('company_id', $companyData->id)
            ->where('soft_delete', 0)
            ->first();

        $logoPath = storage_path('app/public/' . $companyData->logo);
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoData);
        }


        $issuesDate = DB::table('profoma_out_store')
            ->where('id', $invoiceProfomaOutId)->first();

        // dd($profomaInvoiceItems);

        if ($profomaInvoiceItems->isEmpty()) {
            return response()->json(['error' => 'No invoice items found.'], 404);
        }

        $qrText = "Invoice ID: $invoiceProfomaOutId\n";
        $totalDiscount = 0;
        $totalAmountWithoutDiscount = 0;

        foreach ($profomaInvoiceItems as $item) {
            $totalDiscount += $item->quantity * $item->discount;
            $totalAmountWithoutDiscount = $item->unitPrice * $item->quantity;
            $qrText .= "Item: {$item->itemName}, Qty: {$item->quantity}, Price: {$item->unitPrice}\n";
        }

        $qrText .= "Total Price: TSH " . number_format($totalAmountWithoutDiscount - $totalDiscount, 2);

        // Use SVG instead of PNG
        $qrSvg = QrCode::format('svg')->size(150)->generate($qrText);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return Pdf::loadView('inc.download-profoma-out', [
            'profomaInvoiceId' => $invoiceProfomaOutId,
            'profomaInvoiceItems' => $profomaInvoiceItems,
            'qrImageBase64' => $qrBase64,
            'issuesDate' => $issuesDate,
            'customerDetails' => $customerDetails,
            'companyData' => $companyData,
            'logoBase64' => $logoBase64,
            'bankInformation' => $bankInformation,
        ])->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ])->download("Profoma-Invoice-$invoiceProfomaOutId.pdf");
    }

    public function invoiceDownload($encryptedAutoId)
    {
        try {
            $invoiceAutoId = Crypt::decrypt($encryptedAutoId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        // dd($invoiceAutoId);

        $profomaInvoiceItems = DB::table('invoice_items AS ITM')
            ->join('products AS PR', 'ITM.item_id', '=', 'PR.id')
            ->join('invoice AS I', 'ITM.invoice_id', '=', 'I.id')
            ->select([
                'PR.name AS itemName',
                'PR.selling_price AS unitPrice',
                'ITM.quantity AS quantity',
                'ITM.amount AS invoiceAmount',
                'ITM.discount AS discount',
                'I.id AS invoiceId',
            ])
            ->where('I.id', $invoiceAutoId)
            ->where('I.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->get();

        $invoiceServiceItems = DB::table('invoive_service_items AS ITM')
            ->join('service AS SV', 'ITM.service_id', '=', 'SV.id')
            ->join('invoice AS I', 'ITM.invoice_id', '=', 'I.id')
            ->join('stakeholders AS STH', 'I.customer_id', '=', 'STH.id')
            ->select([
                'SV.name AS itemName',
                'SV.price AS unitPrice',
                'SV.description AS description',
                'ITM.quantity AS quantity',
                'ITM.amount AS invoiceAmount',
                'ITM.discount AS discount',
                'STH.tin AS tin',
                'STH.vrn AS vrn',
            ])
            ->where('SV.active', 1)
            ->where('I.id', $invoiceAutoId)
            ->where('I.soft_delete', 0)
            ->where('ITM.soft_delete', 0)
            ->get();

        $itemsOutOfStore = DB::table('profoma_out_store AS POS')
            ->join('invoice AS INV', 'POS.invoice_id', '=', 'INV.id')
            ->join('stakeholders AS STH', 'INV.customer_id', '=', 'STH.id')
            ->select([
                'POS.product_name AS itemName',
                'POS.amountPay AS unitPrice',
                'POS.quantity AS quantity',
                'POS.amountPay AS invoiceAmount',
                'POS.discount AS discount',
                'STH.tin AS tin',
                'STH.vrn AS vrn',
            ])
            ->where('INV.id', $invoiceAutoId)
            ->where('INV.soft_delete', 0)
            ->get();


        $customerDetails = DB::table('stakeholders AS C')
            ->join('invoice AS I', 'I.customer_id', '=', 'C.id')
            ->select([
                'C.name AS customerName',
                'C.phone AS phoneNumber',
                'C.address AS address',
                'C.tin as TIN',
                'C.vrn as VRN',
            ])
            ->where('I.id', $invoiceAutoId)
            ->first();
        // dd($customerDetails);

        $companyData = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.*')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $logoPath = storage_path('app/public/' . $companyData->logo);
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoData);
        }

        $issuesDate = DB::table('invoice')
            ->where('id', $invoiceAutoId)->first();

        $qrText = "Invoice ID: $invoiceAutoId\n";
        $totalDiscount = 0;
        $totalAmountWithoutDiscount = 0;

        if ($invoiceServiceItems->count() > 0) {
            $profomaInvoiceItems = $invoiceServiceItems->collect();
            foreach ($profomaInvoiceItems as $item) {
                $totalDiscount += $item->quantity * $item->discount;
                $totalAmountWithoutDiscount = $item->unitPrice * $item->quantity;
                $qrText .= "Item: {$item->itemName}, Qty: {$item->quantity}, Price: {$item->unitPrice}\n";
            }
        }

        if ($profomaInvoiceItems->count() > 0) {
            foreach ($profomaInvoiceItems as $item) {
                $totalDiscount += $item->quantity * $item->discount;
                $totalAmountWithoutDiscount = $item->unitPrice * $item->quantity;
                $qrText .= "Item: {$item->itemName}, Qty: {$item->quantity}, Price: {$item->unitPrice}\n";
            }
        }

        if ($itemsOutOfStore->count() > 0) {
            $profomaInvoiceItems = $itemsOutOfStore->collect();
            foreach ($profomaInvoiceItems as $item) {
                $totalDiscount += $item->quantity * $item->discount;
                $totalAmountWithoutDiscount = $item->unitPrice * $item->quantity;
                $qrText .= "Item: {$item->itemName}, Qty: {$item->quantity}, Price: {$item->unitPrice}\n";
            }
        }

        $subTotal = $totalAmountWithoutDiscount - $totalDiscount;

        $totalAmount = $subTotal;

        if ($customerDetails->VRN != null) {
            $vat = $subTotal * 0.18;
            $totalAmount = $subTotal + $vat;
        }

        $qrText .= "Total Price: TSH " . number_format($totalAmount, 2);

        // Use SVG instead of PNG
        $qrSvg = QrCode::format('svg')->size(150)->generate($qrText);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return Pdf::loadView('inc.download-invoice', [
            'profomaInvoiceId' => $invoiceAutoId,
            'profomaInvoiceItems' => $profomaInvoiceItems,
            'qrImageBase64' => $qrBase64,
            'issuesDate' => $issuesDate,
            'customerDetails' => $customerDetails,
            'companyData' => $companyData,
            'logoBase64' => $logoBase64,
            'invoiceServiceItems' => $invoiceServiceItems,
            'itemsOutOfStore' => $itemsOutOfStore,
        ])->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ])->download("Invoice-$invoiceAutoId.pdf");
    }

    public function createInvoice()
    {
        $companyId = Auth::user()->company_id;

        $stockProducts = DB::table('products as PR')
            ->join('stocks AS STK', 'PR.id', '=', 'STK.storage_item_id')
            ->select([
                'PR.id AS productId',
                'PR.name AS productName',
                'STK.quantity_total AS availableQuantity',
                'STK.item_price AS sellingPrice',
            ])
            ->where('PR.company_id', $companyId)
            ->where('PR.soft_delete', 0)
            ->where('STK.soft_delete', 0)
            ->orderBy('PR.name', 'ASC')
            ->get();

        $customers = DB::table('stakeholders')
            ->select('id', 'name')
            ->where('company_id', $companyId)
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();
        return view('inc.create-invoice', compact('stockProducts', 'customers'));
    }

    public function invoiceAdjustments(Request $request)
    {
        $profomaInvoiceId = null;
        $profomaInvoiceItems = collect();
        $invoiceServiceItems = collect();

        if ($request->has('invoice_id') && $request->invoice_id != null) {

            $profomaInvoiceId = $request->invoice_id;

            $profomaInvoiceItems = DB::table('invoice_items AS ITM')
                ->join('products AS PR', 'ITM.item_id', '=', 'PR.id')
                ->join('profoma_invoice AS PI', 'ITM.invoice_id', '=', 'PI.invoice_id')
                ->join('invoice AS I', 'PI.invoice_id', '=', 'I.id')
                ->join('stakeholders AS S', 'I.customer_id', '=', 'S.id')
                ->select([
                    'ITM.id AS itemId',
                    'PR.name AS itemName',
                    'PR.selling_price AS unitPrice',
                    'ITM.quantity AS quantity',
                    'ITM.amount AS invoiceAmount',
                    'ITM.discount AS discount',
                    'PI.id AS invoiceId',
                    'S.vrn AS vrn'
                ])
                ->where('PI.id', $profomaInvoiceId)
                ->where('PI.soft_delete', 0)
                ->where('ITM.soft_delete', 0)
                ->get();

            $invoiceServiceItems = DB::table('invoive_service_items AS ITM')
                ->join('service AS SV', 'ITM.service_id', '=', 'SV.id')
                ->join('profoma_invoice AS PI', 'ITM.invoice_id', '=', 'PI.invoice_id')
                ->join('invoice AS I', 'PI.invoice_id', '=', 'I.id')
                ->join('stakeholders AS S', 'I.customer_id', '=', 'S.id')
                ->select([
                    'ITM.id AS itemId',
                    'SV.name AS itemName',
                    'SV.price AS unitPrice',
                    'SV.description AS description',
                    'ITM.quantity AS quantity',
                    'ITM.amount AS invoiceAmount',
                    'ITM.discount AS discount',
                    'S.vrn AS vrn'
                ])
                ->where('SV.active', 1)
                ->where('PI.id', $profomaInvoiceId)
                ->where('PI.soft_delete', 0)
                ->where('ITM.soft_delete', 0)
                ->get();
        }

        return view('inc.invoice-adjustment', compact([
            'profomaInvoiceId',
            'invoiceServiceItems',
            'profomaInvoiceItems',
        ]));
    }

    public function invoiceAdjustSave(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'invoice_id' => 'required|string',

            'item_id' => 'required|array',
            'item_id.*' => 'required|integer',

            'unit_price' => 'required|array',
            'unit_price.*' => 'required|string',

            'quantity' => 'required|array',
            'quantity.*' => 'required|string',

            'dicount' => 'nullable|array',
            'dicount.*' => 'nullable|string',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->invoice_id);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $totalAmount = 0;
        $subTotalAmount = 0;

        if ($request->has('type') && $request->type == 'item') {
            foreach ($request->item_id as $key => $itm) {
                $invoiceIdFromProfoma = DB::table('profoma_invoice AS PI')
                    ->join('invoice_items AS ITM', 'PI.invoice_id', '=', 'ITM.invoice_id')
                    ->select('PI.invoice_id AS invoiceId')
                    ->where('ITM.id', $itm)
                    ->where('PI.id', $decryptedId)
                    ->first();

                $amount = str_replace(',', '', $request->unit_price[$key]);
                $quantity = $request->quantity[$key];
                $discount = str_replace(',', '', $request->dicount[$key]);
                $totalDiscount = 0;

                $discountPercent = number_format(($discount / $amount) * 100);

                if ($discount > $amount * $quantity) {
                    $discountPercent = 0;
                }

                $subTotalAmount += $amount * $quantity;
                $totalDiscount += $discountPercent;

                if ($invoiceIdFromProfoma) {
                    DB::table('invoice_items')->where('id', $itm)->update([
                        'amount' => $amount,
                        'quantity' => $quantity,
                        'discount' => $discountPercent,
                    ]);
                }
            }

            $invoiceId = $invoiceIdFromProfoma->invoiceId;

            $totalAmount = $subTotalAmount;

            DB::table('profoma_invoice')->where('invoice_id', $invoiceId)->update([
                'amount' => $totalAmount,
            ]);

            DB::table('invoice')->where('id', $invoiceId)->update([
                'amount' => $totalAmount - $totalDiscount,
            ]);

            return redirect()->back()->with('success_msg', 'Invoice with number ' . ' # ' . $invoiceId . ' ' . 'has been adjusted successfully!');
        }
    }
}
