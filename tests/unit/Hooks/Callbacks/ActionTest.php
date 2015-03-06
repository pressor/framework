<?php namespace Pressor\Hooks\Callbacks;
use Pressor\Testing\TestCase;

class ActionTest extends TestCase {

	protected $useSpy = true;

	protected function makeAction()
	{
		return new Action('event', 'some_function', 1, 2, array());
	}

	function test_construct_Params_ReturnsInstanceOfActionOrFilter()
	{
		$result = $this->makeAction();

		$this->assertInstanceOf('Pressor\Hooks\Callbacks\ActionOrFilter', $result);
	}

	function test_register_NoParamsWhenEventCallbackPriorityAndAcceptedArgsSet_CallsAddActionWithEventCallbackPriorityAndAcceptedArgs()
	{
		$action = $this->makeAction();

		$action->register();

		$this->assertFunctionLastCalledWith('add_action', array('event', array($action, 'run'), 1, 2));
	}
	function test_register_ProxyPassedWhenEventCallbackPriorityAndAcceptedArgsSet_CallsAddActionOnProxyWithEventCallbackPriorityAndAcceptedArgs()
	{
		$action = $this->makeAction();
		$mockProxy = $this->fakePressorProxy();

		$mockProxy->shouldReceive('addAction')->once()->with('event', array($action, 'run'), 1, 2);

		$action->register($mockProxy);
	}

}

function add_action()
{
	\UnitTesting\FunctionSpy\Spy::add_action();
}

