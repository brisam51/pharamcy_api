<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SuperAdminScope implements Scope
{
    public function apply(Builder $builder, Model $model): void 
    {
        // Skip the scope during authentication
        if ($this->isAuthenticationRequest()) {
            return;
        }
        if (Auth::check()) {
            $user = Auth::user();
             \Log::debug('API Request SuperAdminScope', [
                'endpoint' => request()->path(),
                'method' => request()->method(),
                'user_id' => $user->id,
                'roles' => $user->roles->pluck('name'),
                'is_superadmin' => $user->hasRole('superadmin')
            ]);
            
            // If NOT a superadmin, they can ONLY see their own record
            if (!$user->hasRole('superadmin')) {
                 $builder->where($model->getTable() . '.id', $user->id);
            }
          
        } else {
           
            $builder->where('id', 0); // This ensures no records are returned
        }
    }

     private function isAuthenticationRequest(): bool
    {
        $route = request()->route();
        if (!$route) return true;

        // Add routes that should bypass the scope
        $bypassRoutes = [
            'login',
            'auth.login',
            'api.login',
            'sanctum/token',
            // Add other auth-related routes as needed
        ];

        return in_array($route->getName(), $bypassRoutes) || 
               str_contains(request()->path(), 'login') ||
               str_contains(request()->path(), 'auth');
    }
}//end class
