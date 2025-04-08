<?php

namespace App\Http\Controllers;

use App\Services\MtnService;
use Illuminate\Http\Request;

class MtnDevController extends Controller
{
    protected $mtnService;

    public function __construct(MtnService $mtnService)
    {
        $this->mtnService = $mtnService;
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'service_id' => 'required|string',
        ]);

        $response = $this->mtnService->subscribeUser($request->phone_number, $request->service_id);

        return response()->json($response);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'subscription_id' => 'required|string',
        ]);

        $response = $this->mtnService->unsubscribeUser($request->phone_number, $request->subscription_id);

        return response()->json($response);
    }

    public function listSubscriptionPlans()
    {
        $plans = $this->mtnService->getSubscriptionPlans();

        return response()->json($plans);
    }

}
