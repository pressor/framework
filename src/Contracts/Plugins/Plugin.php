<?php namespace Pressor\Contracts\Plugins;

interface Plugin {

	/**
	 * boot the plugin
	 * @return void
	 */
	public function boot();

}
