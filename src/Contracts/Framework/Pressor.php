<?php namespace Pressor\Contracts\Framework;

interface Pressor {

	/**
	 * get the parent container
	 * @return Illuminate\Container\Container
	 */
	public function getContainer();

}
