<?php

namespace App\Livewire\Communities;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Community;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $stateFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $communities = Community::withCount(['projects', 'beneficiaries'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('lga', 'like', '%' . $this->search . '%');
            })
            ->when($this->stateFilter, function ($query) {
                $query->where('state', $this->stateFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.communities.index', [
            'communities' => $communities,
        ])->layout('layouts.app');
    }
}