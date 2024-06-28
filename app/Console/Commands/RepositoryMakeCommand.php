<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;



class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'make:Repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new repository class';

    /**
     * Execute the console command.
     */


    protected function getStub()
    {
        return __DIR__ . '/stubs/repository.stub';

    }



    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '/Repository';
    }
}
