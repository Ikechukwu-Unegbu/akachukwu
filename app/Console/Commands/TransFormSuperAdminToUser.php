<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TransFormSuperAdminToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command To Transform SuperAdmin To User';

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
        $this->email = $this->argument('email') ?? $this->ask('Enter the email or username of the superadmin you want to transform to user');

        if (! $this->email)
            throw new \Exception('Email is required');

        if (! \App\Models\User::where('email', $this->email)->orWhere('username', $this->email)->exists())
            throw new \Exception('User does not exist');
    }

    public function verifyUserEligibility()
    {
        $user = \App\Models\User::where('email', $this->email)->orWhere('username', $this->email)->first();

        if ($user->role == 'user') throw new \Exception('User already been transformed to user');
    }

    private function transformUser()
    {
        $user = \App\Models\User::where('email', $this->email)->orWhere('username', $this->email)->first();

        $user->transformFromSuperAdminToUser();

        $this->info('Superamin has been transformed to user');
    }
}
