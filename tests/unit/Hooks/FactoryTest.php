<?php namespace Pressor\Hooks;
use Pressor\Testing\TestCase;
use Pressor\Hooks\Callbacks\Action;
use Pressor\Hooks\Callbacks\Filter;
use Pressor\Hooks\Callbacks\Remove;

class FactoryTest extends TestCase {

	function test_construct_NoParams_ReturnsInstanceOfFactoryContract()
	{
		$result = new Factory();

		$this->assertInstanceOf('Pressor\Contracts\Hooks\Factory', $result);
	}
	function test_construct_NoParams_SetsProxyAsNull()
	{
		$factory = new Factory();

		$result = $factory->getProxy();

		$this->assertNull($result);
	}

	function test_construct_Proxy_SetsProxy()
	{
		$factory = new Factory($proxy = $this->fakePressorProxy());

		$result = $factory->getProxy();

		$this->assertEquals($proxy, $result);
	}

	protected function makeFactory()
	{
		return new Factory();
	}
//  markEventComplete
	function test_markEventComplete_Event_SetsCompletedWithEventAsKeyAndValueAsTrue()
	{
		$factory = $this->makeFactory();
		$factory->markEventComplete('foo');

		$result = $factory->getEvents()['foo'];

		$this->assertTrue($result);
	}
// action
	function test_action_EventAndCallback_ReturnsInstanceOfAction()
	{
		$factory = $this->makeFactory();

		$result = $factory->action('event', 'some_function');

		$this->assertInstanceOf('Pressor\Hooks\Callbacks\Action', $result);
	}
	function test_action_WithParams_SetsActionWithDefaultsOrParams()
	{
		$factory = $this->makeFactory();
		$action1 = $factory->action('event', 'some_function');
		$action2 = $factory->action('event', 'some_function', 1, 2, array('args'));

		$result = array(
			array($action1->getEvent(), $action1->getCallback(), $action1->getPriority(), $action1->getAcceptedArgs(), $action1->getArgs()),
			array($action2->getEvent(), $action2->getCallback(), $action2->getPriority(), $action2->getAcceptedArgs(), $action2->getArgs()),
		);

		$this->assertEquals(array(
			array('event', 'some_function', 10, 1, array()),
			array('event', 'some_function', 1, 2, array('args')),
		), $result);
	}
	function test_action_ArrayOfParams_ReturnsArrayOfActionsWithPropertiesSet()
	{
		$factory = $this->makeFactory();
		$actions = $factory->action(array(
				$first = array('foo_event', 'foo_function', 1),
				$second = array('bar_event', 'bar_function', 2),
			));

		$result = array(
				array($actions[0]->getEvent(), $actions[0]->getCallback(), $actions[0]->getPriority()),
				array($actions[1]->getEvent(), $actions[1]->getCallback(), $actions[1]->getPriority()),
			);

		$this->assertEquals(array($first, $second), $result);
	}
// filter
	function test_filter_EventAndCallback_ReturnsInstanceOfFilter()
	{
		$factory = $this->makeFactory();

		$result = $factory->filter('event', 'some_function');

		$this->assertInstanceOf('Pressor\Hooks\Callbacks\Filter', $result);
	}
	function test_filter_WithParams_SetsActionWithDefaultsOrParams()
	{
		$factory = $this->makeFactory();
		$filter1 = $factory->filter('event', 'some_function');
		$filter2 = $factory->filter('event', 'some_function', 1, 2, array('args'));

		$result = array(
			array($filter1->getEvent(), $filter1->getCallback(), $filter1->getPriority(), $filter1->getAcceptedArgs(), $filter1->getArgs()),
			array($filter2->getEvent(), $filter2->getCallback(), $filter2->getPriority(), $filter2->getAcceptedArgs(), $filter2->getArgs()),
		);

		$this->assertEquals(array(
			array('event', 'some_function', 10, 1, array()),
			array('event', 'some_function', 1, 2, array('args')),
		), $result);
	}
	function test_filter_ArrayOfParams_ReturnsArrayOfActionsWithPropertiesSet()
	{
		$factory = $this->makeFactory();
		$filters = $factory->filter(array(
				$first = array('foo_event', 'foo_function', 1),
				$second = array('bar_event', 'bar_function', 2),
			));

		$result = array(
				array($filters[0]->getEvent(), $filters[0]->getCallback(), $filters[0]->getPriority()),
				array($filters[1]->getEvent(), $filters[1]->getCallback(), $filters[1]->getPriority()),
			);

		$this->assertEquals(array($first, $second), $result);
	}
// remove
	function test_remove_EventAndCallback_ReturnsInstanceOfRemove()
	{
		$factory = $this->makeFactory();

		$result = $factory->remove('event', 'some_function');

		$this->assertInstanceOf('Pressor\Hooks\Callbacks\Remove', $result);
	}
	function test_remove_WithParams_SetsActionWithDefaultsOrParams()
	{
		$factory = $this->makeFactory();
		$remove1 = $factory->remove('event', 'some_function');
		$remove2 = $factory->remove('event', 'some_function', 1, 2, array('args'));

		$result = array(
			array($remove1->getEvent(), $remove1->getCallback(), $remove1->getPriority()),
			array($remove2->getEvent(), $remove2->getCallback(), $remove2->getPriority()),
		);

		$this->assertEquals(array(
			array('event', 'some_function', 10),
			array('event', 'some_function', 1),
		), $result);
	}
	function test_remove_ArrayOfParams_ReturnsArrayOfActionsWithPropertiesSet()
	{
		$factory = $this->makeFactory();
		$removes = $factory->remove(array(
				$first = array('foo_event', 'foo_function', 1),
				$second = array('bar_event', 'bar_function', 2),
			));

		$result = array(
				array($removes[0]->getEvent(), $removes[0]->getCallback(), $removes[0]->getPriority()),
				array($removes[1]->getEvent(), $removes[1]->getCallback(), $removes[1]->getPriority()),
			);

		$this->assertEquals(array($first, $second), $result);
	}
//	bind
	protected function fakeCallback()
	{
		$callback = $this->mock('Pressor\Contracts\Hooks\Callbacks\Callback');
		$callback->shouldReceive('getEvent', 'register')->byDefault();
		return $callback;
	}
	protected function fakeFactoryWithDeferred()
	{
		$factory = new FakeFactory();
		$callback = $this->fakeCallback();
		$factory->setDeferred(array($callback));
		return $factory;
	}
	function test_bind_WhenDeferredSetAsArrayWithHooks_CallsRegisterOnLastHookWithNoArgs()
	{
		$factory = $this->fakeFactoryWithDeferred();
		$mockHook = current($factory->getDeferred());

		$mockHook->shouldReceive('register')->once()->with(null);

		$factory->bind();
	}
	function test_bind_WhenDeferredSetAsArrayWithHooksAndPressorSet_CallsRegisterOnHookWithPressorProxy()
	{
		$factory = $this->fakeFactoryWithDeferred();
		$factory->setProxy($fakeProxy = $this->fakePressorProxy());
		$mockHook = current($factory->getDeferred());

		$mockHook->shouldReceive('register')->once()->with($fakeProxy);

		$factory->bind();
	}
	function test_bind_WhenDeferredSetAsArrayWithHooks_SetsDeferredAsEmptyArray()
	{
		$factory = $this->fakeFactoryWithDeferred();
		$factory->bind();

		$result = $factory->getDeferred();

		$this->assertEquals(array(), $result);
	}
	function test_bind_AlreadyCalled_ThrowsLogicException()
	{
		$factory = $this->fakeFactoryWithDeferred();
		$factory->bind();

		$this->setExpectedException('LogicException', 'Pressor hooks factory already bound');

		$factory->bind();
	}
// after bind
	protected function fakeFactoryAfterBind()
	{
		$factory = $this->fakeFactoryWithDeferred();
		$factory->bind();
		return $factory;
	}
	function test_action_AfterBindCalled_DoesNotAddActionToDeferred()
	{
		$factory = $this->fakeFactoryAfterBind();
		$factory->makeCallbackResult = $this->fakeCallback();
		$factory->action('event', 'some_function');

		$result = $factory->getDeferred();

		$this->assertEquals(array(), $result);
	}
	function test_action_AfterBindCalled_CallsGetEventOnCallbackWithNoArgs()
	{
		$factory = $this->fakeFactoryAfterBind();
		$mockHook = $factory->makeCallbackResult = $this->fakeCallback();

		$mockHook->shouldReceive('getEvent')->once()->withNoArgs();

		$factory->action('event', 'some_function');
	}
	function test_action_AfterBindCalledAndGetEventOnCallbackReturnsCompletedEvent_ThrowsLogicException()
	{
		$factory = $this->fakeFactoryAfterBind();
		$stubHook = $factory->makeCallbackResult = $this->fakeCallback();
		$stubHook->shouldReceive('getEvent')->andReturn('muplugins_loaded');
		$factory->markEventComplete('muplugins_loaded');

		$this->setExpectedException('LogicException', 'Cannot register hook on completed event [muplugins_loaded]');

		$factory->action('muplugins_loaded', 'some_function');
	}
	function test_action_AfterBindCalledWhenPressorNotSet_CallsRegisterOnCallbackWithNull()
	{
		$factory = $this->fakeFactoryAfterBind();
		$mockHook = $factory->makeCallbackResult = $this->fakeCallback();

		$mockHook->shouldReceive('register')->once()->with(null);

		$factory->action('event', 'some_function');
	}
	function test_action_AfterBindCalledWhenPressorSet_CallsRegisterOnCallbackWithPressorProxy()
	{
		$factory = $this->fakeFactoryAfterBind();
		$factory->setProxy($fakeProxy = $this->fakePressorProxy());
		$mockHook = $factory->makeCallbackResult = $this->fakeCallback();

		$mockHook->shouldReceive('register')->once()->with($fakeProxy);

		$factory->action('event', 'some_function');
	}
// registerBaseCallbacks
	function test_registerBaseCallbacks_NoParams_AddsBaseHooksAsInstancesOfActionsWithCorrectAttributesToDeferred()
	{
		$factory = $this->makeFactory();
		$factory->registerBaseCallbacks();
		$deferred = $factory->getDeferred();

		$result = array_map(function($hook)
		{
			return array($hook instanceof Action, $hook->getEvent(), $hook->getPriority(), $hook->getArgs());
		}, $deferred);

		$this->assertEquals(array(
			array(true, 'muplugins_loaded', 999, array('muplugins_loaded')),
			array(true, 'plugins_loaded', 999, array('plugins_loaded')),
			array(true, 'setup_theme', 999, array('setup_theme')),
			array(true, 'after_setup_theme', 999, array('after_setup_theme')),
			array(true, 'init', 999, array('init')),
			array(true, 'wp_loaded', 999, array('wp_loaded')),
			array(true, 'admin_init', 999, array('admin_init')),
		), $result);
	}
	function test_registerBaseCallbacks_WhenBindAlreadyCalled_ThrowsLogicException()
	{
		$factory = $this->makeFactory();
		$factory->bind();

		$this->setExpectedException('LogicException', 'Pressor hooks factory already bound');

		$factory->registerBaseCallbacks();
	}
/*
*/
}

class FakeFactory extends Factory {

	public $makeCallbackResult;

	public function setDeferred(array $deferred)
	{
		$this->deferred = $deferred;
	}

	protected function makeCallback($type, $event, $callback, $priority, $acceptedArgs, $args)
	{
		return $this->makeCallbackResult ? : parent::makeCallback($type, $event, $callback, $priority, $acceptedArgs, $args);
	}
}

function factory_test_callback_stub()
{

}
