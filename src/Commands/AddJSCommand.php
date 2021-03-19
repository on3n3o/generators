<?php

namespace Bpocallaghan\Generators\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class AddJSCommand extends FileCommand
{
    /**
     * The console command name.
     * @var string
     */
    protected $name = 'generate:addjs';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Add js files to end of a webpack.mix.js file';

    /**
     * The type of class being generated.
     * @var string
     */
    protected $type = 'addjs';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        
        $stub = "
mix.js(
    \"{jsfile}\",
    \"{publicjsfile}\"
);
        ";

        //$stub = str_replace('{{view}}', $this->getViewPath($this->getUrl(false)), $stub);

        file_put_contents(__DIR__ . '/../../../../../webpack.mix.js', $stub, FILE_APPEND);

        $this->info(ucfirst($this->option('type')) . ' created successfully.');
    }

  

}