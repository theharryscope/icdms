<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $request->routeIs('dashboard')) {
            // 1. Super Admins always go to Central Command Overview
            if ($user->hasRole('Super Admin')) {
                return $next($request);
            }

            // 2. Coordinators go to Coordinator Command
            if ($user->hasRole(['State Coordinator', 'Zonal Coordinator', 'LGA Coordinator', 'Coordinator'])) {
                return redirect()->route('coordinator.dashboard');
            }

            // 3. Volunteers
            if ($user->hasRole('Volunteer')) {
                return redirect()->route('volunteer.dashboard');
            }

            // 4. Employers
            if ($user->hasRole('Partner / Employer')) {
                return redirect()->route('employer.dashboard');
            }

            // 5. Students
            if ($user->hasRole('Student')) {
                return redirect()->route('student.dashboard');
            }

            // 6. Public Donors/Partners
            if ($user->hasRole('Partner / Donor')) {
                return redirect()->route('donor.dashboard');
            }

            // 7. Anyone else without a recognized role must never see the Super Admin
            // overview by default — fail closed, not open.
            return redirect()->route('user.profile');
        }

        return $next($request);
    }
}