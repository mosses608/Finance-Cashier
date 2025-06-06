<?php

namespace App\Http\Controllers\MobilePayments;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Thulani\AirtelMoneyPhpSdk\AirtelCollection;

class PaymentGateWayController extends Controller
{
    //
    public function airtelMoney(Request $request){
        return view('airtelMoney');
    }

    public function transactionAirtelMoney(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|min:10',
            'amount' => 'required',
        ]);

        $phoneNumber = $request->phone_number;
        $amount = $request->amount;

        $config = [
            'client_id' => env('YOUR_CLIENT_ID'),
            'client_secret' => env('YOUR_CLIENT_SECRET'),
            'public_key' => env('YOUR_PUBLIC_KEY'),
            'currency' => 'TSH',
            'country' => 'TZ',
            'env' => 'staging'
        ];

        $airtel = new AirtelCollection($config);

        $airtel->authenticate();

        $transactionRef = 'trx-ref-' . uniqid();
        $transactionId = 'trx-id-' . uniqid();

        try {
            $result = $airtel->initiateUssdPush(
                $amount,
                $phoneNumber,
                $transactionId,
                $transactionRef,
                'TSH',
                'TZ'
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
