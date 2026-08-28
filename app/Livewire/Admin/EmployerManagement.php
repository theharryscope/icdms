<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employer;
use App\Models\StudentPlacement;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployerManagement extends Component
{
    use WithPagination;

    public $search = '';

    // Create Corporate Employer Form
    public $company_name, $industry_sector, $contact_person_name, $contact_email, $contact_phone, $office_address, $password;

    // Student Placement Modal
    public $showPlacementModal = false;
    public $selected_employer_id, $selected_student_id, $job_title, $placement_date;

    public function createEmployer()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'contact_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Ensure role exists
        $role = Role::firstOrCreate(['name' => 'Partner / Employer']);

        // 1. Create User Login Account for Employer
        $user = User::create([
            'name' => $this->contact_person_name,
            'email' => $this->contact_email,
            'password' => Hash::make($this->password),
            'phone' => $this->contact_phone,
            'user_type' => 'partner',
            'registration_role' => 'Partner / Employer',
            'is_active' => true,
            'application_status' => 'approved',
        ]);

        $user->assignRole($role->name);

        // 2. Create Employer Company Profile
        Employer::create([
            'user_id' => $user->id,
            'company_name' => $this->company_name,
            'industry_sector' => $this->industry_sector,
            'contact_person_name' => $this->contact_person_name,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'office_address' => $this->office_address,
        ]);

        $this->reset(['company_name', 'industry_sector', 'contact_person_name', 'contact_email', 'contact_phone', 'office_address', 'password']);
        session()->flash('message', 'Corporate Employer created and login credentials issued successfully.');
    }

    public function openPlacementModal($employerId)
    {
        $this->selected_employer_id = $employerId;
        $this->placement_date = date('Y-m-d');
        $this->showPlacementModal = true;
    }

    public function assignStudentToEmployer()
    {
        $this->validate([
            'selected_employer_id' => 'required|exists:employers,id',
            'selected_student_id' => 'required|exists:users,id',
            'job_title' => 'required|string|max:255',
            'placement_date' => 'required|date',
        ]);

        $employer = Employer::findOrFail($this->selected_employer_id);

        // 1. Create Placement Record
        StudentPlacement::create([
            'student_id' => $this->selected_student_id,
            'employer_id' => $this->selected_employer_id,
            'job_title' => $this->job_title,
            'placement_date' => $this->placement_date,
            'employment_status' => 'active',
        ]);

        // 2. Update Student Profile Category to Certified & Placed
        $student = User::findOrFail($this->selected_student_id);
        $student->update([
            'student_category' => 'certified_placed',
            'placed_company_name' => $employer->company_name,
            'placed_job_title' => $this->job_title,
            'placement_date' => $this->placement_date,
        ]);

        $this->showPlacementModal = false;
        $this->reset(['selected_student_id', 'job_title']);
        session()->flash('message', "Certified graduate successfully assigned to {$employer->company_name}.");
    }

    public function render()
    {
        // Auto-initialize required roles in Spatie database if missing
        Role::firstOrCreate(['name' => 'Student']);
        Role::firstOrCreate(['name' => 'Partner / Employer']);

        $employers = Employer::with(['placements.student'])
            ->when($this->search, function ($query) {
                $query->where('company_name', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_person_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        // Retrieve all student accounts or applicants registered under student track
        $certifiedStudents = User::role('Student')
            ->orWhere('registration_role', 'Student')
            ->orWhere('registration_role', 'student')
            ->get();

        return view('livewire.admin.employer-management', [
            'employers' => $employers,
            'certifiedStudents' => $certifiedStudents,
        ])->layout('layouts.app');
    }
}