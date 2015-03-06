<?php namespace Pressor\Plugins\UpdateDisabler;
use Pressor\Support\Plugins\PluginProvider as BaseProvider;
use Illuminate\Container\Container;

class UpdateDisablerPluginProvider extends BaseProvider {

	/**
	 * boot this provider by allowing its configs to be published
	 * @return void
	 */
	public function boot()
	{
		$this->publishes(array(
			__DIR__ . '/configs.php' => config_path('pressor.plugins.update-disabler.php'),
		));
	}

	protected function afterRegister()
	{
		$this->mergeConfigFrom(__DIR__ . '/configs.php', 'pressor.plugins.update-disabler');
	}

	/**
	 * actually instantiate the plugin
	 * @param  Illuminate\Container\Container $app
	 * @return Pressor\Contracts\Plugins\Plugin
	 */
	public function makePlugin(Container $app)
	{
		$classname = $this->extractValidClassname();
		$configs = $app['config']->get('pressor.plugins.update-disabler');
		return new $classname($app['pressor'], $configs);
	}

}
