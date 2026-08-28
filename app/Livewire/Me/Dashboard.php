<?php

namespace App\Livewire\Me;

use Livewire\Component;
use App\Models\Project;
use App\Models\Kpi;
use App\Models\FieldMonitoringVisit;

class Dashboard extends Component
{
    public function render()
    {
        $kpis = Kpi::with('project')->get();
        
        $totalKpis = $kpis->count();
        $achievedKpis = $kpis->filter(fn($k) => $k->target > 0 && ($k->current / $k->target) >= 1)->count();
        $atRiskKpis = $kpis->filter(fn($k) => $k->target > 0 && ($k->current / $k->target) < 0.5)->count();

        $recentVisits = FieldMonitoringVisit::with(['project', 'officer'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.me.dashboard', [
            'kpis' => $kpis,
            'totalKpis' => $totalKpis,
            'achievedKpis' => $achievedKpis,
            'atRiskKpis' => $atRiskKpis,
            'recentVisits' => $recentVisits,
        ])->layout('layouts.app');
    }
}