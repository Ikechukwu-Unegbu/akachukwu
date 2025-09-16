<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\Crypto\QuidaxSwapService;
use App\Services\Payment\Crypto\WalletService;
use App\Services\Payment\Crypto\QuidaxxService;

class QuidaxSwapController extends Controller
{
    /**
     * Generate a swap quotation via QuidaxSwapService
     * Body: { from_currency, from_amount, to_currency }
     */
    public function generateSwapQuotation(Request $request)
    {
    
        $request->validate([
            'from_currency' => 'required|string',
            'from_amount'   => 'required|numeric|min:0.00000001',
            'to_currency'   => 'required|string',
        ]);


        $user = auth()->user();
        if (empty($user->quidax_id)) {
            (new WalletService())->createUser();
            $user->refresh();
        }

        $service = new QuidaxSwapService(new QuidaxxService());
        $result = $service->generateSwapQuotation(
            $user->quidax_id,
            $request->input('from_currency'),
            (string) $request->input('from_amount'),
            $request->input('to_currency')
        );
        // dd($result->response->data->user->id);

        if($result->response->status == true || $result->response->status == 'success'){
            $swaid = $result->response->data->id;
            $userQuidaxId = $result->response->data->user->id;
            $confirm = $service->confirmQuidaxSwap($swaid, $userQuidaxId);
       

            return response()->json([
                'status'=>'success',
                'message'=>'Swap successful',
                'data'=> $confirm->response->data->received_amount,            ]);
        }
        
        // return response
    }


}
