<?php namespace Pressor\Hooks\Callbacks;
use Pressor\Testing\TestCase;
use Pressor\Contracts\Proxy\Proxy;

class ActionOrFilterTest extends TestCase {

	protected $useSpy = true;

	public function tearDown()
	{
		parent::tearDown();
		HookTestStubClass::flushStatic();
	}

	function test_construct_EventCallbackPriorityAcceptedArgsAndArgs_ReturnsInstanceOCallbackContract()
	{
		$result = new FakeHook('event', 'some_function', 1, 2, array('foo'));

		$this->assertInstanceOf('Pressor\Contracts\Hooks\Callbacks\Callback', $result);
	}
	function test_construct_EventCallbackPriorityAcceptedArgsAndArgs_SetsEventCallbackPriorityAcceptedArgsAndArgs()
	{
		$hook = new FakeHook('event', 'some_function', 1, 2, array('foo'));

		$result = array($hook->getEvent(), $hook->getCallback(), $hook->getPriority(), $hook->getAcceptedArgs(), $hook->getArgs());

		$this->assertEquals(array('event', 'some_function', 1, 2, array('foo')), $result);
	}
	function test_construct_InvalidEventCallbackPriorityAcceptedArgsAndArgs_ThrowsInvalidArgumentException()
	{
		$this->setExpectedException('InvalidArgumentException', 'The event is invalid');

		new FakeHook(array(), 'some_function', 1, 2, array('foo'));
	}
	function test_construct_EventCallbackIsInstanceMethodThatDoesntExistPriorityAcceptedArgsAndArgs_ThrowsInvalidArgumentException()
	{
		$this->setExpectedException('InvalidArgumentException', 'The callback function [Pressor\Hooks\Callbacks\HookTestStubClass@invalidMethod] is not callable');

		$instance = new HookTestStubClass();
		new FakeHook('event', array($instance, 'invalidMethod'), 1, 2, array('foo'));
	}
	function test_construct_EventCallbackInvalidPriorityAcceptedArgsAndArgs_ThrowsInvalidArgumentException()
	{
		$this->setExpectedException('InvalidArgumentException', 'The priority [invalid] is invalid');

		new FakeHook('event', 'some_function', 'invalid', 2, array('foo'));
	}
	function test_construct_EventCallbackPriorityInvalidAcceptedArgsAndArgs_ThrowsInvalidArgumentException()
	{
		$this->setExpectedException('InvalidArgumentException', 'The accepted args [invalid] is invalid');

		new FakeHook('event', 'some_function', 1, 'invalid', array('foo'));
	}

// run
	function test_run_NoParamsWhenMethodSetAsFunction_CallsMethodWithNoArgs()
	{
		$hook = new FakeHook('event', 'Pressor\Hooks\Callbacks\some_function', 1, 2, array());

		$hook->run();

		$this->assertFunctionLastCalledWith('some_function', array());
	}
	function test_run_ParamsWhenMethodSetAsFunction_CallsMethodWithParams()
	{
		$hook = new FakeHook('event', 'Pressor\Hooks\Callbacks\some_function', 1, 2, array());

		$hook->run('foo', 'bar');

		$this->assertFunctionLastCalledWith('some_function', array('foo', 'bar'));
	}
	function test_run_ParamsWhenArgsSetAndMethodSetAsFunction_CallsMethodWithArgsAndParams()
	{
		$hook = new FakeHook('event', 'Pressor\Hooks\Callbacks\some_function', 1, 2, array('arg1', 'arg2'));

		$hook->run('foo', 'bar');

		$this->assertFunctionLastCalledWith('some_function', array('arg1', 'arg2', 'foo', 'bar'));
	}
	function test_run_WhenMethodReturnsResult_ReturnsResult()
	{
		$hook = new FakeHook('event', 'Pressor\Hooks\Callbacks\some_function', 1, 2, array());
		$this->spy['some_function'] = 'result';

		$result = $hook->run();

		$this->assertEquals('result', $result);
	}
	function test_run_ParamsWhenArgsSetAndMethodSetAsStaticMethod_CallsStaticMethodWithArgsAndParams()
	{
		$hook = new FakeHook('event', 'Pressor\Hooks\Callbacks\HookTestStubClass::doStatic', 1, 2, array('arg1', 'arg2'));
		$hook->run('foo', 'bar');

		$result = HookTestStubClass::getLastStaticMethodCall('doStatic');

		$this->assertEquals(array('arg1', 'arg2', 'foo', 'bar'), $result);
	}
	function test_run_ParamsWhenArgsSetAndMethodSetAsInstanceMethod_CallsInstanceMethodWithArgsAndParams()
	{
		$instance = new HookTestStubClass();
		$hook = new FakeHook('event', array($instance, 'doInstance'), 1, 2, array('arg1', 'arg2'));
		$hook->run('foo', 'bar');

		$result = $instance->getLastMethodCall('doInstance');

		$this->assertEquals(array('arg1', 'arg2', 'foo', 'bar'), $result);
	}
/*
*/
}

class FakeHook extends ActionOrFilter {
	protected function bind($event, array $callback, $priority, $acceptedArgs, Proxy $proxy = null)
	{
	}
}

class HookTestStubClass {
	use \UnitTesting\ClassSpy\WatchableTrait;

	public static function doStatic()
	{
		self::trackStaticMethodCall();
	}

	public function doInstance()
	{
		$this->trackMethodCall();
	}

}

function some_function() {
	return \UnitTesting\FunctionSpy\Spy::some_function();
}
