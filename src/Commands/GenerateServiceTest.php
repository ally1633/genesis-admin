<?php

namespace App\Console\Commands;


use App\Helpers\Generators;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Schema;

class GenerateServiceTest extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:test {modelName} {tableName}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates service test for a service';

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
		$columns = \Schema::getColumnListing($tableName);

		$dtoColumnsRandom = [];
		$dtoColumnsParticular = [];
		$updateParameterColumns = [];
		$createAssertColumns = [];
		$updateAssertColumns = [];
		$modelAssertColumns=[];
		$excludedColumns = ['id','uuid','created_at','updated_at','deleted_at'];

		foreach ($columns as $column){
			$type = \Schema::getColumnType($tableName,$column);
			if(! in_array($column,$excludedColumns)){
				switch ($type){
					case 'string':
						$dtoColumnsRandom[] = "			'".$column."' => Str::random()";
						break;
					case 'number':
						$dtoColumnsRandom[] = "			'".$column."' => rand()";
						break;
					case 'datetime':
						$dtoColumnsRandom[] = "			'".$column."' => Carbon::now()->toDateTimeString()";
						break;
					case 'boolean':
						$dtoColumnsRandom[] = "			'".$column."' =>rand(0,1)";
						break;
				}
				$dtoColumnsParticular[]="			'".$column."' => $".$column;
				$updateParameterColumns[] ="$".$column;
				$createAssertColumns[]='		$this->assertTrue($'.$modelLowercase.'->'.$column.' == $dto->'.$column.', "'.$column.' doesn\'t match")';
				$updateAssertColumns[]='		$this->assertTrue($'.$modelLowercase.'->'.$column.' == $'.$column.', "'.$column.' doesn\'t match");';
				$modelAssertColumns[]='		$this->assertTrue($modelInDb->'.$column.' == $'.$modelLowercase.'->'.$column.', "'.$column.' doesn\'t match");';
			}
		}

		$modelTemplate = str_replace(
			['{{modelName}}','{{modelLowercase}}','{{dtoColumnsParticular}}','{{dtoColumnsRandom}}','{{modelAssertColumns}}','{{updateAssertColumns}}','{{updateParameterColumns}}','{{createAssertColumns}}'],
			[$modelName,$modelLowercase,join(','.PHP_EOL,$dtoColumnsParticular),join(','.PHP_EOL,$dtoColumnsRandom),join(PHP_EOL,$modelAssertColumns),join(PHP_EOL,$updateAssertColumns),join(',',$updateParameterColumns),join(';'.PHP_EOL,$createAssertColumns)],
			Generators::getStub('Test')
		);

		file_put_contents(base_path("tests/Unit/{$modelName}ServiceTest.php"), $modelTemplate);
	}

}
