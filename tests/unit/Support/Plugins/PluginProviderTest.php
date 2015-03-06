<?php namespace Pressor\Support\Plugins;
use Pressor\Testing\TestCase;
use Pressor\Contracts\Plugins\Plugin as PluginContract;

class PluginProviderTest extends TestCase {

	protected $useApp = true;

	function test_construct_Container_ReturnsInstanceOfIlluminateServiceProvider()
	{
		$result = new PluginProviderTestFooStubProvider($container = $this->fakeContainer());

		$this->assertInstanceOf('Illuminate\Support\ServiceProvider', $result);
	}
	function test_construct_Container_ReturnsInstanceOfPluginProviderContract()
	{
		$result = new PluginProviderTestFooStubProvider($container = $this->fakeContainer());

		$this->assertInstanceOf('Pressor\Contracts\Plugins\PluginProvider', $result);
	}
	function test_construct_Container_SetsContainer()
	{
		$provider = new PluginProviderTestFooStubProvider($container = $this->fakeContainer());

		$result = $provider->getContainer();

		$this->assertEquals($container, $result);
	}
// register
	protected function makeProvider()
	{
		$this->app['pressor.hooks'] = $hooks = $this->fakePressorHooks();
		$hooks->shouldReceive('action')->byDefault();
		return new PluginProviderTestFooStubProvider($this->app);
	}
	function test_register_NoParamsWhenClassnameSetAsNamespacedClassname_SetsSingletonOnContainer()
	{
		$provider = $this->makeProvider();
		$provider->setClassname($classname = __NAMESPACE__ . '\PluginProviderTestFooStub');
		$provider->register();
		$plugin = $this->app[$classname];
		$second = $this->app[$classname];

		$result = array($plugin instanceof PluginProviderTestFooStub, $plugin === $second);

		$this->assertEquals(array(true, true), $result);
	}
	function test_register_NoParamsWhenClassnameSetAsClassBasename_SetsSingletonOnContainer()
	{
		$provider = $this->makeProvider();
		$provider->setClassname('PluginProviderTestFooStub');
		$provider->register();
		$plugin = $this->app[$classname = __NAMESPACE__ . '\PluginProviderTestFooStub'];
		$second = $this->app[$classname];

		$result = array($plugin instanceof PluginProviderTestFooStub, $plugin === $second);

		$this->assertEquals(array(true, true), $result);
	}
	function test_register_NoParamsWhenClassnamePropertyNotSetGuessesClassNameByRemovingProviderSuffix_SetsSingletonOnContainer()
	{
		$provider = $this->makeProvider();
		$provider->setClassname(null);
		$provider->register();
		$plugin = $this->app[$classname = __NAMESPACE__ . '\PluginProviderTestFooStub'];
		$second = $this->app[$classname];

		$result = array($plugin instanceof PluginProviderTestFooStub, $plugin === $second);

		$this->assertEquals(array(true, true), $result);
	}
	function test_register_NoParamsWhenClassnamePropertyNotSetGuessesClassNameByRemovingPluginProviderSuffix_SetsSingletonOnContainer()
	{
		$this->app['pressor.hooks'] = $hooks = $this->fakePressorHooks();
		$hooks->shouldReceive('action')->byDefault();
		$provider = new PluginProviderTestFooStubPluginProvider($this->app);
		$provider->setClassname(null);
		$provider->register();
		$plugin = $this->app[$classname = __NAMESPACE__ . '\PluginProviderTestFooStub'];
		$second = $this->app[$classname];

		$result = array($plugin instanceof PluginProviderTestFooStub, $plugin === $second);

		$this->assertEquals(array(true, true), $result);
	}
	function test_register_NoParamsWhenClassnamePropertyIsInvalid_ThrowsRuntimeException()
	{
		$provider = $this->makeProvider();
		$provider->setClassname('foo');

		$this->setExpectedException('RuntimeException', 'The classname [foo] could not be resolved');

		$provider->register();
	}
	function test_register_NoParamsWhenValidClassnameAndBindEventAndClassInvokedOnContainer_CallsActionOnHooksFactoryWithBindEventAndArrayWithPluginAndBootMethod()
	{
		$provider = $this->makeProvider();
		$provider->setClassname('PluginProviderTestFooStub');
		$provider->register();

		$this->app['pressor.hooks']->shouldReceive('action')->once()->andReturnUsing(function($event, $method)
		{
			list($plugin, $name) = $method;
			$result = array($event, $plugin instanceof PluginProviderTestFooStub, $name);
			$this->assertEquals(array('boot_event', true, 'boot'), $result);
		});

		$this->app[__NAMESPACE__ . '\PluginProviderTestFooStub'];
	}
	function test_register_NoParamsWhenValidClassname_CallsAfterRegisterMethodWithNoArgs()
	{
		$provider = $this->makeProvider();
		$provider->setClassname('PluginProviderTestFooStub');
		$provider->register();

		$result = $provider->getLastMethodCall('afterRegister');

		$this->assertEquals(array(), $result);
	}
// shouldLoadOnRequest
	function test_shouldLoadOnRequest_RequestContext_ReturnsTrue()
	{
		$provider = $this->makeProvider();

		$result = $provider->shouldLoadOnRequest($this->fakePressorRequest());

		$this->assertTrue($result);
	}
// bindPlugin
	function test_bindPlugin_WhenValidClassname_MakesClassnameOnContainer()
	{
		$provider = $this->makeProvider();
		$provider->setClassname($classname = __NAMESPACE__ . '\PluginProviderTestFooStub');
		$this->app->bindShared($classname, function($app)
		{
			$app['plugin_instantiated'] = true;
			return null;
		});
		$provider->bindPlugin();

		$result = $this->app['plugin_instantiated'];

		$this->assertTrue($result);
	}

}

class PluginProviderTestFooStubProvider extends PluginProvider {
	use \UnitTesting\ClassSpy\WatchableTrait;

	protected $bootEvent = 'boot_event';

	public function setClassname($classname)
	{
		// let me dynamically set the class name for testing
		$this->classname = $classname;
	}

	protected function afterRegister()
	{
		$this->trackMethodCall();
	}

}

class PluginProviderTestFooStub implements PluginContract {
	public function boot()
	{
	}
}

class PluginProviderTestFooStubPluginProvider extends PluginProviderTestFooStubProvider {
}
