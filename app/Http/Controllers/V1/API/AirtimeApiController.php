<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Services\Airtime\AirtimeService;
use App\Models\Utility\AirtimeTransaction;
use App\Services\Account\AccountBalanceService;
use App\Http\Requests\V1\Api\AirtimeApiRequest;
use App\Models\User;
use App\Models\Utility\Airtime;

class AirtimeApiController extends Controller
{
    protected $vendor;
    
    public function __construct(DataVendor $datavendor, DataNetwork $datanetwork)
    {
        
    }

    public function store(AirtimeApiRequest $request, int $userId)
    {
        $airtimeService = new Airtime($this->vendor, $request->network, User::find($userId));
        $response = $airtimeService->airtime($request->validated()['amount'], $request->validated()['phone_number']);
        $response = json_decode($response);

        if(isset($response->rerror)){
            return response()->json([

            ]);
        }
    }
}
