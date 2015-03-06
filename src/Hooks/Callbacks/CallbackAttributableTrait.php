<?php namespace Pressor\Hooks\Callbacks;

trait CallbackAttributableTrait {

	/**
	 * event we're observing
	 */
	protected $event;

	/**
	 * the callback for the hook
	 * string|array
	 */
	protected $callback;

	/**
	 * the priority for the hook
	 */
	protected $priority;

	/**
	 * set a valid event key
	 * @param  mixed $event
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function setValidEvent($event)
	{
		if (!is_string($event))
		{
			throw new \InvalidArgumentException('The event is invalid');
		}
		$this->event = $event;
	}

	/**
	 * ensure the callback is valid
	 * @param  mixed $callback
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	protected function setValidCallback($callback)
	{
		if (is_array($callback) and !is_callable($callback))
		{
			$callback = get_class($callback[0]) . '@' . $callback[1];
			throw new \InvalidArgumentException('The callback function [' . $callback . '] is not callable');
		}
		$this->callback = $callback;
	}

	/**
	 * ensure the priority is valid
	 * @param  mixed $priority
	 * @return int
	 * @throws InvalidArgumentException
	 */
	protected function setValidPriority($priority)
	{
		if (!$this->validateInteger($priority))
		{
			throw new \InvalidArgumentException('The priority [' . $priority . '] is invalid');
		}
		$this->priority = $priority;
	}

	protected function validateInteger($subject)
	{
		return is_int($subject);
	}

	/**
	 * get the event
	 * @return string
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 * get the callback
	 * @return mixed
	 */
	public function getCallback()
	{
		return $this->callback;
	}

	/**
	 * get the priority
	 * @return array
	 */
	public function getPriority()
	{
		return $this->priority;
	}
}
