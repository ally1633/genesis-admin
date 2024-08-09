<?php

namespace App\Console\Commands;


use App\Helpers\Generators;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateDashboard extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:dashboard {modelName} {tableName} {--customView}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates dashboard for a model';

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
		$hasCustom = false;
		$customView = (bool) $this->option('customView');

		if($customView){
			$hasCustom = true;
			$modelLowercase = strtolower($modelName);
			$modelFile = 'App\Models\\'.$modelName;
			$columns = $modelFile::getTableColumns();

			$tableHeader = [];

			foreach ($columns as $column){
				$tableHeader[] = '<th class="text-center">'.$column['label'].'</th>';
			}

			$bladeTemplate = str_replace(
				['{{tableLabel}}','{{tableHeader}}'],
				[ucfirst($tableName),join(PHP_EOL,$tableHeader)],
				Generators::getStub('tableBlade')
			);

			file_put_contents(resource_path("/views/modelCustomViews/{$modelLowercase}.blade.php"), $bladeTemplate);
		}

		$dataToAppend = "		(object)[
				'label'=>'".Str::plural($modelName)."',
				'value'=>'".$tableName."',
				'hasCustom'=> ".$hasCustom.",
				'modelName'=>'".$modelName."',
			],";

		$search = '		];';

		$replace = $dataToAppend. PHP_EOL. $search;

		Generators::appendInAppFolder('/Helpers/Internal.php',$search,$replace);

	}
}
