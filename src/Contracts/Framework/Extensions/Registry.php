<?php namespace Pressor\Contracts\Framework\Extensions;
use Pressor\Contracts\Framework\Request\Context as RequestContext;

interface Registry {

	/**
	 * register a plugin on the container
	 * @param  string $class
	 * @return void
	 */
	public function plugin($class);

	/**
	 * add an alias on the container
	 * @param  string $key
	 * @param  string $alias
	 * @return void
	 */
	public function alias($key, $alias);

	/**
	 * bootstrap the registry
	 * @return void
	 */
	public function bootstrap();

	/**
	 * bind the plugins
	 * @return void
	 */
	public function bind(RequestContext $context);

}
