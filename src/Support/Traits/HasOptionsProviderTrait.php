<?php namespace Pressor\Support\Traits;
use Pressor\Contracts\Options\Provider as OptionsProvider;

trait HasOptionsProviderTrait {

	/**
	 * instance of options provider
	 * @var Pressor\Contracts\Options\Provider
	 */
	protected $optionsProvider;

	/**
	 * set options provider instance
	 * @param  Pressor\Contracts\Options\Provider $optionsProvider
	 * @return void
	 */
	public function setOptionsProvider(OptionsProvider $optionsProvider)
	{
		$this->optionsProvider = $optionsProvider;
	}

	/**
	 * get options provider instance
	 * @return Pressor\Contracts\Options\Provider
	 */
	public function getOptionsProvider()
	{
		return $this->optionsProvider;
	}


}
