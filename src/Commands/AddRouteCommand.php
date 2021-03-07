<?php

namespace Bpocallaghan\Generators\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class AddRouteCommand extends FileCommand
{
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'generate:addroute';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Add route to end of a file';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'addroute';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // setup
        $this->setSettings();
        $this->getResourceName($this->getUrl(false));

        // check the path where to create and save file
        $path = $this->getPath('');
        if ($this->files->exists($path) && $this->optionForce() === false) {
            return $this->error($this->type . ' already exists!');
        }

        // make all the directories
        $this->makeDirectory($path);

        // build file and save it at location
        $stub = $this->files->get(config('generators.stubs.addroute'));
        $stub = str_replace('{{view}}', $this->getViewPath($this->getUrl(false)), $stub);
        if ((bool) $this->optionModule()) {
            // Modules\ModuleName
            $stub = str_replace('{{rootNamespace}}', config('generators.defaults.modules_namespace') . $this->optionModule() . '\\', $stub);
        } else {
            // App\
            $stub = str_replace('{{rootNamespace}}', $this->getLaravel()->getNamespace(), $stub);
        }  
        $stub = str_replace('{{class}}', $this->getClassName(), $stub);

        file_put_contents($path, $stub, FILE_APPEND);

        $this->info(ucfirst($this->option('type')) . ' created successfully.');
    }

    /**
     * Get the class name
     * @return mixed
     */
    protected function getClassName()
    {
        $className = $this->argument('name');
        $className = implode('\\', array_map('Str::plural', explode('.', $className)));
        return ucwords($className, '\\');
    }

}