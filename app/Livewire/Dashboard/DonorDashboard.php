<?php

namespace App\Livewire\Dashboard;

use App\Models\Donation;
use Livewire\Component;

class DonorDashboard extends Component
{
    public function render()
    {
        $donations = Donation::where('donor_email', auth()->user()->email)
            ->latest()
            ->get();

        return view('livewire.dashboard.donor-dashboard', [
            'donations' => $donations,
            'totalDonated' => $donations->where('payment_status', 'successful')->sum('amount'),
            'successfulCount' => $donations->where('payment_status', 'successful')->count(),
            'pendingCount' => $donations->whereIn('payment_status', ['pending', 'pending_verification'])->count(),
        ])->layout('layouts.app');
    }
}