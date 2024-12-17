<?php

namespace App\Services\Account;

use App\Models\Payment\MonnifyTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Data\DataTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\Utility\ElectricityTransaction;
use App\Models\Payment\PayVesselTransaction;
use App\Models\MoneyTransfer;
use App\Models\Payment\VastelTransaction;

class UserTransactionsAuditService
{
    /**
     * Calculate user's expenses and funding.
     *
     * @param int $userId
     * @return array
     */
    public function calculateUserBalance($userId)
    {
        // Calculate expenses
        $expenses = AirtimeTransaction::where('user_id', $userId)->sum('amount')
            + DataTransaction::where('user_id', $userId)->sum('amount')
            + ElectricityTransaction::where('user_id', $userId)->sum('amount')
            + CableTransaction::where('user_id', $userId)->sum('amount')
            + ResultCheckerTransaction::where('user_id', $userId)->sum('amount')
            + MoneyTransfer::where('user_id', $userId)->sum('amount'); // Transfers sent

        // Calculate funding
        $funding = PayVesselTransaction::where('user_id', $userId)->sum('amount')
            + MonnifyTransaction::where('user_id', $userId)->sum('amount')
            + MoneyTransfer::where('recipient', $userId)->sum('amount') // Transfers received
            + VastelTransaction::where('user_id', $userId)->where('type', true)->sum('amount'); // Admin credits

        return [
            'expenses' => $expenses,
            'funding' => $funding,
            'net_balance' => $funding - $expenses,
        ];
    }
}
