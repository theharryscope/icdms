<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Project;
use App\Models\Program;
use App\Models\Beneficiary;
use App\Models\Community;
use App\Models\Donation;
use App\Models\Zone;
use App\Models\ZoneState;
use App\Models\LocalGovernment;
use App\Models\Post;
use App\Models\GalleryAlbum;

class LandingPage extends Component
{
    public function render()
    {
        // The featured "case file" is the most active real project on record —
        // prefer one that's actually in progress and has KPI data to show.
        $caseFileProject = Project::with(['program', 'community', 'kpis' => function ($q) {
                $q->orderByDesc('current');
            }])
            ->where('status', 'in_progress')
            ->latest()
            ->first()
            ?? Project::with(['program', 'community', 'kpis'])->latest()->first();

        $caseFileKpi = $caseFileProject?->kpis?->first();

        return view('livewire.public.landing-page', [
            'featuredPrograms' => Program::withCount('projects')->whereIn('status', ['active', 'planning'])->latest()->take(3)->get(),
            'recentProjects' => Project::with(['program', 'community'])->latest()->take(4)->get(),

            'activeProjectsCount' => Project::where('status', 'in_progress')->count(),
            'totalProjectsCount' => Project::count(),
            'totalBeneficiariesCount' => Beneficiary::count(),
            'totalCommunitiesCount' => Community::count(),
            'totalProgramsCount' => Program::count(),

            // Regional Command footprint — real counts, not a hardcoded "36+".
            'zonesCount' => Zone::count(),
            'statesCount' => ZoneState::count(),
            'lgasCount' => LocalGovernment::count(),

            // Only ever count donations Paystack itself confirmed as successful.
            'verifiedDonationsTotal' => Donation::where('payment_status', 'successful')->sum('amount'),
            'verifiedDonorsCount' => Donation::where('payment_status', 'successful')->select('donor_email')->distinct()->count(),

            'caseFileProject' => $caseFileProject,
            'caseFileKpi' => $caseFileKpi,

            'latestPosts' => Post::published()->latestFirst()->take(3)->get(),
            'latestGalleries' => GalleryAlbum::with('images')->published()->latestFirst()->take(3)->get(),

            'title' => 'InnoTech Future Foundation — Digital Skills, Regional Command, Verified Impact',
            'metaDescription' => 'InnoTech Future Foundation runs digital capacity training, community infrastructure and regional leadership programs across Nigeria — every project, KPI and donation tracked in the open.',
        ])->layout('layouts.guest');
    }
}
