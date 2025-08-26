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
            $existingService = DB::table('service')->where('name', $request->service_name[$key])->exists();

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

            'category_id' => 'required|integer',

            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric',

            'price' => 'required|array',
            'price.*' => 'required|numeric',

            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable|integer',

            'customer_id' => 'nullable|integer',

            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'tin' => 'nullable|string',
            'address' => 'nullable|string',

            'amountTotal' => 'required|numeric',
        ]);

        $companyId = Auth::user()->company_id;

        if ($request->filled('tin')) {
            $existingCustomer = DB::table('stakeholders')
                ->where('tin', $request->tin)
                ->exists();

            if ($existingCustomer == true) {
                return redirect()->back()->with('error_msg', 'Customer you are trying to add already exists!');
            }

            $customerId = DB::table('stakeholders')->insertGetId([
                'name' => $request->name,
                'phone' => $request->phone,
                'tin' => $request->tin,
                'address' => $request->address,
            ]);

            $invoiceId = DB::table('invoice')->insertGetId([
                'customer_id' => $customerId,
                'amount' => $request->amountTotal,
                'created_at' => Carbon::now(),
                'company_id' => $companyId,
                'updated_at' => Carbon::now(),
                'is_profoma' => 1,
            ]);

            foreach ($request->service_id as $key => $serviceId) {

                $invoiceItmId = DB::table('invoive_service_items')->insertGetId([
                    'invoice_id' => $invoiceId,
                    'service_id' => $serviceId,
                    'amount' => $request->price[$key],
                    'discount' => $request->discount[$key],
                    'quantity' => $request->quantity[$key] ?? 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::table('profoma_invoice')->insert([
                    'invoice_id' => $invoiceId,
                    'category_id' => $request->category_id,
                    'invoice_item_id' => $invoiceItmId,
                    'customer_id' => $customerId,
                    'amount' => $request->price[$key],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // dd($request->all());

        $invoiceId = DB::table('invoice')->insertGetId([
            'customer_id' => $request->customer_id,
            'amount' => $request->amountTotal,
            'created_at' => Carbon::now(),
            'company_id' => $companyId,
            'updated_at' => Carbon::now(),
            'is_profoma' => 1,
        ]);

        foreach ($request->service_id as $key => $serviceId) {

            $invoiceItmId = DB::table('invoive_service_items')->insertGetId([
                'invoice_id' => $invoiceId,
                'service_id' => $serviceId,
                'amount' => $request->price[$key],
                'quantity' => $request->quantity[$key] ?? 0,
                'discount' => $request->discount[$key] ?? 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('profoma_invoice')->insert([
                'invoice_id' => $invoiceId,
                'category_id' => $request->category_id,
                'invoice_item_id' => $invoiceItmId,
                'customer_id' => $request->customer_id,
                'amount' => $request->price[$key],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('profoma.invoice')->with('success_msg', 'Profoma invoice created successfully!');
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
