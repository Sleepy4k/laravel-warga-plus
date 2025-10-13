<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeActionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:action {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new action class';

    /**
     * The namespace of the action.
     *
     * @var string
     */
    protected $namespace = 'App\Actions';

    /**
     * Create a new file.
     *
     * @param string $namespace
     * @param string $name
     * @param string $stub
     */
    protected function createFile(string $namespace, string $name, string $stub): void
    {
        // Make path to support sub directories
        $dir = str_replace($this->namespace, '', $namespace);
        $dir = app_path('Actions' . str_replace('/', '\\', $dir));

        // Create the directory if it does not exist
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $path = $dir . '\\' . $name . '.php';

        if (file_exists($path)) {
            $this->error('Action already exists!');
            exit(1);
        }

        $action = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, $name], $stub);

        file_put_contents($path, $action);

        $this->info('Action created successfully.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return file_get_contents(base_path() . "/stubs/action.stub");
    }

    /**
     * Create a new action class.
     *
     * @param string $name
     */
    protected function createAction(string $name): void
    {
        $namespace = $this->namespace;

        // if name had sub directories, remove them and add them to the namespace
        if (str_contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
            $namespace .= '\\' . str_replace('/', '\\', dirname($name));
            $name = basename($name);
        }

        $stub = $this->getStub();

        $this->createFile($namespace, $name, $stub);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->createAction($name);
    }
}
