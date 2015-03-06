<?php namespace Pressor\Framework\Extensions;
use Pressor\Testing\TestCase;

class RegistryTest extends TestCase {

	function test_construct_Container_ReturnsInstanceOfRegistryContract()
	{
		$result = new Registry($container = $this->fakeContainer());

		$this->assertInstanceOf('Pressor\Contracts\Framework\Extensions\Registry', $result);
	}
	function test_construct_Container_SetsContainer()
	{
		$registry = new Registry($container = $this->fakeContainer());

		$result = $registry->getContainer();

		$this->assertEquals($container, $result);
	}
	function test_construct_ContainerAndProviders_SetsProviders()
	{
		$registry = new Registry($this->fakeContainer(), $providers = array('providers'));

		$result = $registry->getProviders();

		$this->assertEquals($providers, $result);
	}
	protected function makeRegistry()
	{
		return new Registry($this->fakeContainer());
	}
	protected function fakePluginProvider()
	{
		return $this->mock('Pressor\Contracts\Plugins\PluginProvider');
	}
	function test_plugin_ValidClassname_CallsRegisterOnContainerWithClassname()
	{
		$registry = $this->makeRegistry();

		$registry->getContainer()->shouldReceive('register')->once()->with($classname = 'PluginProviderStub')->andReturn($this->fakePluginProvider());

		$registry->plugin($classname);
	}
	function test_plugin_ValidClassnameRegisterOnContainerReturnsInstance_AddsClassAsKeyAndValueAsInstanceToPlugins()
	{
		$registry = $this->makeRegistry();
		$registry->getContainer()->shouldReceive('register')->andReturn($pluginProvider = $this->fakePluginProvider());
		$registry->plugin($classname = 'PluginProviderStub');

		$result = $registry->getPlugins();

		$this->assertEquals(array($classname => $pluginProvider), $result);
	}
	function test_plugin_ValidClassnameRegisterOnContainerReturnsResult_ReturnsResult()
	{
		$registry = $this->makeRegistry();
		$registry->getContainer()->shouldReceive('register')->andReturn($pluginProvider = $this->fakePluginProvider());

		$result = $registry->plugin('PluginProviderStub');

		$this->assertEquals($pluginProvider, $result);
	}
	function test_plugin_ArrayOfClassnames_SetsPluginsWithClassnamesAsKeysAndInstancesAsValues()
	{
		$registry = $this->makeRegistry();
		$registry->getContainer()->shouldReceive('register')->with('PluginProviderFooStub')->andReturn($fooProvider = $this->fakePluginProvider());
		$registry->getContainer()->shouldReceive('register')->with('PluginProviderBarStub')->andReturn($barProvider = $this->fakePluginProvider());
		$registry->plugin(array('PluginProviderFooStub', 'PluginProviderBarStub'));

		$result = $registry->getPlugins();

		$this->assertEquals(array('PluginProviderFooStub' => $fooProvider, 'PluginProviderBarStub' => $barProvider), $result);
	}
	function test_plugin_ArrayOfClassnames_ReturnsArrayOfPluginProviderInstances()
	{
		$registry = $this->makeRegistry();
		$registry->getContainer()->shouldReceive('register')->with('PluginProviderFooStub')->andReturn($fooProvider = $this->fakePluginProvider());
		$registry->getContainer()->shouldReceive('register')->with('PluginProviderBarStub')->andReturn($barProvider = $this->fakePluginProvider());

		$result = $registry->plugin(array('PluginProviderFooStub', 'PluginProviderBarStub'));

		$this->assertEquals(array($fooProvider, $barProvider), $result);
	}
	function test_plugin_WhenBindAlreadyCalled_ThrowsLogicException()
	{
		$registry = $this->makeRegistry();
		$registry->bind($this->fakePressorRequest());

		$this->setExpectedException('LogicException', 'Pressor registry already bound');

		$registry->plugin('PluginProviderStub');
	}

	function test_alias_KeyAndAlias_CallsAliasOnContainerWithKeyAndAlias()
	{
		$registry = $this->makeRegistry();

		$registry->getContainer()->shouldReceive('alias')->once()->with('key', 'alias');

		$registry->alias('key', 'alias');
	}

	function test_bootstrap_NoParams_WhenProvidersSetWithPluginsKeyWithProvidersAsKeyAndBooleanValue_SetsPluginsWhereValueIsTrue()
	{
		$registry = $this->makeRegistry();
		$registry->setProviders(array('plugins' => array(
			$classname = 'PluginProviderStub' => true,
			'ShouldNotLoad' => false,
		)));
		$registry->getContainer()->shouldReceive('register')->andReturn($pluginProvider = $this->fakePluginProvider());
		$registry->bootstrap();

		$result = $registry->getPlugins();

		$this->assertEquals(array($classname => $pluginProvider), $result);
	}
	function test_bootstrap_NoParams_WhenProvidersSetWithAliasesKeyWithAliases_CallsAliasOnContainerWithKeyAndValue()
	{
		$registry = $this->makeRegistry();
		$registry->setProviders(array('aliases' => array('foo' => 'bar')));

		$registry->getContainer()->shouldReceive('alias')->once()->with('foo', 'bar');

		$registry->bootstrap();
	}

	function test_bind_RequestContextWhenProvidersSet_CallsShouldLoadOnContextOnProviderWithRequestContext()
	{
		$registry = $this->makeRegistry();
		$registry->getContainer()->shouldReceive('register')->andReturn($mockProvider = $this->fakePluginProvider());
		$registry->plugin('PluginProviderStub');

		$mockProvider->shouldReceive('shouldLoadOnContext')->once()->with($fakeRequestContext = $this->fakePressorRequest());

		$registry->bind($fakeRequestContext);
	}
	function test_bind_RequestContextWhenProvidersSetAndShouldLoadOnContextOnProviderReturnsFalse_NeverCallsBindPluginOnProvider()
	{
		$registry = $this->makeRegistry();
		$registry->getContainer()->shouldReceive('register')->andReturn($mockProvider = $this->fakePluginProvider());
		$registry->plugin('PluginProviderStub');
		$mockProvider->shouldReceive('shouldLoadOnContext')->andReturn(false);

		$mockProvider->shouldReceive('bindPlugin')->never();

		$registry->bind($this->fakePressorRequest());
	}
	function test_bind_RequestContextWhenProvidersSetAndShouldLoadOnContextOnProviderReturnsTrue_CallsBindPluginOnProviderWithNoArgs()
	{
		$registry = $this->makeRegistry();
		$registry->getContainer()->shouldReceive('register')->andReturn($mockProvider = $this->fakePluginProvider());
		$registry->plugin('PluginProviderStub');
		$mockProvider->shouldReceive('shouldLoadOnContext')->andReturn(true);

		$mockProvider->shouldReceive('bindPlugin')->once()->withNoArgs();

		$registry->bind($this->fakePressorRequest());
	}
	function test_bind_WhenBindAlreadyCalled_ThrowsLogicException()
	{
		$registry = $this->makeRegistry();
		$registry->bind($this->fakePressorRequest());

		$this->setExpectedException('LogicException', 'Pressor registry already bound');

		$registry->bind($this->fakePressorRequest());
	}

/*
*/
}
