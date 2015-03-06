<?php namespace Pressor\Contracts\Options;

interface Provider {

	/**
	 * set a key with optional prefix with a value and register the filter
	 * @param  string $key
	 * @param  mixed $value
	 * @param  string $prefix
	 * @throws LogicException
	 */
	public function set($key, $value = null, $prefix = '');

	/**
	 * get the value from data. called by wordpress apply_filters
	 * @param  string $key
	 * @return mixed
	 */
	public function get($key);

}
