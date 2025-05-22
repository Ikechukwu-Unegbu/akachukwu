<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\ScheduledTransaction;

class ScheduledTransactionService
{
    public static function initiateScheduler($transaction, $data)
    {
        try {
            $nextRunAt = Carbon::parse($data['start_date'] . ' ' . $data['time']);

            $scheduledTransaction = ScheduledTransaction::create([
                'transaction_id'  =>   $transaction->id,
                'type'            =>   $data['type'],
                'payload'         =>   json_encode($transaction),
                'frequency'       =>   $data['frequency'],
                'start_date'      =>   $data['start_date'],
                'time'            =>   $data['time'],
                'next_run_at'     =>   $nextRunAt,
            ]);

            $transaction->update(['scheduled_transaction_id' => $scheduledTransaction->id]);

            $formattedDateTime = $scheduledTransaction->created_at->format('F j, Y \a\t g:i A');
            $successMessage = "Schedule created successfully! " .  "Your {$scheduledTransaction->frequency} reminder is set for {$formattedDateTime}.";

            return collect([
                'status' => true,
                'message' => $successMessage
            ]);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return  collect(['status' => false]);
        }
    }
}
