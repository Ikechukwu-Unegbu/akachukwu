<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Vendor;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\OneSignalNotificationService;
use App\Services\Education\ResultCheckerService;
use App\Http\Requests\V1\Api\ResultCheckerRequest;
use App\Models\Education\ResultCheckerTransaction;
use App\Http\Resources\V1\API\ResultCheckerResource;

class EducationController extends Controller
{
    protected $vendor;
    protected $resultCheckerService;
    protected $notificationService;

    public function __construct(Vendor $vendor, ResultCheckerService $resultCheckerService)
    {
        $this->vendor = $vendor->where('name', 'VTPASS')->first();
        $this->resultCheckerService = $resultCheckerService;
        $this->notificationService = new OneSignalNotificationService();
    }

    public function index()
    {
        $response = $this->resultCheckerService::exams($this->vendor->id);

        if (count($response)) {
            return ApiHelper::sendResponse($response, "Result Checker PIN");
        }

        return ApiHelper::sendResponse([], "Result Checker PINs not found!");
    }

    public function create(ResultCheckerRequest $request)
    {
        $response = $this->resultCheckerService::create($this->vendor->id, $request->exam_name, $request->quantity);

        if ($response->status) {
            $transactionResource = new ResultCheckerResource(ResultCheckerTransaction::find($response->response->id));
            return ApiHelper::sendResponse($transactionResource, $response->message);
        }

        $user = Auth::user();

        $subject = ($response->status) ? "Exam Token Purchase Successful" : "Bill Purchase Failed";

        $this->notificationService->sendToUser($user, $subject, '');

        return $response;
    }
}
