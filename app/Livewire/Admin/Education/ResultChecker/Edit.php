<?php

namespace App\Livewire\Admin\Education\ResultChecker;

use App\Models\Education\ResultChecker;
use App\Models\Vendor;
use Livewire\Component;

class Edit extends Component
{
    public $exam;
    public $vendor;
    public $exam_name;
    public $amount;
    public $status;

    public function mount(ResultChecker $exam, Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->exam = $exam;

        $this->exam_name = $this->exam->name;
        $this->amount = $this->exam->amount;
        $this->status = $this->exam->status ? true : false;
    }

    public function update()
    {
        $validated = $this->validate([
            'amount' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        $this->exam->update($validated);

        $this->dispatch('success-toastr', ['message' => 'Exam Updated Successfully']);
        session()->flash('success', 'Exam Updated Successfully');
        return redirect()->to(route('admin.education.result-checker'));
    }

    public function render()
    {
        return view('livewire.admin.education.result-checker.edit');
    }
}
