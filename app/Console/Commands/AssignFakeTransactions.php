<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssignFakeTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:fake-trx {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public $email;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $this->handleInput();
        }catch(\Throwable $th){
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

    public function createFakeTransactionRecords()
    {

    }
    

}
