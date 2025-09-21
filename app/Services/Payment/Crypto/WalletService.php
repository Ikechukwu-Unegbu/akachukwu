<?php
namespace App\Services\Payment\Crypto;

use App\Services\Payment\Crypto\QuidaxxService;
use App\Helpers\ApiHelper;
use App\Models\Payment\CryptoWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WalletService
{
    protected $quidaxService;

    public function __construct()
    {
        $this->quidaxService = new QuidaxxService();
    }

    public function createUser()
    {
        $user = Auth::user();
        $name = explode(' ', $user->name);

        $data = [
            "email" => $user->email,
            "first_name" => $name[0],
            "last_name" => $name[1] ,
            "phone_number" => $user->phone
        ];

        return $this->quidaxService->createUser($data, $user);
    }

    public function fetchUsers()
    {
        return $this->quidaxService->fetchUsers();
    }

    /**
     * Get user account information
     */
    public function getAccountInfo($quidaxId)
    {
        return $this->quidaxService->getAccountInfo($quidaxId);
    }

    /**
     * Get all user wallets
     */
    public function getUserWallets()
    {
        return $this->quidaxService->getUserWallets();
    }

    /**
     * Get specific wallet balance
     */
    public function getWalletBalance($currency)
    {
        return $this->quidaxService->getWalletBalance($currency);
    }

    /**
     * Get account balance summary
     */
    public function getAccountBalanceSummary()
    {
        return $this->quidaxService->getAccountBalanceSummary();
    }


    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies()
    {
        return $this->quidaxService->getSupportedCurrencies();
    }


    /**
     * Get wallet statistics
     */
    public function getWalletStats()
    {
        $wallets = $this->getUserWallets();

        if (!$wallets['status']) {
            return $wallets;
        }

        $totalBalance = 0;
        $walletCount = 0;
        $currencies = [];

        foreach ($wallets['data'] as $wallet) {
            if ($wallet['balance'] > 0) {
                $totalBalance += $wallet['balance'];
                $walletCount++;
                $currencies[] = $wallet['currency'];
            }
        }

        return ApiHelper::sendResponse([
            'total_wallets' => $walletCount,
            'total_balance' => $totalBalance,
            'currencies' => $currencies
        ], 'Wallet statistics retrieved successfully');
    }

}
