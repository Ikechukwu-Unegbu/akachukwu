<?php

namespace App\Livewire\Admin\Activities;

use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public $startDate;
    public $endDate;

    public function  mount(Request $request)
    {
        $this->startDate = $request->input('start_date');
        $this->endDate = $request->input('end_date');
    }
    
    public function render()
    {
        $querylogs  = Activity::with(['subject', 'causer'])
                ->when($this->search, function($query, $search) {
                $query->where('description', 'LIKE', "%{$search}%")
                ->orWhereHas('subject', function ($subjectQuery) use ($search) {
                    $subjectQuery->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
                })->orWhereHas('causer', function ($causerQuery) use ($search) {
                    $causerQuery->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
                });
            });

        if ($this->startDate) {
            $querylogs->where('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $querylogs->where('created_at', '<=', $this->endDate);
        }

        $activity_logs = $querylogs->latest()->paginate($this->perPage);

        return view('livewire.admin.activities.index', compact('activity_logs'));
    }
}