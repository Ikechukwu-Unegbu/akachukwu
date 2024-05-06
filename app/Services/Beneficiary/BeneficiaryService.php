<?php

namespace App\Services\Beneficiary;

use App\Models\Beneficiary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BeneficiaryService
{

    public static function create($beneficiary, $type, $meta)
    {
        $attributes = [
            'user_id'       =>  Auth::id(), 
            'beneficiary'   =>  $beneficiary, 
            'type'          =>  $type
        ];

        $meta_data = ['meta_data' => $meta];

        Beneficiary::updateOrCreate($attributes, $meta_data);

        return true;
    }

    public static function get($type)
    {
        $beneficiary = Beneficiary::whereUserId(Auth::id())->whereType($type)->get();
        return $beneficiary;
    }
}
