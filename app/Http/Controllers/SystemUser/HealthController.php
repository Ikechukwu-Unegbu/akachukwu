<?php

namespace App\Http\Controllers\SystemUser;

use Illuminate\Http\Request;
use App\Helpers\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Services\Health\KycHealthService;
use App\Services\Health\DataHealthService;
use App\Services\Health\SystemHealthService;
use App\Services\Health\AirtimeHealthService;
use App\Services\Health\PalmPayHealthService;
use App\Services\Health\TransactionHealthService;

class HealthController extends Controller
{


    protected $systemHealthService;
    protected $airtimeHealthService;
    protected $dataHealthService;
    protected $transactionHealthService;
    protected $kycHealthService;
    protected $palmPayHealthService;

    public function __construct(
        TransactionHealthService $transactionHealthService,
        SystemHealthService $systemHealthService,
        AirtimeHealthService $airtimeHealthService,
        DataHealthService $dataHealthService,
        KycHealthService $kycHealthService,
        PalmPayHealthService $palmPayHealthService
    ) {
        $this->systemHealthService = $systemHealthService;
        $this->airtimeHealthService = $airtimeHealthService;
        $this->dataHealthService = $dataHealthService;
        $this->transactionHealthService = $transactionHealthService;
        $this->kycHealthService = $kycHealthService;
        $this->palmPayHealthService = $palmPayHealthService;
    }


    public function index(Request $request)
    {
        $duration = $request->query('duration');
    
        $systemHealth = $this->systemHealthService->getSystemHealth();
        $airtimeSuccessRate = $this->airtimeHealthService->successRate($duration);
        $dataSuccessRate = $this->dataHealthService->successRate($duration);
        $transactions = $this->transactionHealthService->getLastFailedTransactions();
        $kycHealthService = $this->kycHealthService;
        $totalUsersCount = GeneralHelpers::totalUsersCount();
        $palmPayHealthService = $this->palmPayHealthService;
        
        return view('system-user.health.index', compact(
            'systemHealth',
            'airtimeSuccessRate',
            'dataSuccessRate',
            "transactions",
            'kycHealthService',
            'totalUsersCount',
            'palmPayHealthService'
        ));
    }
    
}
