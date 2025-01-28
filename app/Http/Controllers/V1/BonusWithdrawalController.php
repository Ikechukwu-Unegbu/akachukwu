<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment\VastelTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusWithdrawalController extends Controller
{
    public function __invoke(Request $request)
    {
        DB::transaction(function () use ($request) {
            $user = DB::table('users')->where('id', $request->user()->id)->lockForUpdate()->first();

            if ($user->bonus_balance > 0) {
                VastelTransaction::create([
                    'reference_id' => 'VST-'.random_int(100000000000, 999999999999),
                    'amount'=>$user->bonus_balance,
                    'type'=>1,
                    'currency'=>"NGN",
                    // 'status'=>true,
                    // 'api_status'=>'successful',
                    'user_id'=>$user->id, 
                    'admin_id'=>0,
                    'description'=>'Bonus withdrawal'
                ]);
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'bonus_balance' => 0,
                        'account_balance' => DB::raw("account_balance + {$user->bonus_balance}")
                    ]);
                 
            } else {
                return redirect()->route('settings.referral', ['modal' => 'successful']);

            }
        });

        return redirect()->route('settings.referral', ['modal' => 'successful']);

    }
}
