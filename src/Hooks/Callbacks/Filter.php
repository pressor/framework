<?php namespace Pressor\Hooks\Callbacks;
use Pressor\Contracts\Proxy\Proxy;

class Filter extends ActionOrFilter {

	/**
	 * actually register the hook on wordpress
	 * @param  string $event
	 * @param  array $callback
	 * @param  int $priority
	 * @param  int $acceptedArgs
	 * @param  Pressor\Proxy\Proxy $proxy
	 */
	protected function bind($event, array $callback, $priority, $acceptedArgs, Proxy $proxy = null)
	{
		if ($proxy)
		{
			$proxy->addFilter($event, $callback, $priority, $acceptedArgs);
			return;
		}
		return add_filter($event, $callback, $priority, $acceptedArgs);
	}

}
