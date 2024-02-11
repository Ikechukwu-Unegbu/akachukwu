<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Show extends Component
{
    public $vendor;
    public $balance;

    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->balance = $this->getVendorAccountBalance();
        $this->authorize('view vendor api');
    }

    public function getVendorAccountBalance()
    {
        try {

            $url = "{$this->vendor->api}/user/";

            $headers = [
                'Authorization' => "Token {$this->vendor->token}" ,
                'Content-Type' => 'application/json',
            ];

            $clientGet = Http::withHeaders($headers)->get($url);
            $clientPost = Http::withHeaders($headers)->post($url);

            if ($clientGet->ok()) {
                $response = $clientGet->object();
            }

            if ($clientPost->ok()) {
                $response = $clientPost->object();
            }

            return isset($response->user->Account_Balance) ? '₦ ' . number_format($response->user->Account_Balance, 2) : 'N/A';

            
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    public function render()
    {
        return view('livewire.admin.api.vendor.show');
    }
}
