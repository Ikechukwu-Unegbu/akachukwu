<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Account\UserPinService;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\V1\Api\ResetUserPinRequest;
use App\Http\Requests\V1\Api\CreateUserPinRequest;
use App\Http\Requests\V1\Api\UpdateUserPinRequest;

class UserPinController extends Controller
{
    public function create(CreateUserPinRequest $request)
    {
        $request->validated();

        try {

            $pinService = UserPinService::createPin($request->pin, $request->pin_confirmation);

            if ($pinService) {
                return response()->json([
                    'status'   =>    true,
                    'error'    =>    NULL,
                    'message'  =>    "PIN created successfully.",
                ], 200)->getData();
            }
            
        } catch (\Throwable $th) {
            return response()->json([
                'status'  =>   false, 
                'message' =>  "Unable to create PIN. Try again later.",
                'error'   =>  $th->getMessage()
            ], 422);
        }
    }

    public function validatePin(Request $request)
    {
        $request->validate([
            'pin'   =>  'required|numeric|digits:4'
        ]);

        $pinService = UserPinService::validatePin(Auth::user(), $request->pin);

        if ($pinService) {
            return response()->json([
                'status'   =>    true,
                'message'  =>    "PIN validated successfully.",
            ], 200)->getData();
        }

        if (!$pinService) {
            throw ValidationException::withMessages([
                'pin' => __('The PIN provided is incorrect. Provide a valid PIN.'),
            ]);
        }
    }

    public function update(UpdateUserPinRequest $request)
    {
        $request->validated();

        try {

            $pinService = UserPinService::updatePinWithCurrent($request->current_pin, $request->pin, $request->pin_confirmation);

            if ($pinService) {
                return response()->json([
                    'status'   =>    true,
                    'error'    =>    NULL,
                    'message'  =>    "PIN updated successfully.",
                ], 200)->getData();
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status'  =>   false, 
                'message' =>  "Unable to update PIN. Try again later.",
                'error'   =>  $th->getMessage()
            ], 422);
        }
    }

    public function resetPin(ResetUserPinRequest $request)
    {
        $resetPinService = UserPinService::resetPin($request->validated());

        if (!$resetPinService) {
            return ApiHelper::sendResponse(['Reset PIN failed'], $resetPinService['current_password']);
        }

        return ApiHelper::sendResponse([], 'PIN reset successfully');
    }
}
