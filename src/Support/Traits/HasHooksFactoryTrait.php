<?php namespace Pressor\Support\Traits;
use Pressor\Contracts\Hooks\Factory as HooksFactory;

trait HasHooksFactoryTrait {

	/**
	 * instance of hooks factory
	 * @var Pressor\Contracts\Hooks\Factory
	 */
	protected $hooksFactory;

	/**
	 * set hooks factory instance
	 * @param  Pressor\Contracts\Hooks\Factory $hooksFactory
	 * @return void
	 */
	public function setHooksFactory(HooksFactory $hooksFactory)
	{
		$this->hooksFactory = $hooksFactory;
	}

	/**
	 * get hooks factory instance
	 * @return Pressor\Contracts\Hooks\Factory
	 */
	public function getHooksFactory()
	{
		return $this->hooksFactory;
	}


}
