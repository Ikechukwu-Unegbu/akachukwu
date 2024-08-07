<?php

namespace App\Console\Commands;

use App\Models\Education\ResultCheckerTransaction;
use App\Models\User;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

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

    public $user;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // try{

            $this->handleInput();
          
            $this->createFakeTransactionRecords();
        // }catch(\Throwable $th){
        //     $this->error($th->getMessage());
        // }
    }

    // public function handleInput()
    // {
    //     $this->email = $this->argument('email') ?? $this->ask('Enter the email or username of the user you want to transform to super admin');

    //     if (! $this->email)
    //         throw new \Exception('Email is required');

    //     if (! \App\Models\User::where('email', $this->email)->orWhere('username', $this->email)->exists())
    //         throw new \Exception('User does not exist');
    // }
    protected function handleInput()
    {
        $this->email = $this->argument('email') ?? $this->ask('Enter the email or username of the user you want to create fake transactions for');

        if (!$this->email) {
            throw new \Exception('Email or username is required');
        }

        $this->user = User::where('email', $this->email)->first();
        Auth::login($this->user);

        if (!$this->user) {
            throw new \Exception('User does not exist');
        }
    }

    public function createFakeTransactionRecords()
    {
        ResultCheckerTransaction::factory()->count(10)->create([
            'user_id' => $this->user->id,
        ]);

        ElectricityTransaction::factory()->count(10)->create([
            'user_id' => $this->user->id,
        ]);

        CableTransaction::factory()->count(10)->create([
            'user_id' => $this->user->id,
        ]);

        $this->info('Transactions assigned');
    }


}
