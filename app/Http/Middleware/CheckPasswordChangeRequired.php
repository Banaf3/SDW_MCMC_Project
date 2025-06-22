<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AgencyStaff;

class CheckPasswordChangeRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only check for agency staff
        if (session('user_type') === 'agency') {
            $staffId = session('user_id');
            $staff = AgencyStaff::find($staffId);            if ($staff && $staff->password_change_required) {
                // Don't redirect if already on password change page or logout
                if (!$request->is('agency/password/change') && !$request->is('logout')) {
                    return redirect()->route('agency.password.change')
                        ->with('warning', 'You must change your password before continuing.');
                }
            }
        }
        
        return $next($request);
    }
}
