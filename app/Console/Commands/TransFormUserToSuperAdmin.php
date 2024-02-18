<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TransFormUserToSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:super-admin {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command To Transform User to SuperAdmin';

    /**
     * Execute the console command.
     */
    protected $email;


    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $this->handleInput();
            $this->verifyUserEligibility();
            $this->transformUser();

        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }

    }

    public function handleInput()
    {
        $this->email = $this->argument('email') ?? $this->ask('Enter the email or username of the user you want to transform to super admin');

        if (! $this->email)
            throw new \Exception('Email is required');

        if (! \App\Models\User::where('email', $this->email)->orWhere('username', $this->email)->exists())
            throw new \Exception('User does not exist');
    }

    public function verifyUserEligibility()
    {
        $user = \App\Models\User::where('email', $this->email)->orWhere('username', $this->email)->first();

        if ($user->role == 'superadmin') throw new \Exception('User is already a super admin');
    }

    private function transformUser()
    {
        $user = \App\Models\User::where('email', $this->email)->orWhere('username', $this->email)->first();

        $user->assignSuperAdminRole();

        $this->info('User has been transformed to super admin');
    }
}
