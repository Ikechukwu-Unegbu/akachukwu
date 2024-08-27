<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Payment\MonnifyTransaction;
use App\Services\Payment\MonnifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentApiController extends Controller
{
    
    public function initiatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount'=>'required',
            'gateway'=>'required'
        ]);

        if($validator->fails()){
            return ApiHelper::sendError($validator->errors(), 'There is a missing field');
        }

        if ($request->gateway !== 'paystack' && $request->gateway !== 'flutterwave' && $request->gateway !== 'monnify') {
           return ApiHelper::sendError(['Invalid payment gateway provided'], 'Invalid payment gateway');
       }

       if ($request->gateway === 'monnify') return $this->payWithMonnify($request);
    }

    private function payWithMonnify(Request $request)
    {
        $transaction = MonnifyTransaction::create([
            'user_id'       =>  auth()->user()->id,
            'reference_id'  => $this->generateUniqueId(),
            'amount'        => $$request->amount,
            'currency'      => config('app.currency', 'NGN'),
            'meta'          =>  [
                'token'   =>  $request->_token,
            ] 
        ]);
        return $transaction;
    }

   
    public function confirmPayment(Request $request, MonnifyService $monnifyService)
    {
        $processPayment = $monnifyService->processPayment($request);

        if($processPayment == false){
            return ApiHelper::sendError([], 'Failed payment attempt');
        }
        return ApiHelper::sendResponse([], 'Payment successful');
    }
    

}
