<?php namespace Pressor\Support\Traits;
use Illuminate\Container\Container;

trait HasContainerTrait {

	/**
	 * container instance
	 * @var Illuminate\Container\Container
	 */
	protected $container;

	/**
	 * set container instance
	 * @param  Illuminate\Container\Container $container
	 * @return void
	 */
	public function setContainer(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * get container instance
	 * @return Illuminate\Container\Container $container
	 */
	public function getContainer()
	{
		return $this->container;
	}

}
