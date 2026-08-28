<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\LocalGovernment;
use Spatie\Permission\Models\Role;

class StudentManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending'; // 'pending', 'approved', 'all'

    // Approval Modal Properties
    public $showApprovalModal = false;
    public $selectedStudentId;
    public $student_name;
    public $student_email;
    public $enrolled_course_title = 'Full-Stack Web Development';
    public $cohort_batch = 'Cohort 2026';
    public $local_government_id;

    public function openApprovalModal($studentId)
    {
        $student = User::findOrFail($studentId);
        $this->selectedStudentId = $student->id;
        $this->student_name = $student->name;
        $this->student_email = $student->email;
        $this->local_government_id = $student->local_government_id;
        $this->showApprovalModal = true;
    }

    public function approveStudent()
    {
        $this->validate([
            'enrolled_course_title' => 'required|string|max:255',
            'cohort_batch' => 'required|string|max:255',
            'local_government_id' => 'nullable|exists:local_governments,id',
        ]);

        $student = User::findOrFail($this->selectedStudentId);

        // Ensure Student role exists
        $role = Role::firstOrCreate(['name' => 'Student']);

        // Generate unique Student ID number
        $studentIdNumber = 'ST-' . date('Y') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT);

        $student->update([
            'application_status' => 'approved',
            'is_active' => true,
            'student_id_number' => $studentIdNumber,
            'enrolled_course_title' => $this->enrolled_course_title,
            'cohort_batch' => $this->cohort_batch,
            'local_government_id' => $this->local_government_id,
            'registration_role' => 'Student',
        ]);

        $student->assignRole($role->name);

        $this->showApprovalModal = false;
        $this->reset(['selectedStudentId', 'student_name', 'student_email']);
        session()->flash('message', "Student application approved successfully! Generated ID: {$studentIdNumber}");
    }

    public function rejectStudent($studentId)
    {
        $student = User::findOrFail($studentId);
        $student->update([
            'application_status' => 'rejected',
            'is_active' => false,
        ]);

        session()->flash('message', "Student application for {$student->name} marked as rejected.");
    }

    public function render()
    {
        // Auto-initialize required roles in Spatie
        Role::firstOrCreate(['name' => 'Student']);

        $students = User::query()
            ->where(function ($q) {
                $q->where('registration_role', 'Student')
                  ->orWhere('registration_role', 'student')
                  ->orWhereHas('roles', function ($r) {
                      $r->where('name', 'Student');
                  });
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('application_status', $this->statusFilter);
            })
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('student_id_number', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.student-management', [
            'students' => $students,
            'lgas' => LocalGovernment::orderBy('name')->get(),
            'pendingCount' => User::where('registration_role', 'Student')->where('application_status', 'pending')->count(),
            'approvedCount' => User::where('registration_role', 'Student')->where('application_status', 'approved')->count(),
        ])->layout('layouts.app');
    }
}