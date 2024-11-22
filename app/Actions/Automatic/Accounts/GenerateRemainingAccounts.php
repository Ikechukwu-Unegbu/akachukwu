<?php 
namespace App\Actions\Automatic\Accounts;

use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use App\Services\Payment\VirtualAccountServiceFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GenerateRemainingAccounts{

    public $expectedBankCodes = ["120001", "035", "50515"]; //9PSB, Wema, Moniepoint

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
                if($code == "035" || $code == "50515"){
                    $monifyGateway = PaymentGateway::where('name', 'Monnify')->first();
                    $virtualAccountFactory = VirtualAccountServiceFactory::make($monifyGateway);
                    $response = $virtualAccountFactory::createSpecificVirtualAccount(auth()->user(), null, $code);
                }
            }
        }
    }

    public function isUserAccountLessThanThree():bool
    {
        $userId = auth()->id();
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
    
       
}