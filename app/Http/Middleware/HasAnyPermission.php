<?php

namespace App\Http\Middleware;

use App\Exceptions\NoPermissionException;
use Closure;
use Illuminate\Http\Request;

class HasAnyPermission
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

        if (!\Gate::any($permissions)) {
            throw new NoPermissionException("Permission denied");
        }

        return $next($request);
    }
}
