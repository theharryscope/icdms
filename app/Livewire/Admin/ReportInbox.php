<?php

namespace App\Livewire\Admin;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;

class ReportInbox extends Component
{
    use WithPagination;

    public function markReviewed(int $reportId): void
    {
        Report::whereKey($reportId)->update([
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);

        session()->flash('message', 'Report marked as reviewed.');
    }

    public function render()
    {
        return view('livewire.admin.report-inbox', [
            'reports' => Report::with('author')->latest()->paginate(10),
            'pendingCount' => Report::where('status', 'pending')->count(),
        ])->layout('layouts.app');
    }
}