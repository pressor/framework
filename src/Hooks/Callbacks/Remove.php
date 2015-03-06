<?php namespace Pressor\Hooks\Callbacks;
use Pressor\Contracts\Hooks\Callbacks\Callback as CallbackContract;
use Pressor\Contracts\Proxy\Proxy;

class Remove implements CallbackContract {
	use CallbackAttributableTrait;

	public function __construct($event, $callback, $priority)
	{
		$this->setValidEvent($event);
		$this->setValidCallback($callback);
		$this->setValidPriority($priority);
	}

	/**
	 * register the hook with wordpress
	 * @param  Pressor\Contracts\Proxy\Proxy $proxy
	 * @throws LogicException
	 */
	public function register(Proxy $proxy = null)
	{
		$event = $this->event;
		$callback = $this->callback;
		$priority = $this->priority;
		if ($proxy)
		{
			$success = $proxy->removeFilter($event, $callback, $priority);
		}
		else
		{
			$success = remove_filter($event, $callback, $priority);
		}
		if (!$success)
		{
			throw new \LogicException('Hook on event [' . $event . '] for callback [' . $callback . '] with priority [' . $priority . '] could not be removed');
		}
	}

}
