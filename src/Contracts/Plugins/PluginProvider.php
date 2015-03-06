<?php namespace Pressor\Contracts\Plugins;
use Pressor\Contracts\Framework\Request\Context as RequestContext;

interface PluginProvider {

	/**
	 * regsiter the plugin
	 * @return void
	 */
	public function register();

	/**
	 * should the plugin load given the request?
	 * @param  Pressor\Contracts\Framework\Request\Context $request
	 * @return boolean
	 */
	public function shouldLoadOnRequest(RequestContext $request);

	/**
	 * bind the plugin
	 * @return void
	 */
	public function bindPlugin();
}
