<?php

namespace Bpocallaghan\Generators\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ServiceProviderCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:service-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new ServiceProvider class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'service-provider';

}