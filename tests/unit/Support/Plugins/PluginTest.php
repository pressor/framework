<?php namespace Pressor\Support\Plugins;
use Pressor\Testing\TestCase;

class PluginTest extends TestCase {

	function test_construct_NoParams_ReturnsInstanceOfPluginContract()
	{
		$result = new PluginTestStub;

		$this->assertInstanceOf('Pressor\Contracts\Plugins\Plugin', $result);
	}
	protected function makePlugin()
	{
		return new PluginTestStub;
	}
// setConfigs
	function test_setConfigs_Array_SetsConfigs()
	{
		$plugin = $this->makePlugin();
		$plugin->boot();

		$this->setExpectedException('LogicException', 'Plugin already booted');

		$plugin->setConfigs(array());
	}
	function test_setConfigs_ArrayWhenBootAlreadyCalled_ThrowsLogicException()
	{
		$plugin = $this->makePlugin();
		$plugin->setConfigs($configs = array('configs'));

		$result = $plugin->getConfigs();

		$this->assertEquals($configs, $result);
	}
// configure
	function test_configure_NestedKeyAndValue_SetsConfigsWithNestedKey()
	{
		$plugin = $this->makePlugin();
		$plugin->configure('foo.bar', 'baz');

		$result = $plugin->getConfigs();

		$this->assertEquals(array('foo' => array('bar' => 'baz')), $result);
	}
	function test_configure_WhenBootAlreadyCalled_ThrowsLogicException()
	{
		$plugin = $this->makePlugin();
		$plugin->boot();

		$this->setExpectedException('LogicException', 'Plugin already booted');

		$plugin->configure('foo', 'bar');
	}
// boot
	function test_boot_NoParams_CallsLoadOnSelfWithNoArgs()
	{
		$plugin = $this->makePlugin();
		$plugin->boot();

		$result = $plugin->getLastMethodCall('load');

		$this->assertEquals(array(), $result);
	}
	function test_boot_WhenBootAlreadyCalled_ThrowsLogicException()
	{
		$plugin = $this->makePlugin();
		$plugin->boot();

		$this->setExpectedException('LogicException', 'Plugin already booted');

		$plugin->boot();
	}

}

class PluginTestStub extends Plugin {
	use \UnitTesting\ClassSpy\WatchableTrait;

	protected function load()
	{
		$this->trackMethodCall();
	}

}
