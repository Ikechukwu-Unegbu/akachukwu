<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Health\DataHealthService;
use App\Services\Health\AirtimeHealthService;
use App\Services\Health\SystemHealthService;

class HealthController extends Controller
{


    protected $systemHealthService;
    protected $airtimeHealthService;
    protected $dataHealthService;

    public function __construct(
        SystemHealthService $systemHealthService,
        AirtimeHealthService $airtimeHealthService,
        DataHealthService $dataHealthService
    ) {
        $this->systemHealthService = $systemHealthService;
        $this->airtimeHealthService = $airtimeHealthService;
        $this->dataHealthService = $dataHealthService;
    }


    public function index()
    {
        // Fetch system health metrics
        $systemHealth = $this->systemHealthService->getSystemHealth();
        
        // Fetch transaction success rates
        $airtimeSuccessRate = $this->airtimeHealthService->successRate();
        $dataSuccessRate = $this->dataHealthService->successRate();
        // var_dump($airtimeSuccessRate);die;
        return view('system-user.health.index', compact(
            'systemHealth',
            'airtimeSuccessRate',
            'dataSuccessRate'
        ));
    }
}
