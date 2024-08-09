<?php

namespace App\Console\Commands;


use App\Helpers\Generators;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Schema;

class GenerateDTO extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:dto {modelName} {tableName}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates dto for a model';

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

		$dtoColumns = ['public $id;'];
		$dtoColumnsSwagger = [];
		$dtoColumnsSwaggerRequiredDefinition =[];
		$dtoColumnsSwaggerRequired = [];
		$excludedColumns = ['id','uuid','created_at','updated_at','deleted_at'];

		foreach ($columns as $column){
			$type = \Schema::getColumnType($tableName,$column);

			if($type == 'datetime'){
				$type = 'string';
			}
			$dtoColumnsSwagger[] =  '*     @SWG\Property(property="'.$column.'", type="'.$type.'"),';
			if(! in_array($column,$excludedColumns)){
				$dtoColumns[] = '	public $'.$column.';';
				$dtoColumnsSwaggerRequiredDefinition[] =  '*     @SWG\Property(property="'.$column.'", type="'.$type.'"),';
				$dtoColumnsSwaggerRequired[] = '"'.$column.'"';
			}
		}

		$modelTemplate = str_replace(
			['{{modelName}}','{{dtoColumns}}','{{dtoColumnsSwagger}}','{{dtoColumnsSwaggerRequiredDefinition}}','{{dtoColumnsSwaggerRequired}}'],
			[$modelName,join(PHP_EOL,$dtoColumns),join(PHP_EOL,$dtoColumnsSwagger),join(PHP_EOL,$dtoColumnsSwaggerRequiredDefinition),join(',',$dtoColumnsSwaggerRequired)],
			Generators::getStub('DTO')
		);

		file_put_contents(app_path("/DTO/{$modelName}DTO.php"), $modelTemplate);
	}
}
