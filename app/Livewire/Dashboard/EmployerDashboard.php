<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Employer;
use App\Models\StudentPlacement;

class EmployerDashboard extends Component
{
    public $selectedPlacementId = null;
    public $performance_rating = 5;
    public $employer_feedback;
    public $showFeedbackModal = false;

    public function openFeedbackModal($placementId)
    {
        $employer = Employer::where('user_id', auth()->id())->firstOrFail();

        // Scope strictly to the logged-in employer's company placements
        $placement = StudentPlacement::where('employer_id', $employer->id)
            ->where('id', $placementId)
            ->firstOrFail();

        $this->selectedPlacementId = $placement->id;
        $this->performance_rating = $placement->performance_rating ?? 5;
        $this->employer_feedback = $placement->employer_feedback;
        $this->showFeedbackModal = true;
    }

    public function submitFeedback()
    {
        $this->validate([
            'performance_rating' => 'required|integer|between:1,5',
            'employer_feedback' => 'required|string|min:10',
        ]);

        $employer = Employer::where('user_id', auth()->id())->firstOrFail();

        // Ensure update applies strictly to authorized placement record
        $placement = StudentPlacement::where('employer_id', $employer->id)
            ->where('id', $this->selectedPlacementId)
            ->firstOrFail();

        $placement->update([
            'performance_rating' => $this->performance_rating,
            'employer_feedback' => $this->employer_feedback,
            'feedback_submitted_at' => now(),
        ]);

        $this->showFeedbackModal = false;
        $this->reset(['selectedPlacementId', 'employer_feedback']);
        session()->flash('message', 'Student evaluation feedback submitted to InnoTech Admin.');
    }

    public function render()
    {
        $user = auth()->user();
        $employer = Employer::where('user_id', $user->id)->first();

        $placements = $employer 
            ? StudentPlacement::with('student')->where('employer_id', $employer->id)->latest()->get()
            : collect();

        return view('livewire.dashboard.employer-dashboard', [
            'employer' => $employer,
            'placements' => $placements,
            'totalPlaced' => $placements->count(),
            'activePlaced' => $placements->where('employment_status', 'active')->count(),
        ])->layout('layouts.app');
    }
}