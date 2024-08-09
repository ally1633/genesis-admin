<?php

namespace App\Console\Commands;


use Helpers\Generators;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Schema;

class GenerateTransformer extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:transformer {modelName} {tableName}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a transformer for a model';

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

		$columns = \Schema::getColumnListing($tableName);
		$excludedColumns = ['id'];

		$returnData = [PHP_EOL];

		foreach ($columns as $column){
			if(in_array($column,$excludedColumns)){
				continue;
			}
			$type = \Schema::getColumnType($tableName,$column);
			if($type=='date'){
				$returnData[$column] = '\'			'.$column.'\' => $model->'.$column.'->toDateTimeString(),';
				continue;
			}
			$returnData[$column] = '\'			'.$column.'\' => $model->'.$column.',';

		}
		$modelTemplate = str_replace(
			['{{modelName}}','{{returnData}}'],
			[$modelName,join(PHP_EOL,$returnData)],
			Generators::getStub('Transformer')
		);

		file_put_contents(app_path("/Models/Transformers/{$modelName}Transformer.php"), $modelTemplate);

	}
}
