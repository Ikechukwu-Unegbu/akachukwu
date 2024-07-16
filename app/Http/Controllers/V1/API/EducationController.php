<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Models\Vendor;
use App\Http\Controllers\Controller;
use App\Services\Education\ResultCheckerService;
use App\Http\Requests\V1\Api\ResultCheckerRequest;
use App\Http\Resources\V1\API\ResultCheckerResource;
use App\Models\Education\ResultCheckerTransaction;

class EducationController extends Controller
{
    protected $vendor;
    protected $resultCheckerService;
    public function __construct(Vendor $vendor, ResultCheckerService $resultCheckerService)
    {
        $this->vendor = $vendor->where('name', 'VTPASS')->first();
        $this->resultCheckerService = $resultCheckerService;
    }

    public function index()
    {
        $response = $this->resultCheckerService::exams($this->vendor->id);

        if (count($response)) {
            return ApiHelper::sendResponse($response, "Result Checker PIN");
        }
    }

    public function create(ResultCheckerRequest $request)
    {
        $response = $this->resultCheckerService::create($this->vendor->id, $request->exam_name, $request->quantity);

        if ($response->status) {
            $transactionResource = new ResultCheckerResource(ResultCheckerTransaction::find($response->response->id));
            return ApiHelper::sendResponse($transactionResource, $response->message);
        }

        return $response;
    }
}
