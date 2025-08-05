<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\BranchReferralService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateBranchReferralLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'branch:generate-referral-links {--user= : Generate for specific user by email/username} {--force : Force regenerate even if link exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Branch referral links for all users or a specific user';

    /**
     * The Branch referral service instance.
     *
     * @var BranchReferralService
     */
    protected $branchService;

    /**
     * Create a new command instance.
     */
    public function __construct(BranchReferralService $branchService)
    {
        parent::__construct();
        $this->branchService = $branchService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting Branch referral link generation...');

            if ($this->option('user')) {
                $this->generateForSpecificUser();
            } else {
                $this->generateForAllUsers();
            }

            $this->info('Branch referral link generation completed successfully!');
        } catch (\Exception $e) {
            $this->error('Error generating Branch referral links: ' . $e->getMessage());
            Log::error('Branch referral link generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Generate referral link for a specific user.
     */
    protected function generateForSpecificUser()
    {
        $userIdentifier = $this->option('user');
        $force = $this->option('force');

        $user = User::where('email', $userIdentifier)
            ->orWhere('username', $userIdentifier)
            ->first();

        if (!$user) {
            throw new \Exception("User with email/username '{$userIdentifier}' not found.");
        }

        $this->info("Processing user: {$user->name} ({$user->username})");

        if ($user->referral_link && !$force) {
            $this->warn("User already has a referral link. Use --force to regenerate.");
            $this->line("Current link: {$user->referral_link}");
            return;
        }

        $this->generateReferralLink($user);
    }

        /**
     * Generate referral links for all users.
     */
    protected function generateForAllUsers()
    {
        $force = $this->option('force');
        
        $query = User::query();
        
        if (!$force) {
            $query->whereNull('referral_link');
        }

        $users = $query->get();
        $totalUsers = $users->count();

        if ($totalUsers === 0) {
            $this->info('No users found that need referral links.');
            return;
        }

        $this->info("Found {$totalUsers} users to process.");
        $this->info("Rate limiting: 100 requests per second (Branch.io limit)");
        
        $progressBar = $this->output->createProgressBar($totalUsers);
        $progressBar->start();

        $successCount = 0;
        $errorCount = 0;
        $requestCount = 0;
        $batchStartTime = microtime(true);

        foreach ($users as $user) {
            try {
                // Rate limiting: Ensure we don't exceed 100 requests per second
                $this->enforceRateLimit($requestCount, $batchStartTime);
                
                $this->generateReferralLink($user);
                $successCount++;
                $requestCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $this->newLine();
                $this->error("Failed to generate link for {$user->username}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Generation completed:");
        $this->line("✅ Successfully generated: {$successCount}");
        $this->line("❌ Failed: {$errorCount}");
        $this->line("⏱️  Total requests made: {$requestCount}");
    }

    /**
     * Enforce rate limiting to respect Branch.io's 100 requests per second limit.
     *
     * @param int $requestCount
     * @param float $batchStartTime
     * @return void
     */
    protected function enforceRateLimit(&$requestCount, &$batchStartTime)
    {
        $currentTime = microtime(true);
        $elapsedTime = $currentTime - $batchStartTime;
        
        // If we've made 100 requests in less than 1 second, wait
        if ($requestCount > 0 && $requestCount % 100 === 0) {
            $timeFor100Requests = $elapsedTime;
            
            if ($timeFor100Requests < 1.0) {
                $sleepTime = 1.0 - $timeFor100Requests;
                $this->newLine();
                $this->line("Rate limit reached. Waiting " . round($sleepTime, 2) . " seconds...");
                usleep($sleepTime * 1000000); // Convert to microseconds
            }
            
            // Reset timer for next batch
            $batchStartTime = microtime(true);
        }
    }

    /**
     * Generate referral link for a single user.
     *
     * @param User $user
     * @return void
     */
    protected function generateReferralLink(User $user)
    {
        $this->line("Generating referral link for {$user->username}...");

        $referralLink = $this->branchService->createReferralLink($user->username);

        if (!$referralLink) {
            throw new \Exception("Failed to generate Branch referral link for user {$user->username}");
        }

        $user->update(['referral_link' => $referralLink]);

        $this->line("✅ Generated: {$referralLink}");
    }
}
