<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Zone;
use App\Models\ZoneState;
use App\Models\LocalGovernment;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserManagement extends Component
{
    use WithPagination;

    // Search & Deletion State
    public $search = '';
    public $confirmingDeletionId = null;

    // Create Form Inputs
    public $name, $email, $password, $phone, $user_type = 'staff';
    public $selected_role, $zone_id, $zone_state_id, $local_government_id;

    // Edit Modal State & Form Inputs
    public $isEditModalOpen = false;
    public $editingUserId;
    public $edit_name, $edit_email, $edit_password, $edit_phone;
    public $edit_selected_role, $edit_zone_id, $edit_zone_state_id, $edit_local_government_id;

    // Applicant Inspection Modal State
    public $isApplicantModalOpen = false;
    public $inspectingUser = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'user_type' => 'required|in:staff,volunteer,beneficiary,partner,donor',
        'selected_role' => 'required|exists:roles,name',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- APPLICANT INSPECTION & APPROVAL ---
    public function inspectApplicant($id)
    {
        $this->inspectingUser = User::with(['zone', 'zoneState', 'localGovernment', 'roles'])->findOrFail($id);
        $this->isApplicantModalOpen = true;
    }

    public function approveApplicant($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'application_status' => 'approved',
            'is_active' => true,
        ]);

        $this->isApplicantModalOpen = false;
        session()->flash('message', "Application for {$user->name} has been APPROVED and account activated.");
    }

    public function rejectApplicant($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'application_status' => 'rejected',
            'is_active' => false,
        ]);

        $this->isApplicantModalOpen = false;
        session()->flash('message', "Application for {$user->name} has been REJECTED.");
    }

    public function closeApplicantModal()
    {
        $this->isApplicantModalOpen = false;
        $this->inspectingUser = null;
    }

    // --- CREATE USER ---
    public function createUser()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'user_type' => $this->user_type,
            'application_status' => 'approved',
            'is_active' => true,
            'zone_id' => $this->zone_id ?: null,
            'zone_state_id' => $this->zone_state_id ?: null,
            'local_government_id' => $this->local_government_id ?: null,
        ]);

        $user->assignRole($this->selected_role);

        $this->reset(['name', 'email', 'password', 'phone', 'selected_role', 'zone_id', 'zone_state_id', 'local_government_id']);
        session()->flash('message', 'User account successfully created and assigned role.');
    }

    // --- DELETE ACTIONS ---
    public function confirmDelete($id)
    {
        if (auth()->id() === $id) {
            session()->flash('message', 'Action denied: You cannot delete your active session account.');
            return;
        }
        $this->confirmingDeletionId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletionId = null;
    }

    public function deleteUser($id)
    {
        if (auth()->id() === $id) {
            session()->flash('message', 'Action denied: You cannot delete your active session account.');
            return;
        }

        $user = User::find($id);
        if ($user) {
            if ($user->document_path) {
                Storage::disk('public')->delete($user->document_path);
            }
            $user->delete();
            session()->flash('message', 'User account deleted successfully.');
        }

        $this->confirmingDeletionId = null;
    }

    // --- EDIT MODAL ACTIONS ---
    public function openEditModal($id)
    {
        $user = User::findOrFail($id);

        $this->editingUserId = $user->id;
        $this->edit_name = $user->name;
        $this->edit_email = $user->email;
        $this->edit_phone = $user->phone;
        $this->edit_selected_role = $user->getRoleNames()->first() ?? '';
        $this->edit_zone_id = $user->zone_id;
        $this->edit_zone_state_id = $user->zone_state_id;
        $this->edit_local_government_id = $user->local_government_id;
        $this->edit_password = '';

        $this->isEditModalOpen = true;
    }

    public function updatedEditZoneId()
    {
        $this->edit_zone_state_id = null;
        $this->edit_local_government_id = null;
    }

    public function updatedEditZoneStateId()
    {
        $this->edit_local_government_id = null;
    }

    public function updateUser()
    {
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_email' => 'required|email|unique:users,email,' . $this->editingUserId,
            'edit_selected_role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($this->editingUserId);

        $updateData = [
            'name' => $this->edit_name,
            'email' => $this->edit_email,
            'phone' => $this->edit_phone,
            'zone_id' => $this->edit_zone_id ?: null,
            'zone_state_id' => $this->edit_zone_state_id ?: null,
            'local_government_id' => $this->edit_local_government_id ?: null,
        ];

        if (!empty($this->edit_password)) {
            $updateData['password'] = Hash::make($this->edit_password);
        }

        $user->update($updateData);
        $user->syncRoles([$this->edit_selected_role]);

        $this->isEditModalOpen = false;
        session()->flash('message', 'User profile updated successfully.');
    }

    public function closeModal()
    {
        $this->isEditModalOpen = false;
    }

    public function render()
    {
        $users = User::with(['roles', 'zone', 'localGovernment'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('registration_role', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => Role::all(),
            'zones' => Zone::all(),
            'states' => $this->zone_id ? ZoneState::where('zone_id', $this->zone_id)->get() : ZoneState::all(),
            'lgas' => $this->zone_state_id ? LocalGovernment::where('zone_state_id', $this->zone_state_id)->get() : LocalGovernment::all(),
            'editStates' => $this->edit_zone_id ? ZoneState::where('zone_id', $this->edit_zone_id)->get() : ZoneState::all(),
            'editLgas' => $this->edit_zone_state_id ? LocalGovernment::where('zone_state_id', $this->edit_zone_state_id)->get() : LocalGovernment::all(),
        ])->layout('layouts.app');
    }
}