<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    //
    public function servicePage()
    {
        $services = DB::table('service')->select('*')->where('soft_delete', 0)->orderBy('name', 'ASC')->get();
        $customers = DB::table('customer')->select('name', 'id')->where('soft_delete', 0)->orderBy('name', 'ASC')->get();
        return view('services.service-page', compact('services', 'customers'));
    }

    public function storeServices(Request $request)
    {
        $request->validate([
            'service_name' => 'required|array',
            'service_name.*' => 'required|string',

            'amount' => 'required|array',
            'amount.*' => 'required|numeric',

            'category' => 'nullable|array',
            'category.*' => 'nullable|string',

            'description' => 'nullable|array',
            'description.*' => 'nullable|string'
        ]);

        foreach ($request->service_name as $key => $serviceName) {
            $existingService = DB::table('service')->where('name', $request->service_name[$key])->exists();

            if ($existingService == true) {
                return redirect()->back()->with('error_msg', 'Service already exists!');
            }

            DB::table('service')->insert([
                'name' => $request->service_name[$key],
                'description' => $request->description[$key],
                'price' => $request->amount[$key],
                'category' => $request->category[$key],
                'created_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success_msg', 'Service added successfully!');
        // dd($request->all());
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

            'customer_id' => 'nullable|integer',

            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'TIN' => 'nullable|string',
            'address' => 'nullable|string',

            'amountTotal' => 'required|numeric',
        ]);


        if ($request->filled('TIN')) {
            $existingCustomer = DB::table('customer')
                ->where('name', $request->name)
                ->where('phone', $request->phone)
                ->where('TIN', $request->TIN)
                ->exists();

            if ($existingCustomer == true) {
                return redirect()->back()->with('error_msg', 'Customer you are trying to add already exists!');
            }

            $customerId = DB::table('customer')->insertGetId([
                'name' => $request->name,
                'phone' => $request->phone,
                'TIN' => $request->TIN,
                'address' => $request->address,
            ]);

            $invoiceId = DB::table('invoice')->insertGetId([
                'customer_id' => $customerId,
                'amount' => $request->amountTotal,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_profoma' => 1,
            ]);

            foreach ($request->service_id as $key => $serviceId) {

                $invoiceItmId = DB::table('invoice_items')->insertGetId([
                    'invoice_id' => $invoiceId,
                    'item_id' => $request->service_id[$key],
                    'amount' => $request->price[$key],
                    'discount' => $request->discount[$key],
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
            'updated_at' => Carbon::now(),
            'is_profoma' => 1,
        ]);

        foreach ($request->service_id as $key => $serviceId) {

            $invoiceItmId = DB::table('invoice_items')->insertGetId([
                'invoice_id' => $invoiceId,
                'item_id' => $request->service_id[$key],
                'amount' => $request->price[$key],
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

        return redirect()->route('profoma.invoice')->with('success_msg','Profoma invoice created successfully!');
    }
}
