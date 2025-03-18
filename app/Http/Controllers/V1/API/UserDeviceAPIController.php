<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserDeviceRepository;
use Illuminate\Support\Facades\Response;

class UserDeviceAPIController extends Controller
{
    public $userDeviceRepository;

    public function __construct(UserDeviceRepository $userDeviceRepository)
    {
        $this->userDeviceRepository = $userDeviceRepository;
    }

    public function registerDevice(Request $request)
    {
        $this->userDeviceRepository->updateOrCreate($request->all());

        return $this->sendSuccess('The device has been registered successfully.');
    }

    public function updateNotificationStatus($playerId)
    {
        $this->userDeviceRepository->updateStatus($playerId);

        return $this->sendSuccess('The notification status has been updated successfully.');
    }

    private function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}