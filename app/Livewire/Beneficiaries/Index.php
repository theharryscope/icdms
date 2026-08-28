<?php

namespace App\Livewire\Beneficiaries;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Beneficiary;
use App\Models\Community;
use App\Models\ZoneState;
use App\Models\LocalGovernment;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletionId = null;

    // Edit Modal State & Form Inputs
    public $isEditModalOpen = false;
    public $editingBeneficiaryId;
    public $beneficiary_code, $full_name, $gender, $age, $phone, $category;
    public $selected_state_id, $selected_lga_id, $community_name;

    protected $listeners = ['refreshBeneficiaries' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- DELETE FUNCTIONS ---
    public function confirmDelete($id)
    {
        $this->confirmingDeletionId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletionId = null;
    }

    public function deleteBeneficiary($id)
    {
        $beneficiary = Beneficiary::find($id);
        if ($beneficiary) {
            $beneficiary->delete();
            session()->flash('message', 'Beneficiary record deleted successfully.');
        }
        $this->confirmingDeletionId = null;
    }

    // --- EDIT MODAL FUNCTIONS ---
    public function openEditModal($id)
    {
        $beneficiary = Beneficiary::with('community')->findOrFail($id);
        
        $this->editingBeneficiaryId = $beneficiary->id;
        $this->beneficiary_code = $beneficiary->beneficiary_code;
        $this->full_name = $beneficiary->full_name;
        $this->gender = $beneficiary->gender;
        $this->age = $beneficiary->age;
        $this->phone = $beneficiary->phone;
        $this->category = $beneficiary->category;
        $this->community_name = $beneficiary->community->name ?? '';

        // Reverse resolve State and LGA from existing Community
        if ($beneficiary->community) {
            $lga = LocalGovernment::where('name', $beneficiary->community->lga)->first();
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

    public function updateBeneficiary()
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:1|max:120',
            'phone' => 'nullable|string|max:20',
            'selected_state_id' => 'required|exists:zone_states,id',
            'selected_lga_id' => 'required|exists:local_governments,id',
            'community_name' => 'required|string|max:255',
            'category' => 'required|string',
        ]);

        $lga = LocalGovernment::find($this->selected_lga_id);
        $state = ZoneState::find($this->selected_state_id);

        $community = Community::firstOrCreate(
            ['name' => trim($this->community_name), 'lga' => $lga->name],
            ['state' => $state->name, 'estimated_population' => 0]
        );

        $beneficiary = Beneficiary::findOrFail($this->editingBeneficiaryId);
        $beneficiary->update([
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'age' => $this->age,
            'phone' => $this->phone,
            'community_id' => $community->id,
            'category' => $this->category,
        ]);

        $this->isEditModalOpen = false;
        session()->flash('message', 'Beneficiary record updated successfully.');
    }

    public function closeModal()
    {
        $this->isEditModalOpen = false;
    }

    public function render()
    {
        $beneficiaries = Beneficiary::with('community')
            ->when($this->search, function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('beneficiary_code', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $states = ZoneState::orderBy('name', 'asc')->get();
        $lgas = $this->selected_state_id 
            ? LocalGovernment::where('zone_state_id', $this->selected_state_id)->orderBy('name', 'asc')->get() 
            : collect();

        return view('livewire.beneficiaries.index', [
            'beneficiaries' => $beneficiaries,
            'states' => $states,
            'lgas' => $lgas,
        ])->layout('layouts.app');
    }
}