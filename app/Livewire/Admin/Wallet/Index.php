<?php

namespace App\Livewire\Admin\Wallet;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 20;
    public $perPages = [20, 50];
    public $search;
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.admin.wallet.index', [
            'walletHistories' => $this->user->checkUserTransactionHistories($this->perPage, $this->user->id)
        ]);
    }
}
