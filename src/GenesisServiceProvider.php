<?php namespace Genesis;

use App\Console\Commands\GenerateController;
use App\Console\Commands\GenerateCRUD;
use App\Console\Commands\GenerateDashboard;
use App\Console\Commands\GenerateDTO;
use App\Console\Commands\GenerateModel;
use App\Console\Commands\GenerateService;
use App\Console\Commands\GenerateServiceTest;
use App\Console\Commands\GenerateTransformer;
use Illuminate\Support\ServiceProvider;

class GenesisServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot(){

		// publish config
		$this->publishes([  __DIR__.'/../config/genesis.php' => config_path('genesis.php')]);
		// publish helper for dynamic dashboard
		$this->publishes([  __DIR__.'/../config/Internal.php' => app_path('/Helpers/Internal.php')]);

		//expose commands to application
		if ($this->app->runningInConsole()) {
			$this->commands([
				GenerateTransformer::class,
				GenerateController::class,
				GenerateCRUD::class,
				GenerateDTO::class,
				GenerateModel::class,
				GenerateService::class,
				GenerateServiceTest::class,
				GenerateDashboard::class,
			]);
		}

	}

	/**
	 * Register the service provider.
	 */
	public function register(){
		//nothing to do here
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides(){
		return array();
	}
}