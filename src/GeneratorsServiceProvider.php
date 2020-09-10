<?php

namespace Bpocallaghan\Generators;

use Illuminate\Support\ServiceProvider;
use Bpocallaghan\Generators\Commands\TestCommand;
use Bpocallaghan\Generators\Commands\JobCommand;
use Bpocallaghan\Generators\Commands\FileCommand;
use Bpocallaghan\Generators\Commands\SeedCommand;
use Bpocallaghan\Generators\Commands\ViewCommand;
use Bpocallaghan\Generators\Commands\EventCommand;
use Bpocallaghan\Generators\Commands\ModelCommand;
use Bpocallaghan\Generators\Commands\TraitCommand;
use Bpocallaghan\Generators\Commands\FactoryCommand;
use Bpocallaghan\Generators\Commands\ConsoleCommand;
use Bpocallaghan\Generators\Commands\PublishCommand;
use Bpocallaghan\Generators\Commands\ContractCommand;
use Bpocallaghan\Generators\Commands\ListenerCommand;
use Bpocallaghan\Generators\Commands\ResourceCommand;
use Bpocallaghan\Generators\Commands\ExceptionCommand;
use Bpocallaghan\Generators\Commands\MigrationCommand;
use Bpocallaghan\Generators\Commands\ControllerCommand;
use Bpocallaghan\Generators\Commands\RepositoryCommand;
use Bpocallaghan\Generators\Commands\MiddlewareCommand;
use Bpocallaghan\Generators\Commands\NotificationCommand;
use Bpocallaghan\Generators\Commands\MigrationPivotCommand;
use Bpocallaghan\Generators\Commands\EventGenerateCommand;
use Bpocallaghan\Generators\Commands\ServiceProviderCommand;

class GeneratorsServiceProvider extends ServiceProvider
{
    private $commandPath = 'command.bpocallaghan.';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // merge config
        $configPath = __DIR__ . '/config/config.php';
        $this->mergeConfigFrom($configPath, 'generators');

        // register all the artisan commands
        $this->registerCommand(PublishCommand::class, 'publish');

        $this->registerCommand(ModelCommand::class, 'model');
        $this->registerCommand(ViewCommand::class, 'view');
        $this->registerCommand(ControllerCommand::class, 'controller');

        $this->registerCommand(MiddlewareCommand::class, 'middleware');

        $this->registerCommand(MigrationCommand::class, 'migration');
        $this->registerCommand(MigrationPivotCommand::class, 'migrate.pivot');
        $this->registerCommand(SeedCommand::class, 'seed');

        $this->registerCommand(NotificationCommand::class, 'notification');

        $this->registerCommand(EventCommand::class, 'event');
        $this->registerCommand(ListenerCommand::class, 'listener');
        $this->registerCommand(EventGenerateCommand::class, 'event.generate');

        $this->registerCommand(TraitCommand::class, 'trait');
        $this->registerCommand(RepositoryCommand::class, 'repository');
        $this->registerCommand(ContractCommand::class, 'contract');

        $this->registerCommand(TestCommand::class, 'test');
        $this->registerCommand(FactoryCommand::class, 'factory');

        $this->registerCommand(JobCommand::class, 'job');
        $this->registerCommand(ConsoleCommand::class, 'console');

        $this->registerCommand(ExceptionCommand::class, 'exception');

        $this->registerCommand(ResourceCommand::class, 'resource');
        $this->registerCommand(FileCommand::class, 'file');
        $this->registerCommand(ServiceProviderCommand::class, 'service-provider');
    }

    /**
     * Register a singleton command
     *
     * @param $class
     * @param $command
     */
    private function registerCommand($class, $command)
    {
        $this->app->singleton($this->commandPath . $command, function ($app) use ($class) {
            return $app[$class];
        });

        $this->commands($this->commandPath . $command);
    }
}