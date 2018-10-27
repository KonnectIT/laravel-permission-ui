<?php

namespace KonnectIT\PermissionUi;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use KonnectIT\PermissionUi\Resources\PermissionResource;
use KonnectIT\PermissionUi\Resources\RoleResource;

class SkeletonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
//            $this->publishes([
//                __DIR__.'/../config/skeleton.php' => config_path('skeleton.php'),
//            ], 'config');

            /*
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/skeleton'),
            ], 'views');
            */

            $this->publishes([
                __DIR__.'/../resources/js/components' => base_path('resources/js/components/permission-ui'),
            ], 'permission-ui-components');

        }

        Route::get('roles', function() {
            $roleModel = config('permission.models.role');

            $roles = $roleModel
                ::when('permissions' == request('include'), function(Builder $q) {
                    $q->with(request('include'));
                })
                ->get();

            return RoleResource::collection($roles);
        });

        Route::get('permissions', function() {
            $permissionModel = config('permission.models.permission');

            $permissions = $permissionModel
                ::when('roles' == request('include'), function(Builder $q) {
                    $q->with(request('include'));
                })
                ->get();

            return PermissionResource::collection($permissions);
        });

        Route::post('permission/{permission}/toggle-role', function(Request $request, $permission) {
            $permissionModel = config('permission.models.permission');
            $roleModel = config('permission.models.role');

            $permissionFound = $permissionModel::findOrFail($permission);
            $roleFound = $roleModel::findOrFail($request->get('role_id'));

            $roleFound->permissions()->toggle($permissionFound);

            return Response::create([]);
        });

        Route::post('permission', function(Request $request) {
            $permissionModel = config('permission.models.permission');

            return $permissionModel::findOrCreate($request->get('name'));
        });

        Route::delete('permission/{permission}', function(Request $request, $permission) {
            $permissionModel = config('permission.models.permission');

            $permissionModel::findById($permission)->delete();

            return [];
        });

        Route::post('role', function(Request $request) {
            $roleModel = config('permission.models.role');

            return $roleModel::findOrCreate($request->get('name'));
        });

        Route::delete('role/{role}', function(Request $request, $role) {
            $roleModel = config('permission.models.role');

            $roleModel::findById($role)->delete();

            return [];
        });

    }

    /**
     * Register the application services.
     */
    public function register()
    {
//        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'skeleton');
    }
}
