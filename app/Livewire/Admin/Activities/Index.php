<?php

namespace App\Livewire\Admin\Activities;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;
    
    public function render()
    {
        return view('livewire.admin.activities.index', [
            'activity_logs' => Activity::with('causer')
                ->when($this->search, function($query, $search) {
                $query->where('description', 'LIKE', "%{$search}%")
                ->orWhereHas('causer', function ($causerQuery) use ($search) {
                    $causerQuery->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
                });
            })->latest()->paginate($this->perPage)
        ]);
    }
}
