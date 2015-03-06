<?php namespace Pressor\Framework;
use Pressor\Testing\TestCase;
use Pressor\Framework\Request\Context as RequestContext;
use Pressor\Path\Provider as PathProvider;
use Pressor\Proxy\Proxy;
use Pressor\Hooks\Factory as HooksFactory;
use Pressor\Options\Provider as OptionsProvider;

class PressorServiceProviderTest extends TestCase {

	protected $useApp = true;

	protected function makeProvider()
	{
		$this->prepareAppForServiceProvider();
		$this->app['path.base'] = 'base_path';
		return new PressorServiceProvider($this->app);
	}

	function test_construct_NoParams_SetsIsDeferredAsFalse()
	{
		$provider = $this->makeProvider();

		$this->assertFalse($provider->isDeferred());
	}
// register wordpress path
	function test_register_NoParamsWhenContainerPathWordpressNotSet_SetsPathWordpressAsPathBaseSlashWordpress()
	{
		$provider = $this->makeProvider();
		unset($this->app['path.wordpress']);
		$provider->register();

		$result = $this->app['path.wordpress'];

		$this->assertEquals('base_path/wordpress', $result);
	}
// register pressor.constants
	function test_register_NoParams_RegistersConstantsServiceProviderOnApp()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$result = $this->app->getProvider($class = 'Pressor\Constants\ConstantsServiceProvider');

		$this->assertInstanceOf($class, $result);
	}
	function test_register_NoParams_ProvidesPressorConstantsOnContainerAsInstanceOfConstantsProvider()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$result = $this->app['pressor.constants'];

		$this->assertInstanceOf('Pressor\Constants\Provider', $result);
	}
// register pressor.registry
	function test_register_NoParams_RegistersRegistryServiceProviderOnApp()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$result = $this->app->getProvider($class = 'Pressor\Framework\Extensions\RegistryServiceProvider');

		$this->assertInstanceOf($class, $result);
	}
	function test_register_NoParams_ProvidesPressorRegistryOnContainerAsInstanceOfRegistry()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$result = $this->app['pressor.registry'];

		$this->assertInstanceOf('Pressor\Framework\Extensions\Registry', $result);
	}
// register pressor.request
	function test_register_NoParams_ProvidesPressorRequestKeyAsSingletonOfRequestContextAndSetsItsConstantsProvider()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$request = $this->app['pressor.request'];
		$second = $this->app['pressor.request'];

		$result = array($request instanceof RequestContext, $request === $second, $request->getConstantsProvider());

		$this->assertEquals(array(true, true, $this->app['pressor.constants']), $result);
	}
// register pressor.path
	function test_register_NoParams_ProvidesPressorPathKeyAsSingletonOfPathProviderAndSetsItsContainerAsApp()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$path = $this->app['pressor.path'];
		$second = $this->app['pressor.path'];

		$result = array($path instanceof PathProvider, $path === $second, $path->getContainer());

		$this->assertEquals(array(true, true, $this->app), $result);
	}
// register pressor.proxy
	function test_register_NoParams_ProvidesPressorProxyKeyAsSingletonOfProxyAndSetsItsPathProviderAsPressorPath()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$proxy = $this->app['pressor.proxy'];
		$second = $this->app['pressor.proxy'];

		$result = array($proxy instanceof Proxy, $proxy === $second, $proxy->getPathProvider());

		$this->assertEquals(array(true, true, $this->app['pressor.path']), $result);
	}
// register pressor.hooks
	function test_register_NoParams_ProvidesPressorHooksKeyAsSingletonOfHooksFactoryAndSetsItsProxyAsPressorProxy()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$hooks = $this->app['pressor.hooks'];
		$second = $this->app['pressor.hooks'];

		$result = array($hooks instanceof HooksFactory, $hooks === $second, $hooks->getProxy());

		$this->assertEquals(array(true, true, $this->app['pressor.proxy']), $result);
	}
// register pressor.options
	function test_register_NoParams_ProvidesPressorOptionsKeyAsSingletonOfOptionsProviderAndSetsItsHooksFactory()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$options = $this->app['pressor.options'];
		$second = $this->app['pressor.options'];

		$result = array($options instanceof OptionsProvider, $options === $second, $options->getHooksFactory());

		$this->assertEquals(array(true, true, $this->app['pressor.hooks']), $result);
	}
// registers pressor
	function test_register_NoParams_ProvidersPressorKeyAsSingletonOfPressorAndSetsItsContainer()
	{
		$provider = $this->makeProvider();
		$provider->register();
		$pressor = $this->app['pressor'];
		$second = $this->app['pressor'];

		$result = array($pressor instanceof Pressor, $pressor === $second, $pressor->getContainer());

		$this->assertEquals(array(true, true, $this->app), $result);
	}
// register classname aliases
	function test_register_NoParams_SetsContractAliases()
	{
		$provider = $this->makeProvider();
		$provider->register();

		$result = array(
			$this->app['Pressor\Contracts\Constants\Provider'] === $this->app['pressor.constants'],
			$this->app['Pressor\Contracts\Framework\Extensions\Registry'] === $this->app['pressor.registry'],
			$this->app['Pressor\Contracts\Framework\Request\Context'] === $this->app['pressor.request'],
			$this->app['Pressor\Contracts\Path\Provider'] === $this->app['pressor.path'],
			$this->app['Pressor\Contracts\Proxy\Proxy'] === $this->app['pressor.proxy'],
			$this->app['Pressor\Contracts\Hooks\Factory'] === $this->app['pressor.hooks'],
			$this->app['Pressor\Contracts\Options\Provider'] === $this->app['pressor.options'],
			$this->app['Pressor\Contracts\Framework\Pressor'] === $this->app['pressor'],
		);

		$this->assertEquals(array(true, true, true, true, true, true, true, true), $result);
	}
/*
*/
}
