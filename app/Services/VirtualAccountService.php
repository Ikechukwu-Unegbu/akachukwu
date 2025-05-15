<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\User;
use App\Helpers\ApiHelper;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\Payment\VirtualAccountServiceFactory;

class VirtualAccountService
{

    protected static $providerMapping = [
        '9PSB' => [
            'code' => '120001',
            'gateway_name' => 'Payvessel'
        ],
        'MONIEPOINT' => [
            'code' => '50515',
            'gateway_name' => 'Monnify'
        ],
        'PALMPAY' => [
            'code' => '100033',
            'gateway_name' => 'Palmpay'
        ]
    ];

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
                return $virtualAccountFactory::apiVerifyBvn($data['bvn'], $data['bank_code'], $data['account_number']);
            }

            if (isset($data['nin']) && !empty($data['nin'])) {
                return $virtualAccountFactory::apiVerifyNin($data['nin']);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function generateSpecificVirtualAccount(User $user, array $providers): array
    {
        $results = [];

        foreach ($providers as $provider) {
            try {
                if (!array_key_exists($provider, self::$providerMapping)) {
                    throw new \Exception("Unsupported provider: {$provider}");
                }


                $response = $this->processProvider($user, $provider);

                return  (array) $response;

            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                $errorResponse = [
                    'error'    =>    "Server Error",
                    'message'  =>    "Opps! Unable to create your virtual account. Pleas try again later.",
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }
        }

        return $results;
    }

    /**
     * Process virtual account creation based on provider name
     *
     * @param User $user
     * @param string $provider
     * @return mixed
     * @throws Exception
     */
    protected function processProvider(User $user, string $provider)
    {
        $mapping = self::$providerMapping[$provider];
        $gateway = PaymentGateway::where('name', $mapping['gateway_name'])->first();

        if (!$gateway) {
            throw new \Exception("{$mapping['gateway_name']} gateway not configured");
        }

        $factory = VirtualAccountServiceFactory::make($gateway);

        return $factory::createSpecificVirtualAccount($user, null, $mapping['code']);
    }

    public function getBanks()
    {
        return Bank::orderBy('name')->get();
    }
}
