<?php namespace Pressor\Contracts\Path;

interface Provider {

	/**
	 * get the wordpress path
	 * @param  string $path
	 * @return string|null
	 */
	public function wordpress($path = null);

}
