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
        $this->authorize('can top-up and debit');
        // $this->authorize('can debit');
        $this->username = $request->query('username');

        $this->user = User::where('username', $this->username)->firstOrFail();
    }
    public function render()

    {
        return view('livewire.admin.crd-dbt.create', [
            'walletHistories' => $this->user->walletHistories()->get()->take(10)
        ]);
    }
}
