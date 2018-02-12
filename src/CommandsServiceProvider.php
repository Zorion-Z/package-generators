<?php 

namespace Generators;

use Illuminate\Support\ServiceProvider;

class CommandsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerPackageCommand();
    }

    protected function registerPackageCommand(){
        $this->app->singleton('command.generater.package', function($app){
            return $app['Generators\Commands\PackageCommand'];
        });
        $this->commands('command.generater.package');
    }

}
