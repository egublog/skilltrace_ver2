<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Str;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

class MakeUsecaseCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:usecase {name : 作成するユースケースに関連するクラス名を指定, ex) UserIndexUsecase->User } {usecaseType : 作成するユースケースがどれかを指定する, ex) UserIndexUsecase -> index UserUpdateUsecase -> update } {--i|interface : ユースケースのインターフェースをついでに作成するかどうかのオプション}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new usecase';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Usecase';

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $usecaseClass;

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $model;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false) {
            return false;
        }

        if ($this->option('interface')) {
            $this->createUsecaseInterface();
        }
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {
        $this->setUsecaseClass();

        $path = $this->getPath($this->usecaseClass);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($this->usecaseClass));

        $this->info($this->type.' created successfully.');

        $this->line("<info>Created Usecase :</info> $this->usecaseClass");
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createUsecaseInterface()
    {
        $usecaseInterfaceClass = $this->argument('name');
        $usecaseType           = $this->argument('usecaseType');

        $this->call('make:usecaseInterface', [
            'name'        => $usecaseInterfaceClass,
            'usecaseType' => $usecaseType,
        ]);
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        $replace = $this->buildParentReplacements();

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return array
     */
    protected function buildParentReplacements()
    {
        $modelName = strtolower($this->argument('name'));

        return [
            '{{ small_class }}' => class_basename($modelName),
            '{{small_class}}'   => class_basename($modelName),
        ];
    }

    /**
     * Set usecase class name.
     *
     * @return void
     */
    private function setUsecaseClass()
    {
        $name        = ucwords(strtolower($this->argument('name')));
        $usecaseType = ucwords(strtolower($this->argument('usecaseType')));

        $this->model = $name;

        $modelClass = $this->parseName($name);

        $this->usecaseClass = $modelClass.$usecaseType.'Usecase';

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if (!$this->argument('name')) {
            throw new InvalidArgumentException('Missing required argument name');
        }

        if (!$this->argument('usecaseType')) {
            throw new InvalidArgumentException('Missing required argument usecase type');
        }

        $stub = parent::replaceClass($stub, $name);

        return str_replace('{{class}}', $this->model, $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $type = ucwords(strtolower($this->argument('usecaseType')));

        return  base_path('stubs/usecase.'.$type.'.stub');
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name        = Str::replaceFirst($this->rootNamespace(), '', $name);
        $usecaseType = Str::replaceFirst($this->rootNamespace(), '', $this->argument('usecaseType'));

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).$usecaseType.'Case.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\UseCase'.'\\'.$this->argument('name');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the usecase class.'],
            ['usecaseType', InputArgument::REQUIRED, 'The name of the usecase type.'],
        ];
    }
}
