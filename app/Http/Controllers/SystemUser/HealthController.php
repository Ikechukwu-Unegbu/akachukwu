<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Health\DataHealthService;
use App\Services\Health\AirtimeHealthService;
use App\Services\Health\SystemHealthService;
use App\Services\Health\TransactionHealthService;

class HealthController extends Controller
{


    protected $systemHealthService;
    protected $airtimeHealthService;
    protected $dataHealthService;
    protected $transactionHealthService;


    public function __construct(
        TransactionHealthService $transactionHealthService,
        SystemHealthService $systemHealthService,
        AirtimeHealthService $airtimeHealthService,
        DataHealthService $dataHealthService
    ) {
        $this->systemHealthService = $systemHealthService;
        $this->airtimeHealthService = $airtimeHealthService;
        $this->dataHealthService = $dataHealthService;
        $this->transactionHealthService = $transactionHealthService;
    }


    public function index(Request $request)
    {
        $duration = $request->query('duration');
    
        $systemHealth = $this->systemHealthService->getSystemHealth();
        $airtimeSuccessRate = $this->airtimeHealthService->successRate($duration);
        $dataSuccessRate = $this->dataHealthService->successRate($duration);
        $transactions = $this->transactionHealthService->getLastFailedTransactions();
    
        return view('system-user.health.index', compact(
            'systemHealth',
            'airtimeSuccessRate',
            'dataSuccessRate',
            "transactions"
        ));
    }
    
}
