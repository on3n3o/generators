<?php

namespace Bpocallaghan\Generators\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResourceCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource (ServiceProvider, Model, Views, Controller, Requests, Migration, Seeder)';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    private $repositoryContract = false;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->resource = $this->getResourceOnly();
        $this->settings = config('generators.defaults');

        $this->callServiceProvider();
        $this->callRoute('web');
        $this->callRoute('api');
        $this->callModel();
        $this->callPolicy();
        $this->callView();
        $this->callJS();
        $this->callRepository();
        $this->callController();
        $this->callRequest();
        $this->callMigration();
        $this->callSeeder();
        $this->callTest();
        $this->callFactory();
        $this->callMigrate();

        // confirm dump autoload
        if ($this->confirm("Run 'composer dump-autoload'?")) {
            $this->composer->dumpAutoloads();
        }
    }

    /**
     * Call the generate:service-provider command
     */
    private function callServiceProvider(): void
    {
        if((bool) $this->optionModule()){
            if ($this->confirm("Create a " . $this->optionModule() . "ServiceProvider?")) {
                $this->callCommandFile('service-provider');
            }
        }
    }

    /**
     * Call the generate:route
     */
    private function callRoute($type): void
    {
        $name = $this->getModelName();
        if ($this->confirm("Create a $name $type route file?")) {
            $this->callCommandFile('route', $type);
        }
    }


    /**
     * Call the generate:model command
     */
    private function callModel(): void
    {
        $name = $this->getModelName();

        $resourceString = $this->getResourceOnly();
        $resourceStringLength = strlen($this->getResourceOnly());

        if ($resourceStringLength > 18) {
            $ans = $this->confirm("Your resource {$resourceString} may have too many characters to use for many to many relationships. The length is {$resourceStringLength}. Continue?");
            if ($ans === false) {
                echo "generate:resource cancelled!";
                die;
            }
        }

        if ($this->confirm("Create a $name model?")) {
            $this->callCommandFile('model', null, null, [
                '--schema' => $this->optionSchema(),
            ]);
        }
    }

    /**
     * Call the generate:policy command
     */
    private function callPolicy(): void
    {
        $name = $this->getModelName();
        if ($this->confirm("Create a $name policy?")) {
            $this->callCommandFile('policy', null, null, [
                '--schema' => $this->optionSchema(),
            ]);
        }
    }

    /**
     * Generate the resource views
     */
    private function callView(): void
    {
        if ($this->confirm("Create crud views for the $this->resource resource?")) {
            $views = config('generators.resource_views');
            foreach ($views as $key => $name) {
                $resource = $this->argument('resource');
                if (Str::contains($resource, '.')) {
                    $resource = str_replace('.', '/', $resource);
                }

                $this->callCommandFile(
                    'view',
                    $this->getViewPath($resource),
                    $key . $this->option('view'),
                    [
                        '--name' => $name,
                        '--schema' => $this->optionSchema(),
                    ]
                );
            }
        }
    }

    /**
     * Generate the resource js
     */
    private function callJS(): void
    {
        if ($this->confirm("Create crud js files for the $this->resource resource?")) {
            $views = config('generators.resource_js');
            foreach ($views as $key => $name) {
                $resource = $this->argument('resource');
                if (Str::contains($resource, '.')) {
                    $resource = str_replace('.', '/', $resource);
                }

                $this->callCommandFile('js', null,
                    $key . $this->option('view'), ['--name' => $name]);
            }
        }
    }

    /**
     * Generate the Repository / Contract Pattern files
     */
    private function callRepository(): void
    {
        // check the config
        if (config('generators.settings.controller.repository_contract')) {
            if ($this->confirm("Create a repository and contract for the $this->resource resource?")) {
                $name = $this->getModelName();

                $this->repositoryContract = true;

                $this->callCommandFile('contract', $name);
                $this->callCommandFile('repository', $name);

                //$contract = $name . config('generators.settings.contract.postfix');
                //$this->callCommandFile('repository', $name, ['--contract' => $contract]);
            }
        }
    }

    /**
     * Generate the resource controller
     */
    private function callController(): void
    {
        $name = $this->getResourceControllerName();

        if ($this->confirm("Create a controller ($name) for the $this->resource resource?")) {
            $arg = $this->getArgumentResource();
            $name = substr_replace(
                $arg,
                Str::plural($this->resource),
                strrpos($arg, $this->resource),
                strlen($this->resource)
            );

            if ($this->repositoryContract) {
                $this->callCommandFile('controller', $name, 'controller_repository');
            } else {

                // if admin - update stub
                if (Str::contains($name, 'admin.') || $this->option('controller') === 'admin') {
                    $this->callCommandFile('controller', $name, 'controller_admin');
                } else {
                    $this->callCommandFile('controller', $name, 'controller');
                }
            }
        }
    }

     /**
     * Generate the resource custom requests
     */
    private function callRequest(): void
    {
        $modelName = $this->getModelName();
        if ($this->confirm("Create custom requests for the " . $this->getResourceControllerName() . "?")) {
            $requests = config('generators.custom_requests');
            foreach ($requests as $key => $name) {
                $resource = $this->argument('resource');
                if (Str::contains($resource, '.')) {
                    $resource = str_replace('.', '/', $resource);
                }

                $this->callCommandFile('request', null,
                    $key . $this->option('view'), [
                        '--name' => $modelName . ucfirst($name) . 'Request',
                        '--schema' => $this->optionSchema()
                    ]);
            }
        }
    }

    /**
     * Call the generate:migration command
     */
    private function callMigration(): void
    {
        $name = $this->getMigrationName($this->option('migration'));

        if ($this->confirm("Create a migration ($name) for the $this->resource resource?")) {
            $this->callCommand('migration', $name, [
                '--model'  => false,
                '--schema' => $this->option('schema'),
                '--module' => $this->optionModule(),
            ]);
        }
    }

    /**
     * Call the generate:seed command
     */
    private function callSeeder(): void
    {
        $name = $this->getSeederName() . config('generators.settings.seeder.postfix');

        if ($this->confirm("Create a seeder ($name) for the $this->resource resource?")) {
            $this->callCommandFile('seeder');
        }
    }

    /**
     * Call the generate:test command
     */
    private function callTest(): void
    {
        $name = $this->getModelName() . 'Test';

        if ($this->confirm("Create a test ($name) for the $this->resource resource?")) {
            // feature test
            $this->callCommandFile('test', Str::plural($name));

            // unit test
            $this->call('generate:file', [
                'name'   => $name,
                '--type' => 'test',
                '--unit' => 'Unit',
                '--module' => $this->optionModule(),
            ]);
        }
    }

    /**
     * Call the generate:factory command
     */
    private function callFactory(): void
    {
        $name = $this->getModelName() . 'Factory';

        if ($this->confirm("Create a factory ($name) for the $this->resource resource?")) {
            $this->callCommandFile('factory', $name);
        }
    }

    /**
     * Call the migrate command
     */
    protected function callMigrate(): void
    {
        if ($this->confirm('Migrate the database?')) {
            $this->call('migrate');
        }
    }

    /**
     * @param       $command
     * @param       $name
     * @param array $options
     */
    private function callCommand($command, $name, $options = []): void
    {
        $options = array_merge($options, [
            'name'    => $name,
            '--plain' => $this->option('plain'),
            '--force' => $this->option('force')
        ]);

        $this->call('generate:' . $command, $options);
    }

    /**
     * Call the generate:file command to generate the given file
     *
     * @param       $type
     * @param null  $name
     * @param null  $stub
     * @param array $options
     */
    private function callCommandFile($type, $name = null, $stub = null, $options = []): void
    {
        $this->call('generate:file', array_merge($options, [
            'name'    => ($name ? $name : $this->argument('resource')),
            '--type'  => $type,
            '--force' => $this->optionForce(),
            '--plain' => $this->optionPlain(),
            '--stub'  => ($stub ?: $this->optionStub()),
            '--module' => $this->optionModule(),
        ]));
    }

    /**
     * The resource argument
     * Lowercase and singular each word
     *
     * @return array|mixed|string
     */
    private function getArgumentResource()
    {
        $name = $this->argument('resource');
        if (Str::contains($name, '/')) {
            $name = str_replace('/', '.', $name);
        }

        if (Str::contains($name, '\\')) {
            $name = str_replace('\\', '.', $name);
        }

        // lowecase and singular
        $name = strtolower(Str::singular($name));

        return $name;
    }

    /**
     * If there are '.' in the name, get the last occurence
     *
     * @return string
     */
    private function getResourceOnly()
    {
        $name = $this->getArgumentResource();
        if (!Str::contains($name, '.')) {
            return $name;
        }

        return substr($name, strripos($name, '.') + 1);
    }

    /**
     * Get the Controller name for the resource
     *
     * @return string
     */
    private function getResourceControllerName(): string
    {
        return $this->getControllerName(
            Str::plural($this->resource),
            false
        ) . config('generators.settings.controller.postfix');
    }

    /**
     * Get the name for the migration
     *
     * @param null $name
     * @return string
     */
    private function getMigrationName($name = null): string
    {
        return 'create_' . Str::plural($this->getResourceName($name)) . '_table';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['resource', InputArgument::REQUIRED, 'The name of the resource being generated.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            [
                'view',
                null,
                InputOption::VALUE_OPTIONAL,
                'Specify the stub for the views',
                null
            ],
            [
                'controller',
                null,
                InputOption::VALUE_OPTIONAL,
                'Specify the stub for the controller',
                null
            ],
            ['migration', null, InputOption::VALUE_OPTIONAL, 'Optional migration name', null],
            [
                'schema',
                's',
                InputOption::VALUE_OPTIONAL,
                'Optional schema to be attached to the migration',
                null
            ],
        ]);
    }
}
