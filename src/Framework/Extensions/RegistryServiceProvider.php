<?php namespace Pressor\Framework\Extensions;
use Illuminate\Support\ServiceProvider;

class RegistryServiceProvider extends ServiceProvider {

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes(array(
			__DIR__ . '/providers.php' => config_path('pressor.registry.php'),
		));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/providers.php', 'pressor.registry');

		$this->app->singleton('pressor.registry', function($app)
		{
			$providers = $app['config']->get('pressor.registry');
			return new Registry($app, $providers);
		});
	}

}
