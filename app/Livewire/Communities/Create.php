<?php

namespace App\Livewire\Communities;

use Livewire\Component;
use App\Models\Community;

class Create extends Component
{
    public $name;
    public $state;
    public $lga;
    public $latitude;
    public $longitude;
    public $estimated_population = 0;
    public $needs_education = false;
    public $needs_tech = false;
    public $needs_infrastructure = false;
    public $needs_healthcare = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'lga' => 'required|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'estimated_population' => 'required|integer|min:0',
    ];

    public function save()
    {
        $this->validate();

        Community::create([
            'name' => $this->name,
            'state' => $this->state,
            'lga' => $this->lga,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'estimated_population' => $this->estimated_population,
            'needs_assessment' => [
                'education' => $this->needs_education,
                'technology' => $this->needs_tech,
                'infrastructure' => $this->needs_infrastructure,
                'healthcare' => $this->needs_healthcare,
            ],
        ]);

        return redirect()->route('communities.index');
    }

    public function render()
    {
        return view('livewire.communities.create')->layout('layouts.app');
    }
}