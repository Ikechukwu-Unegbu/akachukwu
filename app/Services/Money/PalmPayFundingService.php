<?php

namespace App\Services\Money;

use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use App\Models\PalmPayTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PalmPayFundingService extends BasePalmPayService
{
    public static function webhook(Request $request)
    {
        // Verify the webhook signature
        $webhook = [
            'ip'      => $request->ip(),
            'time'    => date('H:i:s'),
            'date'    => date('d-m-Y'),
            'payload' => $request->all(),
            'headers' => $request->headers->all()
        ];

        self::storePayload($webhook);

        try {

            $accountReference = $request->accountReference;
            $headers = $request->headers->all();
            $paymentReference = $request->orderNo;
            $transactionReference = $request->sessionId;
            $amountPaid = $request->orderAmount / 100;

            if (PalmPayTransaction::where('reference_id', $paymentReference)->where('status', true)->exists()) {
                return response()->json(['message' => 'Payment Already Processed'], 403);
            }
    
            if ($request->appId === $headers['appid'][0] && $request->sign === $headers['sign'][0]) {
                $virtualAccount = VirtualAccount::where('reference', $accountReference)->first();

                if ($virtualAccount) {
                    $user = $virtualAccount->user;

                    $transaction = PalmPayTransaction::updateOrCreate([
                        'reference_id'  => $paymentReference,
                        'trx_ref'       => $transactionReference,
                        'user_id'       => $user->id,
                    ], [
                        'amount'        => $amountPaid,
                        'currency'      => config('app.currency', 'NGN'),
                        'redirect_url'  => config('app.url'),
                        'meta'          => json_encode($webhook)
                    ]);

                    $user->setAccountBalance($amountPaid);
                    $transaction->success();

                    return response()->json([
                        'status'   =>    true,
                        'error'    =>    NULL,
                        'message'  =>    "Transaction successful",
                        'response' =>    $transaction
                    ], 200)->getData();
                }
            }
    
            return response()->json([
                'status'   =>    false,
                'error'    =>    "User not found",
                'message'  =>    "Transaction not successful",
                'response' =>    []
            ], 200)->getData();

        } catch (\Throwable $th) {
            $errors = [
                'status'   =>    false,
                'error'    =>    "Server Error",
                'message'  =>    $th->getMessage(),
                'response' =>    []
            ];
            Log::error($errors);
            return response()->json($errors, 401)->getData();
        }
    }

    public static function storePayload($payload)
    {
        $filename = 'palmpay-payload.json';
        $payloadString = json_encode($payload, JSON_PRETTY_PRINT);
    
        if (Storage::disk('webhooks')->exists($filename)) {

            $existingContent = Storage::disk('webhooks')->get($filename);
    
            $existingContent = rtrim($existingContent, "\n]");
    
            if (strlen($existingContent) > 1) {
                $existingContent .= ",\n";
            }

            $newContent = $existingContent . $payloadString . "\n]";
            Storage::disk('webhooks')->put($filename, $newContent);
        } else {
            $newContent = "[\n" . $payloadString . "\n]";
            Storage::disk('webhooks')->put($filename, $newContent);
        }
    }
}
