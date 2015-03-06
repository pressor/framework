<?php namespace Pressor\Hooks\Callbacks;
use Pressor\Testing\TestCase;

class FilterTest extends TestCase {

	protected $useSpy = true;

	protected function makeFilter()
	{
		return new Filter('event', 'some_function', 1, 2, array());
	}

	function test_construct_Params_ReturnsInstanceOfActionOrFilter()
	{
		$result = $this->makeFilter();

		$this->assertInstanceOf('Pressor\Hooks\Callbacks\ActionOrFilter', $result);
	}

	function test_register_NoParamsWhenEventCallbackPriorityAndAcceptedArgsSet_CallsAddFilterWithEventCallbackPriorityAndAcceptedArgs()
	{
		$action = $this->makeFilter();

		$action->register();

		$this->assertFunctionLastCalledWith('add_filter', array('event', array($action, 'run'), 1, 2));
	}

	function test_register_ProxyPassedWhenEventCallbackPriorityAndAcceptedArgsSet_CallsAddFilterOnProxyWithEventCallbackPriorityAndAcceptedArgs()
	{
		$action = $this->makeFilter();
		$mockProxy = $this->fakePressorProxy();

		$mockProxy->shouldReceive('addFilter')->once()->with('event', array($action, 'run'), 1, 2);

		$action->register($mockProxy);
	}

}

function add_filter()
{
	\UnitTesting\FunctionSpy\Spy::add_filter();
}

