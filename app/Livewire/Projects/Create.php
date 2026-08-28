<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\Program;
use App\Models\Community;
use App\Models\ZoneState;
use App\Models\LocalGovernment;
use App\Models\User;

class Create extends Component
{
    public $project_code;
    public $title;
    public $program_id = '';
    
    // Regional Scoping
    public $selected_state_id = '';
    public $selected_lga_id = '';
    public $community_name = ''; // Open text input field
    
    public $project_manager_id = '';
    public $objectives;
    public $budget = 0;
    public $status = 'draft';
    public $start_date;
    public $end_date;

    protected $rules = [
        'project_code' => 'required|unique:projects,project_code',
        'title' => 'required|string|max:255',
        'program_id' => 'required|exists:programs,id',
        'selected_state_id' => 'required|exists:zone_states,id',
        'selected_lga_id' => 'required|exists:local_governments,id',
        'community_name' => 'required|string|max:255',
        'project_manager_id' => 'nullable|exists:users,id',
        'objectives' => 'nullable|string',
        'budget' => 'required|numeric|min:0',
        'status' => 'required|in:draft,approved,in_progress,on_hold,completed,cancelled',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ];

    public function mount()
    {
        $this->project_code = 'PRJ-' . strtoupper(substr(uniqid(), -5));
        $this->start_date = now()->format('Y-m-d');
    }

    public function updatedSelectedStateId()
    {
        $this->selected_lga_id = '';
    }

    public function save()
    {
        $this->validate();

        $lgaModel = LocalGovernment::find($this->selected_lga_id);
        $stateModel = ZoneState::find($this->selected_state_id);

        // Find existing community or create it automatically under this LGA/State
        $community = Community::firstOrCreate(
            [
                'name' => trim($this->community_name),
                'lga' => $lgaModel->name,
            ],
            [
                'state' => $stateModel->name,
                'estimated_population' => 0,
            ]
        );

        Project::create([
            'project_code' => $this->project_code,
            'title' => $this->title,
            'program_id' => $this->program_id,
            'community_id' => $community->id,
            'project_manager_id' => $this->project_manager_id ?: null,
            'objectives' => $this->objectives,
            'budget' => $this->budget,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        session()->flash('message', 'Project workspace created successfully.');

        return redirect()->route('projects.index');
    }

    public function render()
    {
        $states = ZoneState::orderBy('name', 'asc')->get();

        $lgas = $this->selected_state_id 
            ? LocalGovernment::where('zone_state_id', $this->selected_state_id)->orderBy('name', 'asc')->get() 
            : collect();

        return view('livewire.projects.create', [
            'programs' => Program::orderBy('title', 'asc')->get(),
            'states' => $states,
            'lgas' => $lgas,
            'managers' => User::where('user_type', 'staff')->get(),
        ])->layout('layouts.app');
    }
}