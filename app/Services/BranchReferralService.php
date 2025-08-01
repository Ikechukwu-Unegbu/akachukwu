<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service class responsible for generating Branch.io referral links
 * tied to a user's username during registration.
 */
class BranchReferralService
{
    /**
     * The Branch.io API key retrieved from the services config.
     *
     * @var string
     */
    protected $branchKey;

    /**
     * The URL endpoint for Branch.io link creation.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * BranchReferralService constructor.
     * Loads configuration for Branch.io integration.
     */
    public function __construct()
    {
        $this->branchKey = config('services.branch.key');
        $this->baseUrl = config('services.branch.url', 'https://api2.branch.io/v1/url');
    }

    /**
     * Generates a unique referral link for a given username.
     *
     * @param string $username The username to bind to the referral link
     * @return string|null Returns the Branch referral URL or null on failure
     */
    public function createReferralLink(string $username): ?string
    {
        // Define payload to send to Branch.io API
        $payload = [
            'branch_key' => $this->branchKey,
            'campaign' => 'user_referral',
            'channel' => 'app',
            'feature' => 'referral',
            'stage' => 'new_user',
            'data' => [
                // Destination URLs depending on platform
                '$desktop_url' => 'https://vastel.io/register?ref=' . $username,
                // '$ios_url' => 'yourapp://referral?user=' . $username,
                '$android_url' => 'vastel://referral?user=' . $username,
                
                // Custom data to track referrer
                'referrer' => $username,
            ],
        ];

        try {
            // Send POST request to Branch.io to create the referral link
            $response = Http::post($this->baseUrl, $payload);

            // If the request was successful, return the generated link
            if ($response->successful()) {
                return $response->json()['url'];
            }

            // Log the failure details for debugging
            Log::error('Branch link creation failed', [
                'username' => $username,
                'response' => $response->body()
            ]);
        } catch (\Exception $e) {
            // Log any exception thrown during the request
            Log::error('Branch link error: ' . $e->getMessage());
        }

        // Return null if something went wrong
        return null;
    }
}