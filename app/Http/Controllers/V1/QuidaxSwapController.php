<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\Crypto\QuidaxxService;
use App\Services\Payment\Crypto\WalletService;

class QuidaxSwapController extends Controller
{
    /**
     * Generate a swap quotation from one crypto to another
     * Body: { from_currency, from_amount, to_currency }
     */
    public function generateSwapQuotation(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string',
            'from_amount'   => 'required|numeric|min:0.00000001',
            'to_currency'   => 'required|string',
        ]);

        $user = auth()->user();

        // Ensure Quidax user exists
        if (empty($user->quidax_id)) {
            (new WalletService())->createUser();
            $user->refresh();
        }

        $quidax = new QuidaxxService();
        $payload = [
            'from_currency' => strtoupper($request->input('from_currency')),
            'from_amount'   => (string) $request->input('from_amount'),
            'to_currency'   => strtoupper($request->input('to_currency')),
        ];

        $result = $quidax->makeRequest('get', "/users/{$user->quidax_id}/swap_quotation", $payload);
        return response()->json($result);
    }

    /**
     * Confirm a swap using a quotation ID
     * Body: { quotation_id }
     */
    public function confirmSwap(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|string',
        ]);

        $user = auth()->user();
        if (empty($user->quidax_id)) {
            (new WalletService())->createUser();
            $user->refresh();
        }

        $quidax = new QuidaxxService();
        $quotationId = $request->input('quotation_id');
        $result = $quidax->makeRequest('post', "/users/{$user->quidax_id}/swap_quotation/{$quotationId}/confirm");
        return response()->json($result);
    }
}
