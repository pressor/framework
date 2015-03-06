<?php namespace Pressor\Support\Traits;
use Pressor\Contracts\Proxy\Proxy;

trait HasProxyTrait {

	/**
	 * proxy instance
	 * @var Pressor\Contracts\Proxy\Proxy
	 */
	protected $proxy;

	/**
	 * set proxy instance
	 * @param  Pressor\Contracts\Proxy\Proxy $proxy
	 * @return void
	 */
	public function setProxy(Proxy $proxy)
	{
		$this->proxy = $proxy;
	}

	/**
	 * get proxy instance
	 * @return Pressor\Contracts\Proxy\Proxy $proxy
	 */
	public function getProxy()
	{
		return $this->proxy;
	}

}
