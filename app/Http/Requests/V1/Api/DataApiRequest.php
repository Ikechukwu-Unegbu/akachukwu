<?php

namespace App\Http\Requests\V1\Api;

use App\Models\Data\DataPlan;
use App\Services\Account\AccountBalanceService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DataApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'network_id'        =>  'required|integer',
            'data_type_id'      =>  'required|integer',
            'plan_id'           =>  'required|integer',
            'phone_number'      =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();

            $plan = DataPlan::find($this->plan_id); // Fetch the plan using submitted plan_id
            if (!$plan) {
                $validator->errors()->add('plan_id', 'Invalid plan selected.');
                return;
            }

            $accountBalanceService = new AccountBalanceService($user);

            if (!$accountBalanceService->verifyAccountBalance($plan->amount)) {
                $validator->errors()->add('plan_id', 'Insufficient balance for this transaction.');
            }
        });
    }
}
