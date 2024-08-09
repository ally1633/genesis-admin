<?php

namespace App\Console\Commands;


use App\Helpers\Generators;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Schema;

class GenerateController extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:controller {modelName} {tableName} {--noRoutes}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates controller for a model';

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
		$modelLowercase = strtolower($modelName);
		$modelPlural = Str::plural($modelLowercase);
		$noRoutes = (bool) $this->option('noRoutes');

		$columns = \Schema::getColumnListing($tableName);

		$validatorColumns = [];
		$excludedColumns = ['id','uuid','created_at','updated_at','deleted_at'];

		foreach ($columns as $column){
			$type = Schema::getColumnType($tableName,$column);

			if(! in_array($column,$excludedColumns)){
				$validatorColumns[] = $this->getColumnValidator($type,$column);
			}
		}
		$modelTemplate = str_replace(
			['{{modelName}}','{{modelLowercase}}','{{modelPlural}}','{{tableName}}','{{validation}}'],
			[$modelName,$modelLowercase,$modelPlural,$tableName,join(PHP_EOL,$validatorColumns)],
			Generators::getStub('Controller')
		);

		file_put_contents(app_path("/Controllers/{$modelName}Controller.php"), $modelTemplate);

		if (!$noRoutes) {
			$dataToAppend = '		$api->get(\'' . $modelPlural . '\', \'App\Controllers\\' . $modelName . 'Controller@get\');
		$api->post(\'' . $modelPlural . '\', \'App\Controllers\\' . $modelName . 'Controller@create\');
		$api->put(\'' . $modelPlural . '/{id}\',\'App\Controllers\\' . $modelName . 'Controller@update\')->where(\'id\', \'[0-9]+\');
		$api->delete(\'' . $modelPlural . '/{id}\',\'App\Controllers\\' . $modelName . 'Controller@delete\')->where(\'id\', \'[0-9]+\');';
			$search = '//end of file';

			$replace = PHP_EOL . $dataToAppend . PHP_EOL . '		' . $search;
			file_put_contents(base_path('/routes/api.php'), str_replace('//end of file', $replace, file_get_contents(base_path('/routes/api.php'))));
		}

	}

	private function getColumnValidator($type,$column)
	{
		switch($type) {
			case 'datetime':
				$validation = 'date_format:"Y-m-d"';
				break;
			case 'boolean':
				$validation = 'boolean';
				break;
			case 'integer':
				$validation = 'numeric';
				break;
			default:
				if(preg_match('/fk_(.*?)_id/', $column, $match) == 1){
					$linkedTable = $match[1];
					$validation = 'integer|exists:'.$linkedTable.',id';
					break;
				}
				$validation = 'string';
				break;
		}
		return "			'".$column."' => 'required|".$validation."',";
	}

}
