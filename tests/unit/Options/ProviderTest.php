<?php namespace Pressor\Options;
use Pressor\Testing\TestCase;

class ProviderTest extends TestCase {

	function test_construct_HooksFactory_ReturnsInstanceOfProviderContract()
	{
		$result = new Provider($hooksFactory = $this->fakePressorHooks());

		$this->assertInstanceOf('Pressor\Contracts\Options\Provider', $result);
	}
	function test_construct_HooksFactory_SetsHooks()
	{
		$provider = new Provider($hooksFactory = $this->fakePressorHooks());

		$result = $provider->getHooksFactory();

		$this->assertEquals($hooksFactory, $result);
	}
	protected function makeProvider()
	{
		$provider = new Provider($fakeHooks = $this->fakePressorHooks());
		$fakeHooks->shouldReceive('filter')->byDefault();
		return $provider;
	}
	function test_set_KeyAndValue_AddsKeyAndValueToData()
	{
		$provider = $this->makeProvider();
		$provider->set('foo', 'foo value');

		$result = $provider->getData();

		$this->assertEquals(array('foo' => 'foo value'), $result);
	}
	function test_set_KeyValueAndPrefix_AddsPrefixedKeyAndValueToData()
	{
		$provider = $this->makeProvider();
		$provider->set('foo', 'foo value', 'prefix_');

		$result = $provider->getData();

		$this->assertEquals(array('prefix_foo' => 'foo value'), $result);
	}
	function test_set_KeyValueAsFalseAndPrefix_ThrowsLogicException()
	{
		$provider = $this->makeProvider();

		$this->setExpectedException('LogicException', 'Setting key [prefix_foo] as false will have no effect');

		$provider->set('foo', false, 'prefix_');
	}
	function test_set_KeyAndValue_CallsFilterOnPressorHooksWitKeyAndArrayContainingSelfAndGetPriority0AndArrayWithKey()
	{
		$provider = $this->makeProvider();

		$provider->getHooksFactory()->shouldReceive('filter')->once()->with('foo', array($provider, 'get'), 0, array('foo'));

		$provider->set('foo', 'foo value');
	}
	function test_set_KeyValueAndPrefix_CallsFilterOnPressorHooksWitPrefixKeyAndArrayContainingSelfAndGetPriority0AndArrayWithPrefixKey()
	{
		$provider = $this->makeProvider();

		$provider->getHooksFactory()->shouldReceive('filter')->once()->with('prefix_foo', array($provider, 'get'), 0, array('prefix_foo'));

		$provider->set('foo', 'foo value', 'prefix_');
	}
	function test_set_KeyAlreadySetWithNewValue_UpdatesDataKeyValue()
	{
		$provider = $this->makeProvider();
		$provider->set('foo', 'foo value');
		$provider->set('foo', 'new foo value');

		$result = $provider->getData();

		$this->assertEquals(array('foo' => 'new foo value'), $result);
	}
	function test_set_KeyAlreadySetWithNewValue_CallsFilterOnPressorHooksWitKeyAndarrayContainingSelfAndGetPriority0AndArrayWithKeyOnlyOnce()
	{
		$provider = $this->makeProvider();

		$provider->getHooksFactory()->shouldReceive('filter')->once()->with('foo', array($provider, 'get'), 0, array('foo'));

		$provider->set('foo', 'foo value');
		$provider->set('foo', 'new foo value');
	}
	function test_set_Array_SetsDataWithArray()
	{
		$provider = $this->makeProvider();
		$provider->set($data = array('foo' => 'foo value', 'bar' => 'bar value'));

		$result = $provider->getData();

		$this->assertEquals($data, $result);
	}
	function test_set_ArrayAndPrefix_SetsDataWithArrayOfPrefixedKeys()
	{
		$provider = $this->makeProvider();
		$provider->set(array('foo' => 'foo value', 'bar' => 'bar value'), 'prefix_');

		$result = $provider->getData();

		$this->assertEquals(array('prefix_foo' => 'foo value', 'prefix_bar' => 'bar value'), $result);
	}
	function test_set_ArrayValueAsNullAndPrefix_SetsDataWithArrayOfPrefixedKeys()
	{
		$provider = $this->makeProvider();
		$provider->set(array('foo' => 'foo value', 'bar' => 'bar value'), null, 'prefix_');

		$result = $provider->getData();

		$this->assertEquals(array('prefix_foo' => 'foo value', 'prefix_bar' => 'bar value'), $result);
	}
	function test_set_Array_CallsFilterOnPressorHooksWitKeyAndArrayContainingSelfAndGetPriority0AndArrayContainingKey()
	{
		$provider = $this->makeProvider();

		$provider->getHooksFactory()->shouldReceive('filter')->once()->with('foo', array($provider, 'get'), 0, array('foo'));

		$provider->set($data = array('foo' => 'foo value'));
	}
	function test_set_ArrayAndPrefix_CallsFilterOnPressorHooksWitPrefixKeyAndArrayContainingSelfAndGetPriority0AndArrayContainingPrefixKey()
	{
		$provider = $this->makeProvider();

		$provider->getHooksFactory()->shouldReceive('filter')->once()->with('prefix_foo', array($provider, 'get'), 0, array('prefix_foo'));

		$provider->set($data = array('foo' => 'foo value'), 'prefix_');
	}
	function test_set_ArrayValueAsNullAndPrefix_CallsFilterOnPressorHooksWitPrefixKeyAndArrayContainingSelfAndGetPriority0AndArrayContainingPrefixKey()
	{
		$provider = $this->makeProvider();

		$provider->getHooksFactory()->shouldReceive('filter')->once()->with('prefix_foo', array($provider, 'get'), 0, array('prefix_foo'));

		$provider->set($data = array('foo' => 'foo value'), null, 'prefix_');
	}
	function test_get_KeyWhenSetCalledWithKeyAndValue_ReturnsValueOfKey()
	{
		$provider = $this->makeProvider();
		$provider->set('foo', 'foo value');

		$result = $provider->get('foo');

		$this->assertEquals('foo value', $result);
	}
	function test_get_KeyWhenNotSet_ReturnsFalse()
	{
		$provider = $this->makeProvider();

		$result = $provider->get('foo');

		$this->assertFalse($result);
	}
// pre-defined wordpress prefixes
	function test_option_KeyAndValue_SetsDataWithPreOptionKeyAndValue()
	{
		$provider = $this->makeProvider();
		$provider->option('foo', 'foo value');

		$result = $provider->getData();

		$this->assertEquals(array('pre_option_foo' => 'foo value'), $result);
	}
	function test_transient_KeyAndValue_SetsDataWithPreTransientKeyAndValue()
	{
		$provider = $this->makeProvider();
		$provider->transient('foo', 'foo value');

		$result = $provider->getData();

		$this->assertEquals(array('pre_transient_foo' => 'foo value'), $result);
	}
	function test_siteTransient_KeyAndValue_SetsDataWithPreSiteTransientKeyAndValue()
	{
		$provider = $this->makeProvider();
		$provider->siteTransient('foo', 'foo value');

		$result = $provider->getData();

		$this->assertEquals(array('pre_site_transient_foo' => 'foo value'), $result);
	}
	function test_invalidMethod_KeyAndValue_ThrowsBadMethodCallException()
	{
		$provider = $this->makeProvider();

		$this->setExpectedException('BadMethodCallException', 'The method [invalidMethod] does not exist');

		$provider->invalidMethod('foo', 'foo value');
	}

/*
*/
}
