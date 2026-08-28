<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Project;
use App\Models\Beneficiary;
use App\Models\Community;
use App\Models\LocalGovernment;
use App\Models\ZoneState;
use App\Models\FieldActivityLog;

class CoordinatorDashboard extends Component
{
    public $confirmingLogId = null;
    public $coordinator_remarks = '';

    // --- REPORT VERIFICATION ACTIONS ---
    public function verifyReport($logId)
    {
        $log = FieldActivityLog::findOrFail($logId);
        $log->update([
            'status' => 'verified',
            'coordinator_remarks' => $this->coordinator_remarks,
        ]);

        $this->confirmingLogId = null;
        $this->coordinator_remarks = '';
        session()->flash('message', 'Volunteer field report verified successfully.');
    }

    public function rejectReport($logId)
    {
        $log = FieldActivityLog::findOrFail($logId);
        $log->update([
            'status' => 'rejected',
            'coordinator_remarks' => $this->coordinator_remarks,
        ]);

        $this->confirmingLogId = null;
        $this->coordinator_remarks = '';
        session()->flash('message', 'Volunteer field report marked as rejected.');
    }

    public function render()
    {
        $user = auth()->user();

        $projectQuery = Project::query();
        $beneficiaryQuery = Beneficiary::query();
        $communityQuery = Community::query();
        $activityLogQuery = FieldActivityLog::with(['volunteer', 'community']);

        $jurisdictionTitle = 'Unassigned Scope';

        // 1. LGA Coordinator Scope
        if ($user->local_government_id) {
            $lga = LocalGovernment::find($user->local_government_id);
            $lgaName = $lga->name ?? '';
            $jurisdictionTitle = "{$lgaName} LGA Jurisdiction";

            $communityIds = Community::where('lga', $lgaName)->pluck('id');

            $projectQuery->whereIn('community_id', $communityIds);
            $beneficiaryQuery->whereIn('community_id', $communityIds);
            $communityQuery->where('lga', $lgaName);
            $activityLogQuery->whereIn('community_id', $communityIds);
        }
        // 2. State Coordinator Scope
        elseif ($user->zone_state_id) {
            $state = ZoneState::find($user->zone_state_id);
            $stateName = $state->name ?? '';
            $jurisdictionTitle = "{$stateName} State Command";

            $lgaNames = LocalGovernment::where('zone_state_id', $user->zone_state_id)->pluck('name');
            $communityIds = Community::whereIn('lga', $lgaNames)->pluck('id');

            $projectQuery->whereIn('community_id', $communityIds);
            $beneficiaryQuery->whereIn('community_id', $communityIds);
            $communityQuery->whereIn('lga', $lgaNames);
            $activityLogQuery->whereIn('community_id', $communityIds);
        }
        // 3. Zonal Coordinator Scope
        elseif ($user->zone_id) {
            $jurisdictionTitle = "{$user->zone->name} Zonal Command";

            $lgaNames = LocalGovernment::whereHas('state', function ($q) use ($user) {
                $q->where('zone_id', $user->zone_id);
            })->pluck('name');

            $communityIds = Community::whereIn('lga', $lgaNames)->pluck('id');

            $projectQuery->whereIn('community_id', $communityIds);
            $beneficiaryQuery->whereIn('community_id', $communityIds);
            $communityQuery->whereIn('lga', $lgaNames);
            $activityLogQuery->whereIn('community_id', $communityIds);
        }

        // --- Chart: Scoped project status breakdown ---
        $statusCounts = (clone $projectQuery)->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $allStatuses = ['draft', 'approved', 'in_progress', 'on_hold', 'completed', 'cancelled'];
        $projectStatusLabels = array_map(fn ($s) => ucwords(str_replace('_', ' ', $s)), $allStatuses);
        $projectStatusData = array_map(fn ($s) => (int) ($statusCounts[$s] ?? 0), $allStatuses);

        // --- Chart: Scoped volunteer report verification breakdown ---
        $reportStatusCounts = (clone $activityLogQuery)->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $reportStatuses = ['pending', 'verified', 'rejected'];
        $reportStatusLabels = array_map(fn ($s) => ucwords($s), $reportStatuses);
        $reportStatusData = array_map(fn ($s) => (int) ($reportStatusCounts[$s] ?? 0), $reportStatuses);

        return view('livewire.dashboard.coordinator-dashboard', [
            'jurisdictionTitle' => $jurisdictionTitle,
            'assignedState' => $user->zoneState->name ?? 'N/A',
            'assignedLga' => $user->localGovernment->name ?? 'N/A',
            'activeProjects' => (clone $projectQuery)->where('status', 'in_progress')->count(),
            'totalProjects' => (clone $projectQuery)->count(),
            'totalBeneficiaries' => (clone $beneficiaryQuery)->count(),
            'totalCommunities' => (clone $communityQuery)->count(),
            'totalBudget' => (clone $projectQuery)->sum('budget') ?? 0,
            'recentProjects' => (clone $projectQuery)->with(['program', 'community'])->latest()->take(5)->get(),
            'volunteerReports' => (clone $activityLogQuery)->latest()->take(10)->get(),
            'projectStatusLabels' => $projectStatusLabels,
            'projectStatusData' => $projectStatusData,
            'reportStatusLabels' => $reportStatusLabels,
            'reportStatusData' => $reportStatusData,
        ])->layout('layouts.app');
    }
}