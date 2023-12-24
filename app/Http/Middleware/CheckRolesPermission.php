<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRolesPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (array_key_exists('roles', $request->route()->wheres)) {
            $roles = $request->route()->wheres['roles'];
            $roles = explode('|', $roles);
            if (in_array('common', $roles)) {
                return $next($request);
            }
            if (in_array($request->user()->role->name, $roles)) {
                $check_role_perm = new  \App\Repositories\CheckRolesPermission($request->route());
                if ($check_role_perm->checkPermission() || $request->user()->role->name !== 'admin') {
                    return $next($request);
                }
            }
        }
        abort(403, 'Unauthorized Action');
    }
}
