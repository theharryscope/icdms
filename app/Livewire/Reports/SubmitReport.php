<?php

namespace App\Livewire\Reports;

use App\Models\Report;
use Livewire\Component;

class SubmitReport extends Component
{
    public $report_type = 'general';
    public $subject;
    public $details;

    public function submit(): void
    {
        $this->validate([
            'report_type' => 'required|in:general,field_activity,beneficiary,project,incident',
            'subject' => 'required|string|max:255',
            'details' => 'required|string|min:10|max:10000',
        ]);

        Report::create([
            'user_id' => auth()->id(),
            'report_type' => $this->report_type,
            'subject' => $this->subject,
            'details' => $this->details,
        ]);

        $this->reset(['subject', 'details']);
        $this->report_type = 'general';
        session()->flash('message', 'Report sent to the administrator successfully.');
    }

    public function render()
    {
        return view('livewire.reports.submit-report')->layout('layouts.app');
    }
}