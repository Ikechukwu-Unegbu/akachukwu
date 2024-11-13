<?php 
namespace App\Actions\Automatic\Accounts;

use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Auth;

class GenerateRemainingAccounts{

    public $expectedBankCodes = ["120001", "035", "50515"]; //9PSB, Wema, Moniepoint

    public function generateRemaingingAccounts():void
    {
        if(!$this->isUserAccountLessThanThree()){
            $bankCodes = $this->getBankCodesOfMissingAccounts();

            foreach($bankCodes as $code){
                if($code == "120001"){
                    $payVessleGateway = PaymentGateway::where('name', 'Payvessel')->first();

                }
                if($code == '035' || $code == '50515'){
                    $monifyGateway = PaymentGateway::where('name', 'monnify')->first();

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