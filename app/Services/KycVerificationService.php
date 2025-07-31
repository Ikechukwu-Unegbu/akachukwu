<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\Payment\MonnifyService;

class KycVerificationService
{
    /**
     * Process Tier 1 verification (Personal Information + BVN/NIN)
     */
    public function processTier1Verification(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            // Update user's personal information
            $user->update([
                // 'name' => $data['first_name'] . ' ' . $data['last_name'],
                'phone' => $data['phone_number'],
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
            ]);

            // Handle identification (BVN or NIN)
            if ($data['identification_type'] === 'bvn') {
                $bvnVerification = (array) MonnifyService::verifyBvn($data['identification_number'], NULL, $user->phone);

                if ($bvnVerification['status']) {
                    $user->update([
                        'bvn' => $data['identification_number'],
                        'nin' => null,
                    ]);
                }
            }
            if ($data['identification_type'] === 'nin') {
                $ninVerification = (array) MonnifyService::verifyNin($data['identification_number'], $data['date_of_birth'] ?? null, $user->phone);

                if ($ninVerification['status']) {
                    $user->update([
                        'nin' => $data['identification_number'],
                        'bvn' => null,
                    ]);
                }
            }

            // Mark Tier 1 as completed
            $user->update([
                'tier_1_completed' => true,
                'tier_1_completed_at' => now(),
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Tier 1 verification completed successfully',
                'data' => [
                    'tier' => 1,
                    'completed' => true,
                    'next_tier' => 2,
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tier 1 verification failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process Tier 2 verification (Address Information + Next of Kin + Utility Bill)
     */
    public function processTier2Verification(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            // Check if Tier 1 is completed
            if (!$user->tier_1_completed) {
                throw new \Exception('Tier 1 verification must be completed first');
            }

            // Update user's address information
            $user->update([
                'house_number' => $data['house_number'],
                'street_address' => $data['street_address'],
                'local_government' => $data['local_government'],
                'area_code' => $data['area_code'],
                'city' => $data['city'],
                'state' => $data['state'],
                'address' => $data['street_address'], // Update existing 'address' field with street address
            ]);

            // Update next of kin information
            $user->update([
                'next_of_kin_relationship' => $data['next_of_kin_relationship'],
                'next_of_kin_first_name' => $data['next_of_kin_first_name'],
                'next_of_kin_last_name' => $data['next_of_kin_last_name'],
                'next_of_kin_email' => $data['next_of_kin_email'],
                'next_of_kin_phone' => $data['next_of_kin_phone'],
                'next_of_kin_gender' => $data['next_of_kin_gender'],
            ]);

            // Handle utility bill upload
            if (isset($data['utility_bill_file'])) {
                $utilityBillPath = $this->uploadDocumentFile(
                    $data['utility_bill_file'],
                    'utility_bills',
                    $user->id
                );

                $user->update([
                    'utility_bill_type' => $data['utility_bill_type'],
                    'utility_bill_path' => $utilityBillPath,
                ]);
            }

            // Mark Tier 2 as completed
            $user->update([
                'tier_2_completed' => true,
                'tier_2_completed_at' => now(),
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Tier 2 verification completed successfully',
                'data' => [
                    'tier' => 2,
                    'completed' => true,
                    'next_tier' => 3,
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tier 2 verification failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process Tier 3 verification (Government ID + BVN/NIN)
     */
    public function processTier3Verification(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            // Check if Tier 2 is completed
            if (!$user->tier_2_completed) {
                throw new \Exception('Tier 2 verification must be completed first');
            }

            // Handle government ID upload
            if (isset($data['id_file'])) {
                $idPath = $this->uploadDocumentFile(
                    $data['id_file'],
                    'government_ids',
                    $user->id
                );

                $user->update([
                    'government_id_type' => $data['id_type'],
                    'government_id_path' => $idPath,
                ]);
            }

            // Update BVN if provided
            if (isset($data['bvn_number'])) {
                $user->update([
                    'bvn' => $data['bvn_number'],
                ]);
            }

            // Mark Tier 3 as completed and unlock full access
            $user->update([
                'tier_3_completed' => true,
                'tier_3_completed_at' => now(),
                'kyc_completed' => true,
                'kyc_completed_at' => now(),
                'user_level' => 'verified', // Upgrade user level
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Tier 3 verification completed successfully. Full access unlocked!',
                'data' => [
                    'tier' => 3,
                    'completed' => true,
                    'kyc_completed' => true,
                    'user_level' => 'verified',
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tier 3 verification failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get user's current verification status
     */
    public function getUserVerificationStatus(User $user)
    {
        return [
            'tier_1' => [
                'completed' => (bool) $user->tier_1_completed,
                'completed_at' => $user->tier_1_completed_at,
                'requirements' => [
                    'personal_info' => !empty($user->name) && !empty($user->phone),
                    'identification' => !empty($user->bvn) || !empty($user->nin),
                ]
            ],
            'tier_2' => [
                'completed' => (bool) $user->tier_2_completed,
                'completed_at' => $user->tier_2_completed_at,
                'requirements' => [
                    'address_info' => !empty($user->street_address) && !empty($user->state),
                    'next_of_kin' => !empty($user->next_of_kin_first_name),
                    'utility_bill' => !empty($user->utility_bill_path),
                ]
            ],
            'tier_3' => [
                'completed' => (bool) $user->tier_3_completed,
                'completed_at' => $user->tier_3_completed_at,
                'requirements' => [
                    'government_id' => !empty($user->government_id_path),
                    'bvn_verification' => !empty($user->bvn),
                ]
            ],
            'overall_status' => [
                'kyc_completed' => (bool) $user->kyc_completed,
                'current_tier' => $this->getCurrentTier($user),
                'user_level' => $user->user_level ?? 'basic',
            ]
        ];
    }

    /**
     * Upload document file
     */
    public function uploadDocument(User $user, $file, string $documentType)
    {
        try {
            $path = $this->uploadDocumentFile($file, $documentType, $user->id);

            return [
                'status' => 'success',
                'message' => 'Document uploaded successfully',
                'data' => [
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Document upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload document file to storage
     */
    private function uploadDocumentFile($file, string $folder, int $userId)
    {
        $environment = env('APP_ENV') === 'production' ? 'production' : 'staging';
        $path = $environment . '/kyc/' . $folder . '/' . $userId;

        $filePath = Storage::disk('do')->put($path, $file, 'public');

        return env('DO_CDN') . '/' . $filePath;
    }

    /**
     * Get current tier based on completion status
     */
    private function getCurrentTier(User $user): int
    {
        if ($user->tier_3_completed) {
            return 3;
        } elseif ($user->tier_2_completed) {
            return 2;
        } elseif ($user->tier_1_completed) {
            return 1;
        }

        return 0;
    }
}
