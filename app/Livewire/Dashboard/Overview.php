<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Project;
use App\Models\Community;
use App\Models\Beneficiary;
use App\Models\Kpi;
use App\Models\Program;
use App\Models\User;
use App\Models\Donation;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Overview extends Component
{
    public function render()
    {
        $user = auth()->user();

        $projectQuery = Project::query();
        $beneficiaryQuery = Beneficiary::query();

        if ($user && $user->hasRole('Zonal Coordinator') && $user->zone_id) {
            $lgaNames = \App\Models\LocalGovernment::whereHas('state', function ($q) use ($user) {
                $q->where('zone_id', $user->zone_id);
            })->pluck('name');

            $communityIds = Community::whereIn('lga', $lgaNames)->pluck('id');

            $projectQuery->whereIn('community_id', $communityIds);
            $beneficiaryQuery->whereIn('community_id', $communityIds);
        } elseif ($user && $user->hasRole('LGA Coordinator') && $user->local_government_id) {
            $lgaName = $user->localGovernment->name ?? '';
            $communityIds = Community::where('lga', $lgaName)->pluck('id');

            $projectQuery->whereIn('community_id', $communityIds);
            $beneficiaryQuery->whereIn('community_id', $communityIds);
        }

        // Fetch Recent Public Donations
        $recentDonations = Donation::latest()->take(5)->get();
        $totalPublicDonations = Donation::where('payment_status', 'successful')->sum('amount');

        // --- Chart: Project status breakdown (real counts, every enum value represented) ---
        $statusCounts = (clone $projectQuery)->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $allStatuses = ['draft', 'approved', 'in_progress', 'on_hold', 'completed', 'cancelled'];
        $projectStatusLabels = array_map(fn ($s) => ucwords(str_replace('_', ' ', $s)), $allStatuses);
        $projectStatusData = array_map(fn ($s) => (int) ($statusCounts[$s] ?? 0), $allStatuses);

        // --- Chart: Verified donations trend, last 6 months ---
        $donationsTrendLabels = [];
        $donationsTrendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $donationsTrendLabels[] = $month->format('M Y');
            $donationsTrendData[] = (float) Donation::where('payment_status', 'successful')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
        }

        // --- Chart: Budget vs expenditure, top 5 programs by budget ---
        $topPrograms = Program::withSum('projects as project_expenditure', 'expenditure')
            ->orderByDesc('budget')
            ->take(5)
            ->get();
        $programLabels = $topPrograms->pluck('title')->map(fn ($t) => strlen($t) > 24 ? substr($t, 0, 24) . '…' : $t)->values();
        $programBudgetData = $topPrograms->pluck('budget')->map(fn ($v) => (float) $v)->values();
        $programExpenditureData = $topPrograms->pluck('project_expenditure')->map(fn ($v) => (float) ($v ?? 0))->values();

        // --- Chart: Beneficiaries by category ---
        $categoryBreakdown = (clone $beneficiaryQuery)->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $leadershipBoard = collect();
        Zone::with(['coordinator', 'states.coordinator', 'states.localGovernments.lgaCoordinator', 'states.localGovernments.projectLeader'])
            ->get()
            ->each(function ($zone) use ($leadershipBoard) {
                if ($zone->coordinator) {
                    $leadershipBoard->push([
                        'name' => $zone->coordinator->name,
                        'photo' => $zone->coordinator->profile_photo_path,
                        'role' => 'Zonal Coordinator',
                        'scope' => $zone->name,
                        'coverage' => $zone->states->count(),
                        'unit' => 'states',
                    ]);
                }

                $zone->states->each(function ($state) use ($leadershipBoard, $zone) {
                    if ($state->coordinator) {
                        $leadershipBoard->push([
                            'name' => $state->coordinator->name,
                            'photo' => $state->coordinator->profile_photo_path,
                            'role' => 'State Coordinator',
                            'scope' => $state->name . ' · ' . $zone->name,
                            'coverage' => $state->localGovernments->count(),
                            'unit' => 'LGAs',
                        ]);
                    }

                    $state->localGovernments->each(function ($lga) use ($leadershipBoard, $state) {
                        if ($lga->lgaCoordinator) {
                            $leadershipBoard->push([
                                'name' => $lga->lgaCoordinator->name,
                                'photo' => $lga->lgaCoordinator->profile_photo_path,
                                'role' => 'LGA Coordinator',
                                'scope' => $lga->name . ' · ' . $state->name,
                                'coverage' => 1,
                                'unit' => 'LGA',
                            ]);
                        }

                        if ($lga->projectLeader) {
                            $leadershipBoard->push([
                                'name' => $lga->projectLeader->name,
                                'photo' => $lga->projectLeader->profile_photo_path,
                                'role' => 'Project Leader',
                                'scope' => $lga->name . ' · ' . $state->name,
                                'coverage' => 1,
                                'unit' => 'LGA project',
                            ]);
                        }
                    });
                });
            });

        return view('livewire.dashboard.overview', [
            'activeProjects' => (clone $projectQuery)->where('status', 'in_progress')->count(),
            'totalProjects' => (clone $projectQuery)->count(),
            'totalPrograms' => Program::count(),
            'totalCommunities' => Community::count(),
            'totalBeneficiaries' => (clone $beneficiaryQuery)->count(),
            'activeUsers' => User::where('is_active', true)->count(),
            'totalBudget' => ((clone $projectQuery)->sum('budget') ?? 0) + $totalPublicDonations,
            'recentProjects' => (clone $projectQuery)->with(['program', 'community'])->latest()->take(5)->get(),
            'kpis' => Kpi::with('project')->latest()->take(4)->get(),
            'recentDonations' => $recentDonations,
            'totalPublicDonations' => $totalPublicDonations,
            'userRole' => $user ? ($user->getRoleNames()->first() ?? 'Staff Member') : 'Guest',
            'assignedZone' => $user?->zone?->name ?? 'Global Scope',
            'assignedLga' => $user?->localGovernment?->name ?? 'Global Scope',

            'projectStatusLabels' => $projectStatusLabels,
            'projectStatusData' => $projectStatusData,
            'donationsTrendLabels' => $donationsTrendLabels,
            'donationsTrendData' => $donationsTrendData,
            'programLabels' => $programLabels,
            'programBudgetData' => $programBudgetData,
            'programExpenditureData' => $programExpenditureData,
            'categoryBreakdown' => $categoryBreakdown,
            'leadershipBoard' => $leadershipBoard->sortByDesc('coverage')->values(),
        ])->layout('layouts.app');
    }
}
