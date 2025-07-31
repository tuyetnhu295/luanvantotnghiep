<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        if (!Session::has('admin_id')) {
            return redirect('/admin');
        }

        $adminRole = Session::get('admin_role');
        $roles = array_map('trim', $role); // $role đã là mảng rồi, chỉ cần trim từng phần

        if (!in_array($adminRole, $roles)) {
            abort(403, 'Không có quyền truy cập');
        }

        return $next($request);
    }
}
