<?php namespace Pressor\Contracts\Constants;

interface Provider {

	/**
	 * set the data
	 * @param  string|array $key
	 * @param  mixed $value
	 * @return Pressor\Constants\Loader
	 */
	public function set($key, $value = null);

	/**
	 * bind the data to Wordpress by defining the constants
	 * @return Pressor\Constants\Loader
	 */
	public function bind();

	/**
	 * get a constant's value
	 * @param  string $key
	 * @return mixed
	 */
	public function get($key);

}
