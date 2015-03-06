<?php namespace Pressor\Plugins\SiteOptions;
use Pressor\Support\Plugins\PluginProvider as BaseProvider;
use Illuminate\Container\Container;

class SiteOptionsPluginProvider extends BaseProvider {

	/**
	 * boot this provider by allowing its configs to be published
	 * @return void
	 */
	public function boot()
	{
		$this->publishes(array(
			__DIR__ . '/options.php' => config_path('pressor.plugins.site-options.php'),
		));
	}

	protected function afterRegister()
	{
		$this->mergeConfigFrom(__DIR__ . '/options.php', 'pressor.plugins.site-options');
	}

	/**
	 * actually instantiate the plugin
	 * @param  Illuminate\Container\Container $app
	 * @return Pressor\Contracts\Plugins\Plugin
	 */
	public function makePlugin(Container $app)
	{
		$classname = $this->extractValidClassname();
		$configs = $app['config']->get('pressor.plugins.site-options');
		return new $classname($app['pressor.options'], $configs);
	}

}
