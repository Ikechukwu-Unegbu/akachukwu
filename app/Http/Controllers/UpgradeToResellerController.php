<?php

namespace App\Http\Controllers;

use App\Services\UpgradeService;
use Illuminate\Http\Request;

class UpgradeToResellerController extends Controller
{

    // public function __construct(Auth)
    // {
        
    // }
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpgradeService $upgradeService)
    {
        $upgradeService->requestUpgrade();
        return redirect()->back()->with('success', 'Upgrade to Reseller Requested Successfully');
    }
}
