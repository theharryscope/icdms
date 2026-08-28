<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\Beneficiary;
use App\Models\Community;
use App\Models\LocalGovernment;
use App\Models\FieldActivityLog;

class VolunteerDashboard extends Component
{
    use WithPagination;

    // Quick Activity Log Form Fields
    public $activity_title, $community_id, $hours_spent, $teaching_topic, $attendees_count, $activity_notes;
    public $showLogModal = false;

    public function openLogModal()
    {
        $this->showLogModal = true;
    }

    public function closeLogModal()
    {
        $this->showLogModal = false;
        $this->reset(['activity_title', 'community_id', 'hours_spent', 'teaching_topic', 'attendees_count', 'activity_notes']);
    }

    public function submitActivityLog()
    {
        $this->validate([
            'activity_title' => 'required|string|max:255',
            'community_id' => 'required|exists:communities,id',
            'hours_spent' => 'required|numeric|min:0.5',
            'teaching_topic' => 'nullable|string|max:255',
            'attendees_count' => 'nullable|integer|min:0',
            'activity_notes' => 'required|string|min:10',
        ]);

        FieldActivityLog::create([
            'user_id' => auth()->id(),
            'community_id' => $this->community_id,
            'activity_title' => $this->activity_title,
            'hours_spent' => $this->hours_spent,
            'teaching_topic' => $this->teaching_topic,
            'attendees_count' => $this->attendees_count ?? 0,
            'activity_notes' => $this->activity_notes,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Teaching & service report submitted successfully! Sent to your State Coordinator for verification.');

        $this->closeLogModal();
    }

    public function render()
    {
        $user = auth()->user();

        // Scope to Volunteer's LGA / Community area
        $assignedLgaName = $user->localGovernment->name ?? null;

        $communityQuery = Community::query();
        if ($assignedLgaName) {
            $communityQuery->where('lga', $assignedLgaName);
        }

        $communityIds = (clone $communityQuery)->pluck('id');

        $activeTechProjects = Project::whereIn('community_id', $communityIds)
            ->where('status', 'in_progress')
            ->latest()
            ->take(4)
            ->get();

        $enrolledStudents = Beneficiary::whereIn('community_id', $communityIds)->count();

        // Retrieve volunteer's personal logged activity metrics & history
        $loggedActivities = FieldActivityLog::with('community')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5);

        $totalLoggedHours = FieldActivityLog::where('user_id', $user->id)
            ->where('status', 'verified')
            ->sum('hours_spent');

        $totalSessionsCount = FieldActivityLog::where('user_id', $user->id)->count();

        return view('livewire.dashboard.volunteer-dashboard', [
            'volunteer' => $user,
            'assignedLga' => $assignedLgaName ?? 'Unassigned LGA',
            'assignedState' => $user->zoneState->name ?? 'N/A',
            'totalStudentsTaught' => $enrolledStudents,
            'totalLoggedHours' => $totalLoggedHours,
            'totalSessionsCount' => $totalSessionsCount,
            'activeTechProjects' => $activeTechProjects,
            'assignedCommunities' => (clone $communityQuery)->get(),
            'loggedActivities' => $loggedActivities,
        ])->layout('layouts.app');
    }
}