<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\KycVerificationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class KycVerificationController extends Controller
{
    protected $kycService;

    public function __construct(KycVerificationService $kycService)
    {
        $this->kycService = $kycService;
    }

    /**
     * Submit Tier 1 verification (Personal Information + BVN/NIN)
     */
    public function submitTier1(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'gender' => 'required|in:male,female,other',
                'identification_type' => 'required|in:bvn,nin',
                'identification_number' => 'required|string|max:20',
                'date_of_birth' => 'required_if:identification_type,nin|date|before:today',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->kycService->processTier1Verification(
                Auth::user(),
                $request->all()
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while processing Tier 1 verification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit Tier 2 verification (Address Information + Next of Kin)
     */
    public function submitTier2(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // Address Information
                'house_number' => 'required|string|max:50',
                'street_address' => 'required|string|max:500',
                'local_government' => 'required|string|max:100',
                'area_code' => 'required|string|max:20',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',

                // Next of Kin
                'next_of_kin_relationship' => 'required|string|max:50',
                'next_of_kin_first_name' => 'required|string|max:255',
                'next_of_kin_last_name' => 'required|string|max:255',
                'next_of_kin_email' => 'required|email|max:255',
                'next_of_kin_phone' => 'required|string|max:20',
                'next_of_kin_gender' => 'required|in:male,female,other',

                // Utility Bill
                'utility_bill_type' => 'required|in:electricity,waste_water,rent_tenancy,internet,other',
                'utility_bill_file' => 'required|file|mimes:svg,png,jpg,jpeg,gif|max:5120', // 5MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->kycService->processTier2Verification(
                Auth::user(),
                $request->all()
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while processing Tier 2 verification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit Tier 3 verification (Government ID + BVN/NIN)
     */
    public function submitTier3(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_type' => 'required|in:national_id,voters_card,drivers_license,international_passport',
                'id_file' => 'required|file|mimes:svg,png,jpg,jpeg,gif|max:5120', // 5MB max
                'bvn_number' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->kycService->processTier3Verification(
                Auth::user(),
                $request->all()
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while processing Tier 3 verification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's current verification status
     */
    public function getVerificationStatus()
    {
        try {
            $user = Auth::user();

            $status = $this->kycService->getUserVerificationStatus($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Verification status retrieved successfully',
                'data' => $status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while retrieving verification status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload document file
     */
    public function uploadDocument(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'document_type' => 'required|in:utility_bill,government_id',
                'file' => 'required|file|mimes:svg,png,jpg,jpeg,gif|max:5120', // 5MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->kycService->uploadDocument(
                Auth::user(),
                $request->file('file'),
                $request->document_type
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while uploading document',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}