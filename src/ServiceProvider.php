<?php

namespace Douma\RecordLocks;

use Douma\RecordLocks\Decorators\RecordLockPolicyDecorator;
use Douma\RecordLocks\Decorators\ResourceUpdateControllerDecorator;
use Douma\RecordLocks\Decorators\UpdateControllerDecorator;
use Douma\RecordLocks\Repositories\RecordLockRepository;
use Laravel\Nova\Http\Controllers\ResourceDestroyController;
use Laravel\Nova\Http\Controllers\ResourceUpdateController;
use Laravel\Nova\Http\Controllers\UpdateFieldController;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDecorators();
        $this->registerConfig();
        $this->registerPolicies();
    }

    private function registerDecorators()
    {
        app()->bind(UpdateFieldController::class, function(){
            $controller = app()->build(UpdateFieldController::class);
            return new UpdateControllerDecorator($controller, app()->make(RecordLockRepository::class));
        });
        app()->bind(ResourceDestroyController::class, function(){
            $controller = app()->build(ResourceDestroyController::class);
            return new UpdateControllerDecorator($controller, app()->make(RecordLockRepository::class));
        });
        app()->bind(ResourceUpdateController::class, function(){
            $controller = app()->build(ResourceUpdateController::class);
            return new ResourceUpdateControllerDecorator($controller, app()->make(RecordLockRepository::class));
        });
    }

    private function registerPolicies()
    {
        foreach(config('record_locks.policies') as $policy) {
            app()->bind($policy, function() use($policy) {
                return new RecordLockPolicyDecorator(app()->build($policy), app()->make(RecordLockRepository::class));
            });
        }
    }

    private function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/config.php', 'record_locks');
        $this->publishes([
            __DIR__.'/config.php' => config_path('record_locks.php'),
        ]);
    }
}
