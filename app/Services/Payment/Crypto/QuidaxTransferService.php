<?php 
namespace App\Services\Payment\Crypto;

use App\Services\Payment\Crypto\QuidaxService;

class QuidaxTransferService
{
    protected $quidaxService; 

    public function __construct(QuidaxService $quidaxService)
    {
        $this->quidaxService = $quidaxService;
    }

    /**
     * Transfer funds via Quidax
     *
     * @param string $amount
     * @param string $currency
     * @param string $transactionNote
     * @param string $narration
     * @param string $targetAddress   Wallet address or sub-account
     * @param string $userQuidaxId     auth users quidax unique identify
     * @param string|null $network    (Optional) Blockchain network
     * @return mixed
     */
    public function transferFunds($amount, $currency, $transactionNote, $narration, $targetAddress, $userQuidaxId, $network = null)
    {
        $payload = [
            "currency"         => $currency,
            "amount"           => $amount,
            "transaction_note" => $transactionNote,
            "narration"        => $narration,
            "fund_uid"         => $targetAddress,
            "reference"        => uniqid("quidax_", true),
        ];

        if (!is_null($network)) {
            $payload['network'] = $network;
        }

        return $this->quidaxService->makeRequest(
            'post',
            "/users/{$userQuidaxId}/withdraws",
            $payload
        );
    }

}
