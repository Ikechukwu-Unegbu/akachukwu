<?php

namespace App\Http\Resources\V1\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultCheckerResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transaction_id'   =>  $this->transaction_id,
            'exam_name'        =>  $this->exam_name,
            'quantity'         =>  $this->quantity,
            'amount'           =>  $this->amount,
            'balance_before'   =>  $this->balance_before,
            'balance_after'    =>  $this->balance_after,
            'purchase_codes'   =>  $this->result_checker_pins
        ];
    }
}
