<?php

namespace App\Console\Commands;


use App\Helpers\Generators;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Schema;

class GenerateModel extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:model {modelName} {tableName}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates model for a table';

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
		$tableName = $this->argument('tableName');

		$columns = Schema::getColumnListing($tableName);

		$modelData = [];
		$dates = [];
		$commentColumns = '';
		$relations = [];
		$relationForOtherModel = [];
		$columnData=[];

		foreach ($columns as $column){
			$type = \Schema::getColumnType($tableName,$column);
			$columnData[]  =$this->getCorrectType($type,$column);

			$commentColumns = $commentColumns.PHP_EOL.' * @property '.$this->getColumnShort($type).'    $'.$column;
			if($column=='id'){
				continue;
			}
			if($type=='datetime'){
				array_push($dates,"'".$column."'");
				continue;
			}
			if(preg_match('/fk_(.*?)_id/', $column, $match) == 1) {
				$relations[] = $this->getRelationsForColumn($match[1],$column,$modelName);
				$relationForOtherModel[] = $this->getRelationsForColumn(strtolower($modelName),'fk_'.$match[1].'_id',str_replace('_','',ucfirst($match[1])));
			}
			$modelData[]  = "'".$column."'";
		}

		$modelTemplate = str_replace(
			['{{modelName}}','{{modelPlural}}','{{tableName}}','{{fillable}}','{{dates}}','{{columns}}','{{relations}}','{{relationsForLinkedModel}}','{{columnData}}'],
			[$modelName,$modelPlural,$tableName,join(',',$modelData),join(',',$dates),$commentColumns,join(PHP_EOL,$relations),join(PHP_EOL,$relationForOtherModel),join(PHP_EOL,$columnData)],
			Generators::getStub('Model')
		);

		file_put_contents(app_path("/Models/{$modelName}.php"), $modelTemplate);

	}

	private function getCorrectType($type,$columnName)
	{
		$label = str_replace('_',' ',ucfirst($columnName));
		$dbName = $columnName;
		$displayBlade = 'stringFormGroup';
		$options = [];
		switch($type) {
			case 'datetime':
				$displayBlade = 'datepicker';
				break;
			case 'boolean':
				$displayBlade = 'switch';
				break;
			case 'integer':
				if(preg_match('/fk_(.*?)_id/', $columnName, $match) == 1){
					if(substr($match[1],-1)=='y'){
						$localModelLowercase = $match[1];
						$modelPlural = substr($localModelLowercase, 0, -1).'ies';
					}else{
						$modelPlural = $match[1].'s';
					}
					$options = ["'dropdownOptions'=>'".$modelPlural."'"] ;
					$displayBlade = 'dropdown';
					break;
				}
				$displayBlade = 'numberFormGroup';
				break;
			default:
				break;
		}

		return "
			[
				'label'=>'".$label."',
				'dbName'=>'".$dbName ."',
				'displayBlade'=>'".$displayBlade ."',
				'options'=>[".join(',',$options) ."],
			],";
	}

	private function getColumnShort($type)
	{
		switch($type) {
			case 'datetime':
				return 'Carbon';
			case 'boolean':
				return 'bool';
			case 'integer':
				return 'int';
			default:
				return 'string';
		}
	}

	private function getRelationsForColumn($foreignKey,$column,$modelName)
	{
		//we generate the relations both ways

		$data = '
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function '.Str::plural(strtolower($foreignKey)).'() {
		return $this->hasMany('.str_replace('_','',ucfirst($foreignKey)).'::class, \'fk_'.strtolower($modelName).'_id\');
	}
		'.PHP_EOL;

		$data .= '
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function '.strtolower($modelName).'() {
		return $this->belongsTo('.$modelName.'::class, \''.$column.'\');
	}
		';

		return $data;
	}

}
