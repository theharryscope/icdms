<?php

namespace App\Livewire\Programs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Program;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $programs = Program::with('manager')
            ->withCount('projects')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('program_code', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.programs.index', [
            'programs' => $programs,
        ])->layout('layouts.app');
    }
}