<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('partials.nav-bar', function ($view) {
            $companyId = Auth::user()->company_id;

            if(!$companyId){
                return redirect()->back()->with('error_msg','Company data not found!');
            }

            $modules = DB::table('auth_user_modules AS AUM')
                ->join('company_modules AS CM', 'AUM.id', '=', 'CM.parent_module_id')
                ->select([
                    'AUM.module_parent_id AS module_parent_id',
                    'AUM.module_name AS module_name',
                    'AUM.module_path AS module_path',
                    'AUM.module_icon AS module_icon',
                    'CM.parent_module_id AS module_id',
                ])
                ->whereNull('AUM.is_admin')
                ->where('CM.company_id', $companyId)
                ->get()
                ->unique('module_id');

            $childModules = DB::table('auth_user_modules AS AUM')
                ->join('company_modules AS CM', 'AUM.id', '=', 'CM.child_module_id')
                ->select([
                    'CM.parent_module_id AS parent_module_id',
                    'AUM.module_name AS module_name',
                    'AUM.module_path AS module_path',
                ])
                ->whereNull('AUM.is_admin')
                ->where('CM.company_id', $companyId)
                ->where('CM.soft_delete', 0)
                ->orderBy('AUM.module_name', 'ASC')
                ->get();

            $view->with([
                'parentModules' => $modules,
                'childModules' => $childModules,
            ]);
        });
    }
}
