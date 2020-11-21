<?php

namespace Bpocallaghan\Generators\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class FileCommand extends GeneratorCommand
{
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'generate:file';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create a file from a stub in the config';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'File';

    /**
     * Get the filename of the file to generate
     * @return string
     */
    private function getFileName()
    {
        $name = $this->getArgumentNameOnly();

        switch ($this->option('type')) {
            case 'view':
                break;
            case 'model':
                $name = $this->getModelName();
                break;
            case 'controller':
                $name = $this->getControllerName($name);
                break;
            case 'seeder':
                $name = $this->getSeederName($name);
                break;
            case 'service-provider':
                $name = $this->optionModule() ?? $this->getModelName();
                break;
            case 'request':
                $name = $this->getModelName();
                break;
        }

        // override the name
        if ($this->option('name')) {
            return $this->option('name') . $this->settings['file_type'];
        }

        return $this->settings['prefix'] . $name . $this->settings['postfix'] . $this->settings['file_type'];
    }

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
        $this->files->put($path, $this->buildClass($this->argumentName()));

        // check if there is an output handler function
        $output_handler = config('generators.output_path_handler');
        $this->info(ucfirst($this->option('type')) . ' created successfully.');
        if (is_callable($output_handler)) {
            // output to console from the user defined function
            $this->info($output_handler(Str::after($path, '.')));
        } else {
            // output to console
            $this->info('- ' . $path);
        }

        // if we need to run "composer dump-autoload"
        if ($this->settings['dump_autoload'] === true) {
            if ($this->confirm("Run 'composer dump-autoload'?")) {
                $this->composer->dumpAutoloads();
            }
        }
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = $this->getFileName();

        $withName = (bool) $this->option('name');

        $path = $this->settings['path'];
        
        if((bool) $this->option('module')){
            /**
             * Strip path ./app/ or ./ from path and replace it with modules path
             */
            $path = str_replace(config('generators.defaults.path'), '', $path);
            $path = str_replace('./', '', $path);
            $path = config('generators.defaults.modules_path') . $this->option('module') . '/' . $path;
        }

        if ($this->settingsDirectoryNamespace() === true) {
            $path .= $this->getArgumentPath($withName);
        }

        $path .= $name;

        return $path;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        // examples used for the placeholders is for 'foo.bar'

        // App\Foo
        $stub = str_replace('{{namespace}}', $this->getNamespace($name), $stub);

        // Foo
        $stub = str_replace('{{namespaceWithoutApp}}', $this->getNamespace($name, false), $stub);

        if((bool) $this->optionModule()){
            // Modules\ModuleName
            $stub = str_replace('{{rootNamespace}}', config('generators.defaults.modules_namespace') . $this->optionModule() . '\\', $stub);
        }else{
            // App\
            $stub = str_replace('{{rootNamespace}}', $this->getLaravel()->getNamespace(), $stub);
        }

        // Bar
        $stub = str_replace('{{class}}', $this->getClassName(), $stub);

        $url = $this->getUrl(); // /foo/bar

        // /foo/bar
        $stub = str_replace('{{url}}', $this->getUrl(), $stub);

        // bars
        $stub = str_replace('{{collection}}', $this->getCollectionName(), $stub);

        // Bars
        $stub = str_replace('{{collectionUpper}}', $this->getCollectionUpperName(), $stub);

        // Bar
        $stub = str_replace('{{model}}', $this->getModelName(), $stub);

        // Bar
        $stub = str_replace('{{resource}}', $this->resource, $stub);

        // bar
        $stub = str_replace('{{resourceLowercase}}', $this->resourceLowerCase, $stub);

        // ./resources/views/foo/bar.blade.php
        $stub = str_replace('{{path}}', $this->getPath(''), $stub);

        // foos.bars
        $stub = str_replace('{{view}}', $this->getViewPath($this->getUrl(false)), $stub);

        // foos.bars (remove admin or website if first word)
        $stub = str_replace('{{viewPath}}', $this->getViewPathFormatted($this->getUrl(false)), $stub);

        // bars
        $stub = str_replace('{{table}}', $this->getTableName($url), $stub);

        // console command name
        $stub = str_replace('{{command}}', $this->option('command'), $stub);

        // contract file name
        $stub = str_replace('{{contract}}', $this->getContractName(), $stub);

        // contract namespace
        $stub = str_replace('{{contractNamespace}}', $this->getContractNamespace(), $stub);

        // Module
        $stub = str_replace('{{module}}', $this->optionModule() ?? '', $stub);

        // 'name', 'created_by',
        $stub = str_replace('{{fillable}}', $this->getModelFillable(), $stub);

        // 'meals_eaten' => 'integer'
        // 'pushups_done' => 'boolean'
        $stub = str_replace('{{casts}}', $this->getModelCasts(), $stub);

        // created_at, payed_at
        $stub = str_replace('{{dates}}', $this->getModelDates(), $stub);

        // 'meals_eaten' => 'required|numeric|min:0'
        // 'pushups_done' => 'reqired|boolean'
        $stub = str_replace('{{request.store.validators}}', $this->getRequestValidators('store'), $stub);
        
        // 'meals_eaten' => 'required|numeric|min:0'
        // 'pushups_done' => 'reqired|boolean'
        $stub = str_replace('{{request.update.validators}}', $this->getRequestValidators('update'), $stub);
        
        $stub = str_replace('{{index.request}}', $this->getRequestNamespace($name, 'Index'), $stub);
        $stub = str_replace('{{create.request}}', $this->getRequestNamespace($name, 'Create'), $stub);
        $stub = str_replace('{{store.request}}', $this->getRequestNamespace($name, 'Store'), $stub);
        $stub = str_replace('{{edit.request}}', $this->getRequestNamespace($name, 'Edit'), $stub);
        $stub = str_replace('{{update.request}}', $this->getRequestNamespace($name, 'Update'), $stub);
        $stub = str_replace('{{destroy.request}}', $this->getRequestNamespace($name, 'Destroy'), $stub);

        return $stub;
    }

    protected function getRequestNamespace($name, $requestType)
    {
        return implode('\\', array_map("ucfirst", explode('.', $name))) .'\\' . $this->getModelName() . $requestType . 'Request';
    }

    /**
     * Get the full namespace name for a given class.
     *
     * @param  string $name
     * @param bool    $withApp
     * @return string
     */
    protected function getNamespace($name, $withApp = true)
    {
        $path = (strlen($this->settings['namespace']) >= 2 ? $this->settings['namespace'] . '\\' : '');
        
        // dont add the default namespace if specified not to in config
        if ($this->settingsDirectoryNamespace() === true) {
            $path .= str_replace('/', '\\', $this->getArgumentPath($this->option('type') == 'request' ? true : false));
        }
        
        $pieces = array_map('ucfirst', explode('/', $path));
        \Log::debug($pieces);
        if($withApp === true){
            if((bool) $this->optionModule()){
                $namespace = config('generators.defaults.modules_namespace') . $this->optionModule() . '\\' . implode('\\', $pieces);
            }else{
                $namespace = $this->getLaravel()->getNamespace() . implode('\\', $pieces);
            }
        }else{
            $namespace = implode('\\', $pieces);
        }

        $namespace = rtrim(ltrim(str_replace('\\\\', '\\', $namespace), '\\'), '\\');

        return $namespace;
    }

    /**
     * Get the url for the given name
     *
     * @param bool $lowercase
     * @return string
     */
    protected function getUrl($lowercase = true)
    {
        if ($lowercase) {
            $url = '/' . rtrim(implode(
                '/',
                array_map('Str::snake', explode('/', $this->getArgumentPath(true)))
            ), '/');
            $url = (implode('/', array_map('Str::slug', explode('/', $url))));

            return $url;
        }

        return '/' . rtrim(implode('/', explode('/', $this->getArgumentPath(true))), '/');
    }

    /**
     * Get the class name
     * @return mixed
     */
    protected function getClassName()
    {
        return ucwords(Str::camel(str_replace(
            [$this->settings['file_type']],
            [''],
            $this->getFileName()
        )));
    }

    protected function getModelFillable()
    {
        $fillableArray = [];
        $schemaArray = explode(', ', $this->optionSchema());
        foreach($schemaArray as $schemaElement){
            $schemaElement = explode(':', $schemaElement);
            if(isset($schemaElement[0])){
                $fillableArray[] = '\'' . $schemaElement[0] . '\'';
            }
        }
        return implode(', ', $fillableArray);
    }

    protected function getModelCasts()
    {
        $casts = [
            // migrationColumnType => cast
            'integer' => 'integer',
            'bigInteger' => 'integer',
            'boolean' => 'boolean',
            'decimal' => 'decimal:2',
            'double' => 'double',
            'float' => 'float',
            'json' => 'array',
            'jsonb' => 'array',
            'mediumInteger' => 'integer',
            'smallInteger' => 'integer',
            'tinyInteger' => 'integer',
            'unsignedBigInteger' => 'integer',
            'unsignedDecimal' => 'decimal',
            'unsignedInteger' => 'integer',
            'unsignedMediumInteger' => 'integer',
            'unsignedSmallInteger' => 'integer',
            'unsignedTinyInteger' => 'integer',
        ];
        
        $castsArray = [];
        $schemaArray = explode(', ', $this->optionSchema());
        foreach($schemaArray as $schemaElement){
            $schemaElement = explode(':', $schemaElement);
            if(isset($schemaElement[0]) && isset($schemaElement[1])){
                $columnType = explode(',', $schemaElement[1])[0];
                if(isset($casts[$columnType])){
                    $castsArray[] = '\'' . $schemaElement[0] . '\' => \'' . $casts[$columnType] . '\'';
                }
            }
        }
        return implode(',' . PHP_EOL, $castsArray);
    }

    protected function getModelDates()
    {
        $dateCommands = collect([
            'date', 'dateTime', 'dateTimeTz', 'softDeletes', 'softDeletesTz', 'time', 'timeTz', 'timestamp', 'timestampTz', 'year'
        ]);
        $datesArray = [];
        $schemaArray = explode(', ', $this->optionSchema());
        foreach($schemaArray as $schemaElement){
            $schemaElement = explode(':', $schemaElement);
            if(isset($schemaElement[0]) && isset($schemaElement[1])){
                $columnType = explode(',', $schemaElement[1])[0];
                if($dateCommands->contains($columnType)){
                    $datesArray[] = '\'' . $schemaElement[0] . '\'';
                }
            }
        }
        return implode(', ', $datesArray);
    }

    protected function getRequestValidators($requestType = 'store')
    {
        $validators = [];
        $schemaArray = explode(', ', $this->optionSchema());
        $schema = collect();
        foreach($schemaArray as $schemaElement){
            $schemaElements = collect(explode(':', $schemaElement));
            $schema->push(collect([
                'attribute' => $schemaElements->shift(),
                'row_settings' => $schemaElements
                ]));
        }

        if($requestType == 'store'){
            foreach($schema as $schemaElement){
                $rowValidators = $this->getRowValidators($schemaElement['row_settings'], $schemaElement['attribute']);
                $validators[] = '            \'' . $schemaElement['attribute'] . '\' => \'' . $rowValidators . '\'';
            }
        }else if($requestType == 'update'){
            foreach($schema as $schemaElement){
                $rowValidators = $this->getRowValidators($schemaElement['row_settings'], $schemaElement['attribute']);
                $validators[] = '            \'' . $schemaElement['attribute'] . '\' => \'' . $rowValidators . '\'';
            }
        }else{

        }

        return implode(', ' . PHP_EOL, $validators);
    }

    private function getRowValidators($rowSettings, $onAttribute){
        $validators = ['required'];
        /** this could be setup in config file */
        $validatorTable = [
            'string' => [
                'max:255'
            ],
            'unsigned' => [
                'min:0'
            ],
            'unique' => [
                'unique:' . $this->getTableName($this->getUrl()) . ',' . $onAttribute
            ],
            'integer' => [
                'numeric'
            ],
            
        ];
        foreach($rowSettings as $rowSetting){
            if(array_key_exists($rowSetting, $validatorTable)){
                foreach($validatorTable[$rowSetting] as $rowValidator){
                    $validators[] = $rowValidator;
                }
            }
        }
        return implode('|', $validators);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge([
            ['schema', 's', InputOption::VALUE_OPTIONAL, 'Optional schema to be attached to the migration', null],
            [
                'type',
                null,
                InputOption::VALUE_OPTIONAL,
                'The type of file: model, view, controller, migration, seed',
                'view'
            ],
            // optional for the generate:console
            [
                'command',
                null,
                InputOption::VALUE_OPTIONAL,
                'The terminal command that should be assigned.',
                'command:name'
            ],
            // optional for the generate:test
            [
                'unit',
                null,
                InputOption::VALUE_OPTIONAL,
                'Create a unit test.',
                'Feature'
            ],
        ], parent::getOptions());
    }
}
