<?php namespace Pressor\Plugins\SiteOptions;
use Pressor\Testing\TestCase;

class SiteOptionsPluginProviderTest extends TestCase {

	protected $useApp = true;

	protected function makeProvider()
	{
		$this->prepareAppForPluginProvider();
		$this->app['pressor.options'] = $this->fakePressorOptions();
		return new SiteOptionsPluginProvider($this->app);
	}

	function test_construct_NoParams_SetsIsDeferredAsFalse()
	{
		$provider = $this->makeProvider();

		$this->assertFalse($provider->isDeferred());
	}
	function test_boot_NoParams_PublishesConfigFilesToConfigPath()
	{
		$provider = $this->makeProvider();
		$provider->boot();

		$result = SiteOptionsPluginProvider::pathsToPublish(__NAMESPACE__ . '\SiteOptionsPluginProvider');

		$this->assertEquals(array(
			$this->extractSrcPath('Plugins/SiteOptions/options.php') => config_path('pressor.plugins.site-options.php'),
		), $result);
	}
	function test_register_NoParams_MergesConfigsKeyWithConfigsFile()
	{
		$provider = $this->makeProvider();

		$this->app['config']->shouldReceive('set')->once()->with('pressor.plugins.site-options', require $this->extractSrcPath('Plugins/SiteOptions/options.php'));

		$provider->register();
	}
	function test_register_NoParams_SetsAppPluginClassnameAsSingletonOfPlugin()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$plugin = $this->app[$classname = __NAMESPACE__ . '\SiteOptions'];
		$second = $this->app[$classname];

		$result = array($plugin instanceof SiteOptions, $plugin === $second);

		$this->assertEquals(array(true, true), $result);
	}
	function test_register_NoParamsWhenAppMakesPluginClassnameKey_SetsOnPluginOptionsProviderAndConfigsFromPressorPluginsSiteOptionsKey()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$this->app['config']->shouldReceive('get')->with('pressor.plugins.site-options')->andReturn(array('configs'));
		$plugin = $this->app[$classname = __NAMESPACE__ . '\SiteOptions'];

		$result = array($plugin->getOptionsProvider(), $plugin->getConfigs());

		$this->assertEquals(array($this->app['pressor.options'], array('configs')), $result);
	}
	function test_register_NoParamsWhenAppMakesPluginClassnameKey_CallsActionOnHooksFactoryWithPluginsLoadedEventAndArrayWithPluginAndBootAsMethod()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$this->app['pressor.hooks']->shouldReceive('action')->once()->andReturnUsing(function($event, $method)
		{
			list($plugin, $name) = $method;
			$result = array($event, $plugin instanceof SiteOptions, $name);
			$this->assertEquals(array('plugins_loaded', true, 'boot'), $result);
		});

		$this->app[__NAMESPACE__ . '\SiteOptions'];
	}

/*
*/
}

