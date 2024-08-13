<?php

namespace App\Livewire\Admin\CrdDbt;

use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;

class Create extends Component
{
    public $username;
    public $user;

    public function mount(Request $request)
    {
        if(auth()->user()->can('debit') || auth()->user()->can('credit')){
            $this->username = $request->query('username');

            $this->user = User::where('username', $this->username)->firstOrFail();
        }else{
            abort(403);
        }
        
    }
    public function render()

    {
        return view('livewire.admin.crd-dbt.create', [
            'walletHistories' => $this->user->walletHistories()->get()->take(10)
        ]);
    }
}
