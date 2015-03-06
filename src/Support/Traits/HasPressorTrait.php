<?php namespace Pressor\Support\Traits;
use Pressor\Contracts\Framework\Pressor;

trait HasPressorTrait {

	/**
	 * pressor instance
	 * @var Pressor\Contracts\Framework\Pressor
	 */
	protected $pressor;

	/**
	 * set pressor instance
	 * @param  Pressor\Contracts\Framework\Pressor $pressor
	 * @return void
	 */
	public function setPressor(Pressor $pressor)
	{
		$this->pressor = $pressor;
	}

	/**
	 * get pressor instance
	 * @return Pressor\Contracts\Framework\Pressor $pressor
	 */
	public function getPressor()
	{
		return $this->pressor;
	}

}
