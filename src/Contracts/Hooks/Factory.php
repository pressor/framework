<?php namespace Pressor\Contracts\Hooks;

interface Factory {

	/**
	 * create a wordpress action
	 * @param  string $event
	 * @param  array|string $callback
	 * @param  int $priority
	 * @param  int $acceptedArgs
	 * @param  array $args
	 * @return Pressor\Hooks\Action;
	 */
	public function action($event, $callback = null, $priority = null, $acceptedArgs = null, array $args = array());

	/**
	 * create a wordpress filter
	 * @param  string $event
	 * @param  array|string $callback
	 * @param  int $priority
	 * @param  int $acceptedArgs
	 * @param  array $args
	 * @return Pressor\Hooks\Filter;
	 */
	public function filter($event, $callback = null, $priority = null, $acceptedArgs = null, array $args = array());

	/**
	 * remove a wordpress action
	 * @param  string $event
	 * @param  array|string $callback
	 * @param  int $priority
	 * @return boolean
	 */
	public function remove($event, $callback = null, $priority = 10);

	/**
	 * bind this instance
	 */
	public function bind();

}
