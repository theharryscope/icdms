<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Program;

class StudentDashboard extends Component
{
    public $assignment_title;
    public $submission_link;
    public $submission_notes;

    public function submitAssignment()
    {
        $this->validate([
            'assignment_title' => 'required|string|max:255',
            'submission_link' => 'required|url',
            'submission_notes' => 'nullable|string',
        ]);

        session()->flash('message', 'Assignment link submitted successfully! Your instructor will review it soon.');

        $this->reset(['assignment_title', 'submission_link', 'submission_notes']);
    }

    public function render()
    {
        $student = auth()->user();

        // Sample curriculum modules for the enrolled course
        $curriculumModules = [
            ['title' => 'Module 1: HTML5, CSS3 & Responsive Design', 'duration' => 'Week 1 - 3', 'status' => 'Completed'],
            ['title' => 'Module 2: JavaScript ES6+ & DOM Manipulation', 'duration' => 'Week 4 - 6', 'status' => 'In Progress'],
            ['title' => 'Module 3: Backend APIs with PHP & Laravel', 'duration' => 'Week 7 - 9', 'status' => 'Upcoming'],
            ['title' => 'Module 4: Capstone Project & Deployment', 'duration' => 'Week 10 - 12', 'status' => 'Upcoming'],
        ];

        return view('livewire.dashboard.student-dashboard', [
            'student' => $student,
            'curriculumModules' => $curriculumModules,
            'availablePrograms' => Program::latest()->take(3)->get(),
        ])->layout('layouts.app');
    }
}