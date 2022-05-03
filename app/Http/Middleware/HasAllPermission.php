<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\NoPermissionException;

class HasAllPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $permissions = trimStringArray($permissions);

        if (!Gate::check($permissions)) {
            throw new NoPermissionException(
                __('Sorry! You are not authorized to perform this action.')
            );
        }

        return $next($request);
    }
}
