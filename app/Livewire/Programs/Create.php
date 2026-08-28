<?php

namespace App\Livewire\Programs;

use Livewire\Component;
use App\Models\Program;
use App\Models\User;

class Create extends Component
{
    public $program_code;
    public $title;
    public $description;
    public $manager_id;
    public $budget = 0;
    public $start_date;
    public $end_date;
    public $status = 'planning';

    protected $rules = [
        'program_code' => 'required|unique:programs,program_code',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'manager_id' => 'nullable|exists:users,id',
        'budget' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'status' => 'required|in:planning,active,suspended,completed',
    ];

    public function mount()
    {
        $this->program_code = 'PRG-' . strtoupper(substr(uniqid(), -5));
        $this->start_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        Program::create([
            'program_code' => $this->program_code,
            'title' => $this->title,
            'description' => $this->description,
            'manager_id' => $this->manager_id,
            'budget' => $this->budget,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Program successfully created.');

        return redirect()->route('programs.index');
    }

    public function render()
    {
        return view('livewire.programs.create', [
            'managers' => User::where('user_type', 'staff')->get(),
        ])->layout('layouts.app');
    }
}