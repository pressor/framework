<?php namespace Pressor\Framework\Request;
use Pressor\Contracts\Framework\Request\Context as ContextContract;
use Pressor\Contracts\Constants\Provider as ConstantsProvider;

class Context implements ContextContract {

	/**
	 * constants provider instance
	 * @var Pressor\Contracts\Constants\Loader
	 */
	protected $constants;

	public function __construct(ConstantsProvider $constants)
	{
		$this->constants = $constants;
	}

	/**
	 * get the constants provider instance
	 * @return Pressor\Contracts\Constants\Loader
	 */
	public function getConstantsProvider()
	{
		return $this->constants;
	}

	/**
	 * is this a wordpress admin-side request
	 * @return boolean
	 */
	public function isAdmin()
	{
		return (boolean) $this->constants->get('WP_BLOG_ADMIN');
	}

	/**
	 * is this a wordpress client-side request
	 * @return boolean
	 */
	public function isClient()
	{
		return !$this->isAdmin();
	}

	/**
	 * is this a wordpress ajax request
	 * @return boolean
	 */
	public function isAjax()
	{
		return (boolean) $this->constants->get('DOING_AJAX');
	}

}
