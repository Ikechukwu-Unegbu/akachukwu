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
use App\Models\User;

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
        $expenses = AirtimeTransaction::where('user_id', $userId)->where('vendor_status', 'successful')->sum('amount')
            + DataTransaction::where('user_id', $userId)->where('vendor_status', 'successful')->sum('amount')
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

     /**
     * Fetch all usernames a given user sent money to and the total sent to each.
     *
     * @param int $userId
     * @return array
     */
    public function getSentMoneySummary($userId)
    {
        // Group by recipient and sum the amount
        $sentSummary = MoneyTransfer::where('user_id', $userId)
            ->selectRaw('recipient, SUM(amount) as total_sent')
            ->groupBy('recipient')
            ->get();

        // Map recipient IDs to usernames and total sent
        $summary = $sentSummary->map(function ($transaction) {
            $recipientUser = User::find($transaction->recipient);
            return [
                'username' => $recipientUser ? $recipientUser->username : 'Unknown User',
                'total_sent' => $transaction->total_sent,
            ];
        });

        return $summary->toArray();
    }

    /**
     * Fetch all usernames that sent money to a given user and the total received from each.
     *
     * @param int $userId
     * @return array
     */
    public function getReceivedMoneySummary($userId)
    {
        // Group by sender (user_id) and sum the amount
        $receivedSummary = MoneyTransfer::where('recipient', $userId)
            ->selectRaw('user_id, SUM(amount) as total_received')
            ->groupBy('user_id')
            ->get();

        // Map sender IDs to usernames and total received
        $summary = $receivedSummary->map(function ($transaction) {
            $senderUser = User::find($transaction->user_id);
            return [
                'username' => $senderUser ? $senderUser->username : 'Unknown User',
                'total_received' => $transaction->total_received,
            ];
        });

        return $summary->toArray();
    }
}
