<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path('Services/' . str_replace('\\', '/', $name) . '.php');

        // Extract directory path
        $directory = dirname($path);

        // Ensure the directory exists
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Check if the service class already exists
        if (File::exists($path)) {
            $this->error("Service class '{$name}' already exists!");
            return Command::FAILURE;
        }

        // Extract class name from the full path
        $className = basename($name);

        // Create the service class file
        $template = <<<PHP
<?php

namespace App\Services\\{$this->getNamespace($name)};

class {$className}
{
    // Add your service methods here
}

PHP;

        File::put($path, $template);

        $this->info("Service class '{$name}' created successfully.");
        return Command::SUCCESS;
    }

    /**
     * Get the namespace for the service class from the name.
     *
     * @param string \$name
     * @return string
     */
    protected function getNamespace(string $name): string
    {
        $parts = explode('/', str_replace('\\', '/', $name));
        array_pop($parts); // Remove the class name
        return implode('\\', $parts);
    }
}
