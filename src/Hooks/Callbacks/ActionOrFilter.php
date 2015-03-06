<?php namespace Pressor\Hooks\Callbacks;
use Pressor\Contracts\Hooks\Callbacks\Callback as CallbackContract;
use Pressor\Contracts\Proxy\Proxy;

abstract class ActionOrFilter implements CallbackContract {
	use CallbackAttributableTrait;

	/**
	 * accepted args for hook's callabck
	 */
	protected $acceptedArgs;

	/**
	 * extra arguments we can unshift onto the method call
	 */
	protected $args;

	public function __construct($event, $callback, $priority, $acceptedArgs, array $args)
	{
		$this->setValidEvent($event);
		$this->setValidCallback($callback);
		$this->setValidPriority($priority);
		$this->setValidAcceptedArgs($acceptedArgs);
		$this->args = $args;
	}

	/**
	 * ensure the accepted args is valid
	 * @param  mixed $acceptedArgs
	 * @return int
	 * @throws InvalidArgumentException
	 */
	protected function setValidAcceptedArgs($acceptedArgs)
	{
		if (!$this->validateInteger($acceptedArgs))
		{
			throw new \InvalidArgumentException('The accepted args [' . $acceptedArgs . '] is invalid');
		}
		$this->acceptedArgs = $acceptedArgs;
	}

	/**
	 * get the number of accepted args
	 * @return array
	 */
	public function getAcceptedArgs()
	{
		return $this->acceptedArgs;
	}

	/**
	 * get the extra prepended arguments
	 * @return array
	 */
	public function getArgs()
	{
		return $this->args;
	}

	/**
	 * register the hook with wordpress
	 * @param  Pressor\Contracts\Proxy\Proxy $proxy
	 * @return void
	 */
	public function register(Proxy $proxy = null)
	{
		return $this->bind($this->event, array($this, 'run'), $this->priority, $this->acceptedArgs, $proxy);
	}
	/**
	 * actually register the hook on wordpress
	 * @param  string $event
	 * @param  array $callback
	 * @param  int $priority
	 * @param  int $acceptedArgs
	 * @param  Pressor\Proxy\Proxy $proxy
	 * @return boolean
	 */
	protected abstract function bind($event, array $callback, $priority, $acceptedArgs, Proxy $proxy = null);

	/**
	 * run the callback from a wordpress hook
	 * @param  mixed
	 * @return mixed
	 */
	public function run()
	{
		$params = array_merge($this->args, func_get_args());
		return call_user_func_array($this->callback, $params);
	}
}
