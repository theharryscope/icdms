<?php

namespace App\Livewire\Me;

use Livewire\Component;
use App\Models\FieldMonitoringVisit;
use App\Models\Project;

class CreateFieldVisit extends Component
{
    public $project_id;
    public $visit_date;
    public $latitude;
    public $longitude;
    public $observations;
    public $challenges;
    public $recommendations;
    public $status = 'conducted';

    protected $rules = [
        'project_id' => 'required|exists:projects,id',
        'visit_date' => 'required|date',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'observations' => 'required|string',
        'challenges' => 'nullable|string',
        'recommendations' => 'nullable|string',
        'status' => 'required|in:scheduled,conducted,reviewed,approved',
    ];

    public function mount()
    {
        $this->visit_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        FieldMonitoringVisit::create([
            'project_id' => $this->project_id,
            'field_officer_id' => auth()->id() ?? 1,
            'visit_date' => $this->visit_date,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'observations' => $this->observations,
            'challenges' => $this->challenges,
            'recommendations' => $this->recommendations,
            'status' => $this->status,
        ]);

        return redirect()->route('me.dashboard');
    }

    public function render()
    {
        return view('livewire.me.create-field-visit', [
            'projects' => Project::all(),
        ])->layout('layouts.app');
    }
}