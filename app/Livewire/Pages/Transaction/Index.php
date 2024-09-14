<?php

namespace App\Livewire\Pages\Transaction;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'Tailwind';

    public $perPage = 50;
    public $selectedDate;
    public $service;
    public $services = ['airtime', 'data', 'cable', 'electricity', 'funding'];

    public function mount()
    {
        $this->selectedDate = date('Y-m');
    }

    public function render()
    {
        // Get the current date
        $now = Carbon::now();

        // Get the current year
        $year = $now->year;

        // Get the current month number
        $currentMonth = $now->month;

        // Generate months from the current month to January
        $months = [];
        for ($i = $currentMonth; $i >= 1; $i--) {
            $months[] = Carbon::createFromFormat('!m', $i)->format('F'); // Month name
        }

        return view('livewire.pages.transaction.index', [
            'transactions'  => auth()->user()->transactionHistories($this->perPage, $this->service, $this->selectedDate),
            'months'        =>  $months,
            'year'          =>  $year,
        ]);
    }
}
