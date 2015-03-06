<?php namespace Pressor\Hooks;
use Pressor\Contracts\Hooks\Factory as FactoryContract;
use Pressor\Support\Traits\HasProxyTrait;
use Pressor\Contracts\Proxy\Proxy;
use Pressor\Contracts\Hooks\Callbacks\Callback;

class Factory implements FactoryContract {
	use HasProxyTrait;

	/**
	 * tracks if this has been bound
	 * @var boolean
	 */
	protected $bound = false;

	/**
	 * array of deferred hooks that haven't bound
	 * @var array
	 */
	protected $deferred = array();

	/**
	 * array of completed Wordpress events
	 * @var array
	 */
	protected $events = array(
		'muplugins_loaded' => false,
		'plugins_loaded' => false,
		'setup_theme' => false,
		'after_setup_theme' => false,
		'init' => false,
		'wp_loaded' => false,
		'admin_init' => false,
	);

	public function __construct(Proxy $proxy = null)
	{
		if ($proxy) $this->setProxy($proxy);
	}

	/**
	 * get the events
	 * @return  array
	 */
	public function getEvents()
	{
		return $this->events;
	}

	/**
	 * mark an event as completed
	 * @param  string $event
	 */
	public function markEventComplete($event)
	{
		$this->events[$event] = true;
	}

	/**
	 * get the deferred hooks
	 * @return  array
	 */
	public function getDeferred()
	{
		return $this->deferred;
	}

	/**
	 * create a wordpress action
	 * @param  string $event
	 * @param  array|string $callback
	 * @param  int $priority
	 * @param  int $acceptedArgs
	 * @param  array $args
	 * @return Pressor\Hooks\Action;
	 */
	public function action($event, $callback = null, $priority = null, $acceptedArgs = null, array $args = array())
	{
		return $this->makeAndTriageCallback(__FUNCTION__, $event, $callback,  $priority, $acceptedArgs, $args);
	}

	/**
	 * create a wordpress filter
	 * @param  string $event
	 * @param  array|string $callback
	 * @param  int $priority
	 * @param  int $acceptedArgs
	 * @param  array $args
	 * @return Pressor\Hooks\Filter;
	 */
	public function filter($event, $callback = null, $priority = null, $acceptedArgs = null, array $args = array())
	{
		return $this->makeAndTriageCallback(__FUNCTION__, $event, $callback,  $priority, $acceptedArgs, $args);
	}

	/**
	 * remove a wordpress action
	 * @param  string $event
	 * @param  array|string $callback
	 * @param  int $priority
	 * @return boolean
	 */
	public function remove($event, $callback = null, $priority = 10)
	{
		return $this->makeAndTriageCallback(__FUNCTION__, $event, $callback,  $priority);
	}

	/**
	 * make the hook
	 * @param  string $type
	 * @param  string $event
	 * @param  string|array $callback
	 * @param  int $priority
	 * @param  int $acceptedArgs
	 * @param  array $args
	 * @return array|Pressor\Contracts\Hooks\Callback;
	 */
	protected function makeAndTriageCallback($type, $event, $callback, $priority = null, $acceptedArgs = null, array $args = array())
	{
		if (is_array($event))
		{
			$callbacks = array();
			foreach ($event as $args)
			{
				array_unshift($args, $type);
				$callbacks[] = call_user_func_array(array($this, 'makeAndTriageCallback'), $args);
			}
			return $callbacks;
		}
		$callback = $this->makeCallback($type, $event, $callback, $priority, $acceptedArgs, $args);
		$this->deferOrBindCallback($callback);
		return $callback;
	}

	protected function makeCallback($type, $event, $callback, $priority, $acceptedArgs, $args)
	{
		list($priority, $acceptedArgs, $args) = $this->extractHookParams(compact('priority', 'acceptedArgs', 'args'));
		$class = __NAMESPACE__ . '\\Callbacks\\' . ucfirst($type);
		return new $class($event, $callback, $priority, $acceptedArgs, $args);
	}

	/**
	 * extract the hook arguments
	 * @param  array $params
	 * @return array
	 */
	protected function extractHookParams(array $params)
	{
		$priority = 10;
		$acceptedArgs = 1;
		foreach ($params as $key => $value)
		{
			if (is_array($value))
			{
				$args = $value;
				break;
			}
			if (!is_null($value))
			{
				${$key} = $value;
			}
		}
		return array($priority, $acceptedArgs, $args);
	}

	/**
	 * triage the hook by regsitering it if bound or marking it deferred
	 * @param  Pressor\Contracts\Hooks\Callbacks\Callback $callback;
	 */
	protected function deferOrBindCallback(Callback $callback)
	{
		if (!$this->bound)
		{
			$this->deferred[] = $callback;
			return;
		}
		$this->validateEventNotComplete($callback->getEvent());
		$this->bindCallback($callback);
	}

	/**
	 * ensure that we aren't trying to bind to an event that has already fired
	 * @param  string $event
	 * @throws LogicException
	 */
	protected function validateEventNotComplete($event)
	{
		if (!empty($this->events[$event]))
		{
			throw new \LogicException('Cannot register hook on completed event [' . $event . ']');
		}
	}

	/**
	 * register the base Wordpress events so we can ensure we don't hook into a completed event
	 * @return void
	 */
	public function registerBaseCallbacks()
	{
		$this->validateNotBound();
		foreach ($this->events as $event => $completed)
		{
			// bind to a late priority so that other callbacks can finish before the event is considered complete
			$this->action($event, array($this, 'markEventComplete'), 999, array($event));
		}
	}

	/**
	 * bind this instance
	 */
	public function bind()
	{
		$this->validateNotBound();
		foreach ($this->deferred as $callback)
		{
			$this->bindCallback($callback);
		}
		// unset the deferred to save a few bytes
		$this->deferred = array();
		$this->bound = true;
	}

	protected function validateNotBound()
	{
		if ($this->bound)
		{
			throw new \LogicException('Pressor hooks factory already bound');
		}
	}

	protected function bindCallback(Callback $callback)
	{
		$callback->register($this->proxy);
	}

}
