<?php 
namespace App\Actions\Automatic\Accounts;

use App\Helpers\ApiHelper;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\Payment\VirtualAccountServiceFactory;

class GenerateRemainingAccounts{

    // public $expectedBankCodes = ["120001", "035", "50515"]; //9PSB, Wema, Moniepoint
    public $expectedBankCodes = ["120001", "50515", "100033"]; //9PSB, Moniepoint, Palmpay

    // public static $virtualAccountService = ["120001" => "9PSB", "035" => "WEMA", "50515" => "MONIEPOINT"];
    public static $virtualAccountService = [
        "120001" => "9PSB", 
        "50515" => "MONIEPOINT", 
        "100033" => "PALMPAY"
    ];

    protected static $virtualAccountProviders = [
        "120001" => "Payvessel", 
        "50515" => "Monnify", 
        "100033" => "Palmpay"
    ];

    public function generateRemaingingAccounts():void
    {
        if ($this->isUserAccountLessThanThree() && auth()->user()->isKycDone()) {
            $bankCodes = $this->getBankCodesOfMissingAccounts();
            // dd($bankCodes);

            foreach($bankCodes as $code){
                if($code == "120001"){
                    $payVessleGateway = PaymentGateway::where('name', 'Payvessel')->first();
                    $virtualAccountFactory = VirtualAccountServiceFactory::make($payVessleGateway);
                    $response = $virtualAccountFactory::createSpecificVirtualAccount(auth()->user(), null, $code);
                }
                // dd('wasnt ok');
                if($code == "100033" || $code == "50515"){
                    $monifyGateway = PaymentGateway::where('name', 'Monnify')->first();
                    $virtualAccountFactory = VirtualAccountServiceFactory::make($monifyGateway);
                    $response = $virtualAccountFactory::createSpecificVirtualAccount(auth()->user(), null, $code);
                }

                if ($code == "100033"){
                    $palmPayGateway = PaymentGateway::where('name', 'Palmpay')->first();
                    $virtualAccountFactory = VirtualAccountServiceFactory::make($palmPayGateway);
                    $virtualAccountFactory::createSpecificVirtualAccount(auth()->user(), null, $code);
                }
            }
        }
    }

    public function isUserAccountLessThanThree($userId = null):bool
    {
        $userId = ($userId) ? $userId : auth()->id();
        $virtualAccountCount = VirtualAccount::where('user_id', $userId)->count();
    
        return $virtualAccountCount < 3;
    }

  

    public function getBankCodesOfMissingAccounts():array 
    {
        $userId = auth()->id();
        $userBankCodes = VirtualAccount::where('user_id', $userId)
            ->pluck('bank_code')
            ->toArray();
    
        $missingBankCodes = array_diff($this->expectedBankCodes, $userBankCodes);
    
        return array_values($missingBankCodes);
    }

    public function checkKyc($user)
    {
        return !is_null($user->nin) || !is_null($user->bvn);
    }
    
    public function generateSpecificAccount($user, $bankCode)
    {
        if (!$this->checkKyc($user)) {
            return ApiHelper::sendError("Error", "User KYC not completed");
        }

        $providerName = $this->getProviderNameByBankCode($bankCode);

        if (!$providerName) {
            return ApiHelper::sendError("Server Error", "Provider Not Found!");
        }

        if ($this->isUserAccountLessThanThree($user->id)) {
            $gateway = PaymentGateway::where('name', $providerName)->first();

            if (!$gateway) {
                return ApiHelper::sendError("Server Error", "Provider Not Found!");
            }

            $virtualAccountFactory = VirtualAccountServiceFactory::make($gateway);
            return $virtualAccountFactory::createSpecificVirtualAccount($user, null, $bankCode);
        }

        return false;
    }

    private function getProviderNameByBankCode($bankCode)
    {
        $providerMappings = self::$virtualAccountProviders;
        return $providerMappings[$bankCode] ?? null;
    }
}