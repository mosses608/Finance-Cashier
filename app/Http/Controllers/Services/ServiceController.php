<?php

namespace App\Http\Controllers\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ServiceController extends Controller
{
    //
    public function servicePage()
    {
        $companyId = Auth::user()->company_id;

        $services = DB::table('service')
            ->select('*')
            ->where('company_id', $companyId)
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $customers = DB::table('stakeholders')
            ->select('name', 'id')
            ->where('company_id', $companyId)
            ->where('stakeholder_category', 1)
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $regions = DB::table('city')->select([
            'name',
            'id',
        ])
            ->orderBy('name', 'ASC')
            ->get();

        // dd($customers);

        return view('services.service-page', compact([
            'services',
            'customers',
            'regions'
        ]));
    }

    public function storeServices(Request $request)
    {
        $request->validate([
            'service_name' => 'required|array',
            'service_name.*' => 'required|string',

            'amount' => 'nullable|array',
            'amount.*' => 'nullable|numeric',

            'category' => 'nullable|array',
            'category.*' => 'nullable|string',

            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable|string',

            'description' => 'nullable|array',
            'description.*' => 'nullable|string'
        ]);

        $companyId = Auth::user()->company_id;

        foreach ($request->service_name as $key => $serviceName) {

            $existingService = DB::table('service')
                ->where('name', $request->service_name[$key])
                ->where('company_id', $companyId)
                ->exists();

            if ($existingService == true) {
                return redirect()->back()->with('error_msg', 'Service already exists!');
            }

            DB::table('service')->insert([
                'name' => $request->service_name[$key],
                'description' => $request->description[$key],
                'price' => $request->amount[$key] ?? null,
                'category' => $request->category[$key],
                'quantity' => $request->quantity[$key] ?? null,
                'company_id' => $companyId,
                'created_by' => Auth::user()->id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success_msg', 'Service added successfully!');
    }

    public function serviceProfomaInvoice(Request $request)
    {
        $request->validate([
            'service_id' => 'required|array',
            'service_id.*' => 'required|integer',

            'invoice_date' => 'required|date',

            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric',

            'price' => 'required|array',
            'price.*' => 'required|numeric',

            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable|integer|min:1',

            'customer_id' => 'nullable|integer',
        ]);

        DB::beginTransaction();

        try {
            $companyId = Auth::user()->company_id;

            $invoiceId = DB::table('invoice')->insertGetId([
                'customer_id' => $request->customer_id,
                'amount' => 0,
                'created_at' => $request->invoice_date,
                'updated_at' => Carbon::now(),
                'company_id' => $companyId,
                'is_profoma' => 1,
            ]);

            $grandTotal = 0;

            foreach ($request->service_id as $key => $serviceId) {
                $unitPrice = $request->price[$key] ?? 0;
                $qty = $request->quantity[$key] ?? 1;
                $discount = $request->discount[$key] ?? 0;

                $lineAmount = ($unitPrice * $qty) * (1 - ($discount / 100));

                $grandTotal += $lineAmount;

                $invoiceItemId = DB::table('invoive_service_items')->insertGetId([
                    'invoice_id' => $invoiceId,
                    'service_id' => $serviceId,
                    'amount' => $lineAmount,
                    'quantity' => $qty,
                    'discount' => $discount,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::table('profoma_invoice')->insert([
                    'invoice_id' => $invoiceId,
                    'category_id' => 2,
                    'invoice_item_id' => $invoiceItemId,
                    'customer_id' => $request->customer_id,
                    'amount' => $lineAmount,
                    'profoma_status' => 'pending',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            DB::table('invoice')->where('id', $invoiceId)->update([
                'amount' => $grandTotal,
            ]);

            DB::commit();
            return redirect()->back()->with('success_msg', 'Service Proforma Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error_msg', 'Failed to create service proforma: ' . $e->getMessage());
        }
    }

    public function acceptProfoma()
    {
        $companyId = Auth::user()->company_id;
        $profomaInvoices = DB::table('profoma_invoice AS PI')
            ->join('invoice AS INV', 'PI.invoice_id', '=', 'INV.id')
            ->join('stakeholders AS STK', 'PI.customer_id', '=', 'STK.id')
            ->select([
                'STK.name AS name',
                'PI.id AS invoiceProfomaId',
                'PI.amount AS amount',
                'PI.profoma_status AS status',
                'PI.created_at AS dateDue',
                'PI.invoice_id AS invoiceId',
            ])
            ->where('INV.company_id', $companyId)
            ->where('INV.is_profoma', 1)
            ->where('PI.soft_delete', 0)
            ->where('STK.soft_delete', 0)
            ->orderBy('PI.created_at', 'DESC')
            ->get();

        $profomaInvoicesOutStore = DB::table('profoma_out_store AS PI')
            ->join('invoice AS INV', 'PI.invoice_id', '=', 'INV.id')
            ->join('stakeholders AS STK', 'PI.customer_id', '=', 'STK.id')
            ->select([
                'STK.name AS name',
                'PI.id AS invoiceProfomaId',
                'PI.amountPay AS amount',
                'PI.order_status AS status',
                'PI.created_at AS dateDue',
                'PI.invoice_id AS invoiceId',
            ])
            ->where('INV.company_id', $companyId)
            ->where('INV.is_profoma', 1)
            ->where('PI.soft_delete', 0)
            ->where('STK.soft_delete', 0)
            ->orderBy('PI.created_at', 'DESC')
            ->get();

        // dd($profomaInvoices);

        return view('inc.accept-profoma', compact([
            'profomaInvoices',
            'profomaInvoicesOutStore',
        ]));
    }

    public function approveProfomaInvoice(Request $request)
    {
        $request->validate([
            'invoiceId' => 'required|string',
            'profomaId' => 'required|string',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->invoiceId);
            $decryptedProfomaId = Crypt::decrypt($request->profomaId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $invoiceExists = DB::table('invoice')
            ->where('id', $decryptedId)
            ->where('is_profoma', 1)
            ->exists();

        $profomaExists = DB::table('profoma_invoice')
            ->where('id', $decryptedProfomaId)
            ->where('soft_delete', 0)
            ->exists();

        if ($invoiceExists === false && $profomaExists === false) {
            return redirect()->back()->with('error_msg', 'Invoice does not exist or might already been accepted!');
        }

        DB::table('invoice')->where('id', $decryptedId)->update([
            'is_profoma' => 0,
            'updated_at' => Carbon::now(),
        ]);

        DB::table('profoma_invoice')->where('id', $decryptedProfomaId)->update([
            'profoma_status' => 'Accepted',
        ]);

        // PURCHASE ORDER
        $purchaseOderId = DB::table('purchases_orders')->insertGetId([
            'invoice_id' => $decryptedId,
        ]);

        return redirect()->route('create.new.sales')->with('success_msg', 'Invoice accepted, Your purchase order is . ' . ' ' . $purchaseOderId);
    }

    public function acceptProfomaOutStore(Request $request)
    {
        $request->validate([
            'invoiceId' => 'required|string',
            'profomaId' => 'required|string',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->invoiceId);
            $decryptedProfomaId = Crypt::decrypt($request->profomaId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $invoiceExists = DB::table('invoice')
            ->where('id', $decryptedId)
            ->where('is_profoma', 1)
            ->exists();

        $profomaExists = DB::table('profoma_out_store')
            ->where('id', $decryptedProfomaId)
            ->where('soft_delete', 0)
            ->exists();

        if ($invoiceExists === false && $profomaExists === false) {
            return redirect()->back()->with('error_msg', 'Invoice does not exist or might already been accepted!');
        }

        DB::table('invoice')->where('id', $decryptedId)->update([
            'is_profoma' => 0,
            'updated_at' => Carbon::now(),
        ]);

        DB::table('profoma_out_store')->where('id', $decryptedProfomaId)->update([
            'order_status' => 'Accepted',
        ]);

        return redirect('/invoice-list')->with('success_msg', 'Invoice has been approved successfully!');
    }
}
