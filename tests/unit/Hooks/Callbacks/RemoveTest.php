<?php namespace Pressor\Hooks\Callbacks;
use Pressor\Testing\TestCase;

class RemoveTest extends TestCase {

	protected $useSpy = true;

	function test_construct_EventCallbackAndPriority_ReturnsInstanceOfCallbackContract()
	{
		$result = new Remove('event', 'some_function', 1);

		$this->assertInstanceOf('Pressor\Contracts\Hooks\Callbacks\Callback', $result);
	}
	function test_construct_EventCallbackAndPriority_SetsEventCalbackAndPriorityOnRemove()
	{
		$remove = new Remove('event', 'some_function', 1);

		$result = array($remove->getEvent(), $remove->getCallback(), $remove->getPriority());

		$this->assertEquals(array('event', 'some_function', 1), $result);
	}
	function test_construct_InvalidEventCallbackAndPriority_ThrowsInvalidArgumentException()
	{
		$this->setExpectedException('InvalidArgumentException', 'The event is invalid');

		new Remove(array(), 'some_function', 1);
	}
	function test_construct_EventCallbackAndInvalidPriority_ThrowsInvalidArgumentException()
	{
		$this->setExpectedException('InvalidArgumentException', 'The priority [invalid] is invalid');

		new Remove('event', 'some_function', 'invalid');
	}

	protected function makeRemove()
	{
		return new Remove('event', 'some_function', 1);
	}

	function test_register_NoParamsWhenEventCallbackAndPrioritySetAndPressor_CallsRemoveFilterWithEventCallbackAndPriority()
	{
		$remove = $this->makeRemove();
		$this->spy['remove_filter'] = true;

		$remove->register();

		$this->assertFunctionLastCalledWith('remove_filter', array('event', 'some_function', 1));
	}
	function test_register_WhenRemoveFilterReturnsFalse_ThrowsLogicException()
	{
		$remove = $this->makeRemove();
		$this->spy['remove_filter'] = false;

		$this->setExpectedException('LogicException', 'Hook on event [event] for callback [some_function] with priority [1] could not be removed');

		$remove->register();
	}
	function test_register_ProxyWhenEventCallbackAndPrioritySet_CallsRemoveFilterOnProxyWithEventCallbackAndPriority()
	{
		$remove = $this->makeRemove();
		$mockProxy = $this->fakePressorProxy();

		$mockProxy->shouldReceive('removeFilter')->once()->with('event', 'some_function', 1)->andReturn(true);

		$remove->register($mockProxy);
	}
	function test_register_ProxyWhenRemoveFilterReturnsFalse_ThrowsLogicException()
	{
		$remove = $this->makeRemove();
		$stubProxy = $this->fakePressorProxy();
		$stubProxy->shouldReceive('removeFilter')->andReturn(false);

		$this->setExpectedException('LogicException', 'Hook on event [event] for callback [some_function] with priority [1] could not be removed');

		$remove->register($stubProxy);
	}

/*
*/
}

function remove_filter()
{
	return \UnitTesting\FunctionSpy\Spy::remove_filter();
}

