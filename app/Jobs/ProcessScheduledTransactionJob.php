<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Services\Data\DataService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use App\Models\ScheduledTransaction;
use Illuminate\Queue\SerializesModels;
use App\Services\Airtime\AirtimeService;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Utility\AirtimeTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessScheduledTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public ScheduledTransaction $transaction;

    public function __construct(ScheduledTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $log = $this->handleLogs();

            $this->transaction->update([
                'status' => 'processing',
                'logs' => $this->appendToLogs($log)
            ]);

            $payload = json_decode($this->transaction->payload);

            $isInitialRun = $this->transaction->last_run_at === null;

            if ($isInitialRun) {
                Auth::loginUsingId($payload->user_id);

                if ($this->transaction->type === 'airtime') {
                    $vendorId = $payload->vendor_id;
                    $networkId = $payload->network_id;
                    $amount = $payload->amount;
                    $phone_number = $payload->mobile_number;

                    $transaction = AirtimeTransaction::find($payload->id);

                    app(AirtimeService::class)::create(
                        $vendorId, $networkId, $amount,  $phone_number, false, [], true, $transaction
                    );

                } elseif ($this->transaction->type === 'data') {
                    $vendorId = $payload->vendor_id;
                    $networkId = $payload->network_id;
                    $typeId = $payload->type_id;
                    $plan = $payload->data_id;
                    $amount = $payload->amount;
                    $phone_number = $payload->mobile_number;

                    $transaction = DataTransaction::find($payload->id);

                    app(DataService::class)::create(
                        $vendorId, $networkId, $typeId, $plan, $phone_number, false, [], true, $transaction
                    );

                }

                $log['status'] = 'success';
                $log['message'] = 'Initial transaction executed successfully.';
                $log['is_initial_run'] = true;

                $this->transaction->update([
                    'status' => 'completed',
                    'last_run_at' => $this->transaction->next_run_at,
                    'next_run_at' => $this->calculateNextRunAt($this->transaction),
                    'logs' => $this->appendToLogs($log),
                ]);

                Auth::logout();
                return;
            }

            $alreadyProcessed = match ($this->transaction->type) {
                'airtime' => AirtimeTransaction::where('scheduled_transaction_id', $this->transaction->id)
                    ->whereDate('created_at', $this->transaction->next_run_at->toDateString())
                    ->whereTime('created_at', $this->transaction->next_run_at->toTimeString())
                    ->exists(),
                'data' => DataTransaction::where('scheduled_transaction_id', $this->transaction->id)
                    ->whereDate('created_at', $this->transaction->next_run_at->toDateString())
                    ->whereTime('created_at', $this->transaction->next_run_at->toTimeString())
                    ->exists(),
                default => true,
            };

            if (!$alreadyProcessed) {
                Auth::loginUsingId($payload->user_id);

                if ($this->transaction->type === 'airtime') {
                    $vendorId = $payload->vendor_id;
                    $networkId = $payload->network_id;
                    $amount = $payload->amount;
                    $phone_number = $payload->mobile_number;
                    $runService = app(AirtimeService::class)::create($vendorId, $networkId, $amount, $phone_number);
                    $transaction = AirtimeTransaction::find($runService->response->id);
                    $transaction->update(['scheduled_transaction_id' => $this->transaction->id]);

                } elseif ($this->transaction->type === 'data') {
                    $vendorId = $payload->vendor_id;
                    $networkId = $payload->network_id;
                    $typeId = $payload->type_id;
                    $plan = $payload->data_id;
                    $amount = $payload->amount;
                    $phone_number = $payload->mobile_number;

                    $runService = app(DataService::class)::create($vendorId, $networkId, $typeId, $plan, $phone_number);

                    $transaction = DataTransaction::find($runService->response->id);
                    $transaction->update(['scheduled_transaction_id' => $this->transaction->id]);
                }

                $log['status'] = 'success';
                $log['message'] = 'Transaction executed successfully.';
                $log['is_initial_run'] = $isInitialRun;
            } else {
                $log['status'] = 'skipped';
                $log['message'] = 'Transaction already processed for this time.';
            }

            $this->transaction->update([
                'status' => 'completed',
                'last_run_at' => $this->transaction->next_run_at,
                'next_run_at' => $this->calculateNextRunAt($this->transaction),
                'logs' => $this->appendToLogs($log),
            ]);

            Auth::logout();
        } catch (\Exception $e) {
            $log['status'] = 'failed';
            $log['message'] = 'Error: ' . $e->getMessage();

            $this->transaction->update([
                'status' => 'failed',
                'logs' => $this->appendToLogs($log),
            ]);

            Log::error('Scheduled transaction failed', [
                'id' => $this->transaction->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function handleLogs()
    {
        return [
            'timestamp' => Carbon::now()->format('d M Y | h:i:s A'),
            'status' => 'started',
            'message' => 'Processing scheduled transaction',
        ];
    }

    protected function appendToLogs(array $entry): array
    {
        $logs = $this->transaction->logs ?? [];
        $logs[] = $entry;
        return $logs;
    }

    protected function calculateNextRunAt(ScheduledTransaction $transaction): Carbon
    {
        $current = Carbon::parse($transaction->next_run_at);

        return match ($transaction->frequency) {
            'hourly' => $current->addHour(),
            'daily' => $current->addDay(),
            'weekly' => $current->addWeek(),
            'monthly' => $current->addMonth(),
            'yearly' => $current->addYear(),
            default => $current,
        };
    }
}
