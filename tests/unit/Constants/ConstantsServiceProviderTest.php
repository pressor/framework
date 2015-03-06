<?php namespace Pressor\Constants;
use Pressor\Testing\TestCase;

class ConstantsServiceProviderTest extends TestCase {

	protected $useApp = true;

	protected function makeProvider()
	{
		$this->prepareAppForServiceProvider();
		return new ConstantsServiceProvider($this->app);
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

		$result = ConstantsServiceProvider::pathsToPublish(__NAMESPACE__ . '\ConstantsServiceProvider');

		$this->assertEquals(array(
			$this->extractSrcPath('Constants/keys.php') => config_path('pressor.constants.php'),
			$this->extractSrcPath('Constants/data.php') => config_path('pressor.constants.data.php'),
		), $result);
	}

	function test_register_NoParams_MergesPressorConstantsKeyWithKeysFile()
	{
		$provider = $this->makeProvider();

		$this->app['config']->shouldReceive('set')->once()->with('pressor.constants', require $this->extractSrcPath('Constants/keys.php'));
		$this->app['config']->shouldReceive('set');

		$provider->register();
	}
	function test_register_NoParams_MergesPressorConstantsDataKeyWithDataFile()
	{
		$provider = $this->makeProvider();

		$this->app['config']->shouldReceive('set')->once()->with('pressor.constants.data', require $this->extractSrcPath('Constants/data.php'));
		$this->app['config']->shouldReceive('set');

		$provider->register();
	}
	function test_register_NoParams_SetsAppPressorConstantsKeyAsInstanceOfProvider()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$result = $this->app['pressor.constants'];

		$this->assertInstanceOf('Pressor\Constants\Provider', $result);
	}
	function test_register_NoParams_CallsGetOnAppConfigKeyWithPressorConstantsKey()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$this->app['config']->shouldReceive('get')->once()->with('pressor.constants')->andReturn(array('blacklisted' => array()));
		$this->app['config']->shouldReceive('get')->andReturn(array());

		$constants = $this->app['pressor.constants'];
	}
	function test_register_NoParams_CallsGetOnAppConfigKeyWithPressorConstantsDataKey()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$this->app['config']->shouldReceive('get')->with('pressor.constants')->andReturn(array('blacklisted' => array()));

		$this->app['config']->shouldReceive('get')->once()->with('pressor.constants.data')->andReturn(array('foo' => 'bar'));

		$constants = $this->app['pressor.constants'];
	}
	function test_register_NoParamsWhenGetOnAppConfigReturnsConfigsAndData_SetsConfigsAndDataOnConstants()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$this->app['config']->shouldReceive('get')->with('pressor.constants')->andReturn($configs = array('blacklisted' => array()));
		$this->app['config']->shouldReceive('get')->with('pressor.constants.data')->andReturn($data = array('foo' => 'bar'));
		$constants = $this->app['pressor.constants'];

		$result = array($constants->getConfigs(), $constants->getData());

		$this->assertEquals(array($configs, $data), $result);
	}
/*
*/
}
