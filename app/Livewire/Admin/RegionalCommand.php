<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Zone;
use App\Models\ZoneState;
use App\Models\LocalGovernment;
use App\Models\User;
use Illuminate\Support\Str;

class RegionalCommand extends Component
{
    use WithFileUploads;

    // Form Inputs - Zone
    public $zone_name, $zone_code, $zone_description, $zonal_coordinator_id;

    // Form Inputs - State
    public $selected_zone_id, $state_name, $state_coordinator_id;

    // Form Inputs - LGA
    public $selected_state_id, $lga_name, $lga_coordinator_id, $project_leader_id;
    public $lga_import_file;
    public string $search = '';
    public array $zoneAssignments = [];
    public array $stateAssignments = [];
    public array $lgaCoordinatorAssignments = [];
    public array $projectLeaderAssignments = [];

    protected $rules = [
        'zone_name' => 'required|string|max:255',
        'zone_code' => 'required|string|max:50|unique:zones,code',
    ];

    public function createZone()
    {
        $this->validate();

        $zone = Zone::create([
            'name' => $this->zone_name,
            'code' => strtoupper($this->zone_code),
            'description' => $this->zone_description,
            'zonal_coordinator_id' => $this->zonal_coordinator_id ?: null,
        ]);

        if ($this->zonal_coordinator_id) {
            $user = User::find($this->zonal_coordinator_id);
            $user->update(['zone_id' => $zone->id]);
            $user->assignRole('Zonal Coordinator');
        }

        $this->reset(['zone_name', 'zone_code', 'zone_description', 'zonal_coordinator_id']);
        session()->flash('message', 'Zone created and Zonal Coordinator assigned.');
    }

    public function createState()
    {
        $this->validate([
            'selected_zone_id' => 'required|exists:zones,id',
            'state_name' => 'required|string|max:255',
        ]);

        $state = ZoneState::create([
            'zone_id' => $this->selected_zone_id,
            'name' => $this->state_name,
            'state_coordinator_id' => $this->state_coordinator_id ?: null,
        ]);

        if ($this->state_coordinator_id) {
            $user = User::find($this->state_coordinator_id);
            $user->update([
                'zone_id' => $this->selected_zone_id,
                'zone_state_id' => $state->id
            ]);
            $user->assignRole('State Coordinator');
        }

        $this->reset(['state_name', 'state_coordinator_id']);
        session()->flash('message', 'State created under Zone and State Coordinator assigned.');
    }

    public function createLga()
    {
        $this->validate([
            'selected_state_id' => 'required|exists:zone_states,id',
            'lga_name' => 'required|string|max:255',
        ]);

        $lga = LocalGovernment::create([
            'zone_state_id' => $this->selected_state_id,
            'name' => $this->lga_name,
            'lga_coordinator_id' => $this->lga_coordinator_id ?: null,
            'project_leader_id' => $this->project_leader_id ?: null,
        ]);

        $state = ZoneState::find($this->selected_state_id);

        if ($this->lga_coordinator_id) {
            $user = User::find($this->lga_coordinator_id);
            $user->update([
                'zone_id' => $state->zone_id,
                'zone_state_id' => $state->id,
                'local_government_id' => $lga->id
            ]);
            $user->assignRole('LGA Coordinator');
        }

        if ($this->project_leader_id) {
            $user = User::find($this->project_leader_id);
            $user->update([
                'zone_id' => $state->zone_id,
                'zone_state_id' => $state->id,
                'local_government_id' => $lga->id
            ]);
            $user->assignRole('Project Leader');
        }

        $this->reset(['lga_name', 'lga_coordinator_id', 'project_leader_id']);
        session()->flash('message', 'LGA registered with LGA Coordinator and Project Leader assigned.');
    }

    public function importLgas(): void
    {
        $this->validate([
            'selected_state_id' => 'required|exists:zone_states,id',
            'lga_import_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $contents = file_get_contents($this->lga_import_file->getRealPath());
        $rows = preg_split('/\r\n|\r|\n/', $contents);
        $names = [];

        foreach ($rows as $index => $row) {
            $columns = str_getcsv($row);
            $value = count($columns) > 1
                ? ($columns[array_search('name', array_map(fn ($column) => Str::lower(trim($column)), $columns)) ?: 0] ?? '')
                : ($columns[0] ?? '');
            $value = trim($value, " \t\n\r\0\x0B\xEF\xBB\xBF");

            if ($index === 0 && Str::lower($value) === 'name') {
                continue;
            }

            if ($value !== '') {
                $names[Str::lower($value)] = $value;
            }
        }

        if (empty($names)) {
            $this->addError('lga_import_file', 'No LGA names were found. Use one name per line or a CSV with a name column.');
            return;
        }

        $existingNames = LocalGovernment::where('zone_state_id', $this->selected_state_id)
            ->pluck('name')
            ->map(fn ($name) => Str::lower(trim($name)))
            ->all();

        $existingNames = array_fill_keys($existingNames, true);
        $newNames = array_diff_key($names, $existingNames);

        foreach ($newNames as $name) {
            LocalGovernment::create([
                'zone_state_id' => $this->selected_state_id,
                'name' => $name,
            ]);
        }

        $created = count($newNames);
        $skipped = count($names) - $created;
        $this->reset('lga_import_file');
        session()->flash('message', "LGA import complete: {$created} created, {$skipped} duplicate(s) skipped.");
    }

    public function clearSearch(): void
    {
        $this->search = '';
    }

    public function assignZoneCoordinator(int $zoneId): void
    {
        $userId = $this->zoneAssignments[$zoneId] ?? null;
        $this->validateAssignmentUser($userId);

        $zone = Zone::findOrFail($zoneId);
        $user = User::findOrFail($userId);
        $zone->update(['zonal_coordinator_id' => $user->id]);
        $user->update(['zone_id' => $zone->id]);
        $user->assignRole('Zonal Coordinator');

        session()->flash('message', "{$user->name} assigned as coordinator for {$zone->name}.");
    }

    public function assignStateCoordinator(int $stateId): void
    {
        $userId = $this->stateAssignments[$stateId] ?? null;
        $this->validateAssignmentUser($userId);

        $state = ZoneState::findOrFail($stateId);
        $user = User::findOrFail($userId);
        $state->update(['state_coordinator_id' => $user->id]);
        $user->update([
            'zone_id' => $state->zone_id,
            'zone_state_id' => $state->id,
        ]);
        $user->assignRole('State Coordinator');

        session()->flash('message', "{$user->name} assigned as coordinator for {$state->name}.");
    }

    public function assignLgaCoordinator(int $lgaId): void
    {
        $userId = $this->lgaCoordinatorAssignments[$lgaId] ?? null;
        $this->validateAssignmentUser($userId);

        $lga = LocalGovernment::with('state')->findOrFail($lgaId);
        $user = User::findOrFail($userId);
        $lga->update(['lga_coordinator_id' => $user->id]);
        $user->update([
            'zone_id' => $lga->state->zone_id,
            'zone_state_id' => $lga->zone_state_id,
            'local_government_id' => $lga->id,
        ]);
        $user->assignRole('LGA Coordinator');

        session()->flash('message', "{$user->name} assigned as coordinator for {$lga->name} LGA.");
    }

    public function assignProjectLeader(int $lgaId): void
    {
        $userId = $this->projectLeaderAssignments[$lgaId] ?? null;
        $this->validateAssignmentUser($userId);

        $lga = LocalGovernment::with('state')->findOrFail($lgaId);
        $user = User::findOrFail($userId);
        $lga->update(['project_leader_id' => $user->id]);
        $user->update([
            'zone_id' => $lga->state->zone_id,
            'zone_state_id' => $lga->zone_state_id,
            'local_government_id' => $lga->id,
        ]);
        $user->assignRole('Project Leader');

        session()->flash('message', "{$user->name} assigned as project leader for {$lga->name} LGA.");
    }

    private function validateAssignmentUser($userId): void
    {
        validator(['user_id' => $userId], [
            'user_id' => 'required|exists:users,id',
        ])->validate();
    }

    public function render()
    {
        $search = trim($this->search);
        $zonesQuery = Zone::with(['coordinator', 'states.coordinator', 'states.localGovernments.lgaCoordinator', 'states.localGovernments.projectLeader']);

        if ($search !== '') {
            $zonesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('states', function ($stateQuery) use ($search) {
                        $stateQuery->where('name', 'like', "%{$search}%")
                            ->orWhereHas('localGovernments', function ($lgaQuery) use ($search) {
                                $lgaQuery->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $zones = $zonesQuery->get();

        if ($search !== '') {
            $zones = $zones->map(function ($zone) use ($search) {
                if (Str::contains(Str::lower($zone->name . ' ' . $zone->code), Str::lower($search))) {
                    return $zone;
                }

                $zone->setRelation('states', $zone->states->filter(function ($state) use ($search) {
                    if (Str::contains(Str::lower($state->name), Str::lower($search))) {
                        return true;
                    }

                    $state->setRelation('localGovernments', $state->localGovernments->filter(
                        fn ($lga) => Str::contains(Str::lower($lga->name), Str::lower($search))
                    ));

                    return $state->localGovernments->isNotEmpty();
                })->values());

                return $zone;
            })->values();
        }

        return view('livewire.admin.regional-command', [
            'zones' => $zones,
            'allStates' => ZoneState::all(),
            'staffUsers' => User::where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}