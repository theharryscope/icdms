<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\Program;
use App\Models\Community;
use App\Models\ZoneState;
use App\Models\LocalGovernment;
use App\Models\User;

class Index extends Component
{
    use WithPagination;

    // Search & Deletion State
    public $search = '';
    public $confirmingDeletionId = null;

    // Edit Modal State & Form Fields
    public $isEditModalOpen = false;
    public $editingProjectId;
    public $project_code, $title, $program_id, $project_manager_id;
    public $selected_state_id, $selected_lga_id, $community_name;
    public $objectives, $budget, $status, $start_date, $end_date;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- DELETE ACTIONS ---
    public function confirmDelete($id)
    {
        $this->confirmingDeletionId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletionId = null;
    }

    public function deleteProject($id)
    {
        $project = Project::find($id);
        if ($project) {
            $project->delete();
            session()->flash('message', 'Project deleted successfully.');
        }
        $this->confirmingDeletionId = null;
    }

    // --- EDIT MODAL ACTIONS ---
    public function openEditModal($id)
    {
        $project = Project::with('community')->findOrFail($id);

        $this->editingProjectId = $project->id;
        $this->project_code = $project->project_code;
        $this->title = $project->title;
        $this->program_id = $project->program_id;
        $this->project_manager_id = $project->project_manager_id;
        $this->objectives = $project->objectives;
        $this->budget = $project->budget;
        $this->status = $project->status;
        $this->start_date = $project->start_date ? $project->start_date->format('Y-m-d') : '';
        $this->end_date = $project->end_date ? $project->end_date->format('Y-m-d') : '';
        $this->community_name = $project->community->name ?? '';

        // Reverse-resolve State and LGA from existing Community record
        if ($project->community) {
            $lga = LocalGovernment::where('name', $project->community->lga)->first();
            if ($lga) {
                $this->selected_lga_id = $lga->id;
                $this->selected_state_id = $lga->zone_state_id;
            }
        }

        $this->isEditModalOpen = true;
    }

    public function updatedSelectedStateId()
    {
        $this->selected_lga_id = '';
    }

    public function updateProject()
    {
        $this->validate([
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
        ]);

        $lgaModel = LocalGovernment::find($this->selected_lga_id);
        $stateModel = ZoneState::find($this->selected_state_id);

        $community = Community::firstOrCreate(
            ['name' => trim($this->community_name), 'lga' => $lgaModel->name],
            ['state' => $stateModel->name, 'estimated_population' => 0]
        );

        $project = Project::findOrFail($this->editingProjectId);
        $project->update([
            'title' => $this->title,
            'program_id' => $this->program_id,
            'community_id' => $community->id,
            'project_manager_id' => $this->project_manager_id ?: null,
            'objectives' => $this->objectives,
            'budget' => $this->budget,
            'status' => $this->status,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
        ]);

        $this->isEditModalOpen = false;
        session()->flash('message', 'Project workspace updated successfully.');
    }

    public function closeModal()
    {
        $this->isEditModalOpen = false;
    }

    public function render()
    {
        $projects = Project::with(['program', 'community', 'manager'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('project_code', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $states = ZoneState::orderBy('name', 'asc')->get();
        $lgas = $this->selected_state_id 
            ? LocalGovernment::where('zone_state_id', $this->selected_state_id)->orderBy('name', 'asc')->get() 
            : collect();

        return view('livewire.projects.index', [
            'projects' => $projects,
            'programs' => Program::orderBy('title', 'asc')->get(),
            'states' => $states,
            'lgas' => $lgas,
            'managers' => User::where('user_type', 'staff')->get(),
        ])->layout('layouts.app');
    }
}