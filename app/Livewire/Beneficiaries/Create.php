<?php

namespace App\Livewire\Beneficiaries;

use Livewire\Component;
use App\Models\Beneficiary;
use App\Models\Community;
use App\Models\ZoneState;
use App\Models\LocalGovernment;

class Create extends Component
{
    public $beneficiary_code;
    public $full_name;
    public $gender = 'male';
    public $age;
    public $phone;
    
    // Geographic Binding Inputs
    public $selected_state_id = '';
    public $selected_lga_id = '';
    public $community_name = ''; // Plain text input for community
    
    public $category = 'Youth';

    protected $rules = [
        'beneficiary_code' => 'required|unique:beneficiaries,beneficiary_code',
        'full_name' => 'required|string|max:255',
        'gender' => 'required|in:male,female,other',
        'age' => 'required|integer|min:1|max:120',
        'phone' => 'nullable|string|max:20',
        'selected_state_id' => 'required|exists:zone_states,id',
        'selected_lga_id' => 'required|exists:local_governments,id',
        'community_name' => 'required|string|max:255',
        'category' => 'required|string',
    ];

    public function mount()
    {
        $this->beneficiary_code = 'BNF-' . strtoupper(substr(uniqid(), -5));
    }

    // Reset LGA selection when State changes
    public function updatedSelectedStateId()
    {
        $this->selected_lga_id = '';
    }

    public function save()
    {
        $this->validate();

        $lga = LocalGovernment::find($this->selected_lga_id);
        $state = ZoneState::find($this->selected_state_id);

        // Find or automatically create the community record for the specified LGA
        $community = Community::firstOrCreate(
            [
                'name' => trim($this->community_name),
                'lga' => $lga->name,
            ],
            [
                'state' => $state->name,
                'estimated_population' => 0,
            ]
        );

        // Register Beneficiary linked to the Community ID
        Beneficiary::create([
            'beneficiary_code' => $this->beneficiary_code,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'age' => $this->age,
            'phone' => $this->phone,
            'community_id' => $community->id,
            'category' => $this->category,
        ]);

        session()->flash('message', 'Beneficiary successfully registered.');

        return redirect()->route('beneficiaries.index');
    }

    public function render()
    {
        $states = ZoneState::orderBy('name', 'asc')->get();

        $lgas = $this->selected_state_id 
            ? LocalGovernment::where('zone_state_id', $this->selected_state_id)->orderBy('name', 'asc')->get() 
            : collect();

        return view('livewire.beneficiaries.create', [
            'states' => $states,
            'lgas' => $lgas,
        ])->layout('layouts.app');
    }
}