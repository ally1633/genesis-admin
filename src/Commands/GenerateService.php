<?php

namespace App\Console\Commands;


use App\Helpers\Generators;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateService extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:service {modelName}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates service for a model';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$modelName = $this->argument('modelName');
		$modelLowercase = strtolower($modelName);
		$modelPlural = Str::plural($modelLowercase);

		$modelTemplate = str_replace(
			['{{modelName}}','{{modelLowercase}}','{{modelPlural}}'],
			[$modelName,$modelLowercase,$modelPlural],
			Generators::getStub('Service')
		);

		file_put_contents(app_path("/Services/{$modelName}Service.php"), $modelTemplate);
	}

}
