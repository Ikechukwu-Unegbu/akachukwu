<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use App\Services\Payment\VirtualAccountServiceFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VirtualAccountService
{
    public function getAuthenticatedUser ()
    {
        return Auth::user();
    }

    public function fetchVirtualAccounts()
    {
        return $this->getAuthenticatedUser()->virtualAccounts;
    }

    public function createVirtualAccount($data)
    {        
        try {
            $activeGateway = PaymentGateway::where('name', 'Monnify')->firstOrFail();
            $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);

            if (isset($data['bvn']) && !empty($data['bvn'])) {
                return $virtualAccountFactory::verifyBvn($data['bvn'], $data['bank_code'], $data['account_number']);
            }

            if (isset($data['nin']) && !empty($data['nin'])) {
                return $virtualAccountFactory::verifyNin($data['nin']);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function getBanks()
    {
        return Bank::orderBy('name')->get();
    }
}