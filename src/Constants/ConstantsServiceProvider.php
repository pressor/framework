<?php namespace Pressor\Constants;
use Illuminate\Support\ServiceProvider;
class ConstantsServiceProvider extends ServiceProvider {

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes(array(
			__DIR__ . '/keys.php' => config_path('pressor.constants.php'),
			__DIR__ . '/data.php' => config_path('pressor.constants.data.php'),
		));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/keys.php', 'pressor.constants');
		$this->mergeConfigFrom(__DIR__ . '/data.php', 'pressor.constants' . '.data');

		$this->app->singleton('pressor.constants', function($app)
		{
			$configs = $app['config']->get('pressor.constants');
			$data = $app['config']->get('pressor.constants' . '.data');
			return new Provider($configs, $data);
		});
	}

}
