<?php namespace Pressor\Framework;
use Pressor\Testing\TestCase;

class PressorTest extends TestCase {

	protected $useApp = true;

	function test_construct_Container_ReturnsInstanceOfPressorContract()
	{
		$result = new Pressor($container = $this->fakeContainer());

		$this->assertInstanceOf('Pressor\Contracts\Framework\Pressor', $result);
	}
	function test_construct_Container_SetsContainer()
	{
		$pressor = new Pressor($container = $this->fakeContainer());

		$result = $pressor->getContainer();

		$this->assertEquals($container, $result);
	}

	protected function makePressorForBoot()
	{
		$pressor = new Pressor($this->app);
		$this->app['pressor.hooks'] = $hooks = $this->fakePressorHooks();
		$hooks->shouldReceive('registerBaseCallbacks', 'action')->byDefault();
		$this->app['pressor.registry'] = $registry = $this->fakePressorRegistry();
		$registry->shouldReceive('bootstrap')->byDefault();
		return $pressor;
	}
	function test_boot_NoParams_CallsRegisterBaseCallbacksOnHooksFactoryWithNoArgs()
	{
		$pressor = $this->makePressorForBoot();

		$this->app['pressor.hooks']->shouldReceive('registerBaseCallbacks')->once()->withNoArgs();

		$pressor->boot();
	}
	function test_boot_NoParams_CallsActionOnHooksWithPluginsLoadedEventAndSelfAndBindMethod()
	{
		$pressor = $this->makePressorForBoot();

		$this->app['pressor.hooks']->shouldReceive('action')->once()->with('plugins_loaded', array($pressor, 'bind'));

		$pressor->boot();
	}
	function test_boot_NoParams_CallsBootstrapOnRegistryWithNoArgs()
	{
		$pressor = $this->makePressorForBoot();

		$this->app['pressor.registry']->shouldReceive('bootstrap')->once()->withNoArgs();

		$pressor->boot();
	}
	function test_boot_NoParams_SetsPressorBootedAsTrueOnContainer()
	{
		$pressor = $this->makePressorForBoot();
		$pressor->boot();

		$result = $this->app['pressor.booted'];

		$this->assertTrue($result);
	}
	function test_boot_WhenBootAlreadyCalled_ThrowsLogicException()
	{
		$pressor = $this->makePressorForBoot();
		$pressor->boot();

		$this->setExpectedException('LogicException', 'Pressor already booted');

		$pressor->boot();
	}
// bind
	protected function makePressorForBind()
	{
		$pressor = new Pressor($this->app);
		$this->app['pressor.hooks'] = $hooks = $this->fakePressorHooks();
		$hooks->shouldReceive('bind')->byDefault();
		$this->app['pressor.registry'] = $registry = $this->fakePressorRegistry();
		$registry->shouldReceive('bind')->byDefault();
		$this->app['pressor.request'] = $this->fakePressorRequest();
		return $pressor;
	}
	function test_bind_NoParams_CallsBindOnRegistryWithRequest()
	{
		$pressor = $this->makePressorForBind();

		$this->app['pressor.registry']->shouldReceive('bind')->once()->with($this->app['pressor.request']);

		$pressor->bind();
	}
	function test_bind_NoParams_CallsBindOnHooksWithNoArgs()
	{
		$pressor = $this->makePressorForBind();

		$this->app['pressor.hooks']->shouldReceive('bind')->once()->withNoArgs();

		$pressor->bind();
	}
	function test_bind_NoParams_SetsPressorBoundKeyAsTrueOnContainer()
	{
		$pressor = $this->makePressorForBind();
		$pressor->bind();

		$result = $this->app['pressor.bound'];

		$this->assertTrue($result);
	}
	function test_bind_WhenBindAlreadyCalled_ThrowsLogicException()
	{
		$pressor = $this->makePressorForBind();
		$pressor->bind();

		$this->setExpectedException('LogicException', 'Pressor already bound');

		$pressor->bind();
	}

	protected function makePressor()
	{
		return new Pressor($this->app);
	}

	function test_offsetExists_WhenContainerPressorPrefixedKeyExists_ReturnsTrueOtherwiseFalse()
	{
		$pressor = $this->makePressor();
		$this->app['pressor.foo'] = true;

		$result = array(isset($pressor['foo']), isset($pressor['bar']));

		$this->assertEquals(array(true, false), $result);
	}
	function test_offsetGet_WhenContainerPressorPrefixedKeyExists_ReturnsItsValue()
	{
		$pressor = $this->makePressor();
		$this->app['pressor.foo'] = 'result';

		$result = $pressor['foo'];

		$this->assertEquals('result', $result);
	}
	function test_offsetSet_KeyAndValue_SetsPressorPrefixedKeyOnContainerWithValue()
	{
		$pressor = $this->makePressor();
		$pressor['foo'] = 'result';

		$result = $this->app['pressor.foo'];

		$this->assertEquals('result', $result);
	}
	function test_offsetUnset_KeyAndValue_UnsetsPressorPrefixedKeyOnContainerWithValue()
	{
		$pressor = $this->makePressor();
		$this->app['pressor.foo'] = true;
		unset($pressor['foo']);

		$result = isset($this->app['pressor.foo']);

		$this->assertFalse($result);
	}


/*
*/
}
