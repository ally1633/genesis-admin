<?php

namespace App\Console\Commands;


use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Schema;

class GenerateCRUD extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:crud {modelName} {tableName} {--onlyModel} {--noDashboard}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates crud for a new model';

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
		$tableName = $this->argument('tableName');
		$argumentArray = ['modelName'=>$modelName,'tableName'=>$tableName];

		$this->call('generate:model',$argumentArray);

		$onlyModel = (bool) $this->option('onlyModel');
		if(!$onlyModel){
			$this->call('generate:controller ',$argumentArray);
			$this->call('generate:transformer ',$argumentArray);
			$this->call('generate:dto ',$argumentArray);
			$this->call('generate:service ',$argumentArray);
			$this->call('generate:test ',$argumentArray);
		}

		$noDashboard = (bool) $this->option('noDashboard');

		if(!$noDashboard){
			$this->call('generate:dashboard ',$argumentArray);
		}
	}

}
