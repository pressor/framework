<?php namespace Pressor\Support\Plugins;
use Pressor\Contracts\Plugins\Plugin as PluginContract;

abstract class Plugin implements PluginContract {

	/**
	 * holds configs
	 * @var array
	 */
	protected $configs = array();

	/**
	 * tracks boot status
	 * @var boolean
	 */
	protected $booted = false;

	/**
	 * set the configs
	 * @param  array $configs
	 * @return void
	 */
	public function setConfigs(array $configs)
	{
		$this->validateNotBooted();
		$this->configs = $configs;
	}

	/**
	 * get the configs
	 * @return array
	 */
	public function getConfigs()
	{
		return $this->configs;
	}

	/**
	 * configure the plugin
	 * @param  string $key
	 * @param  mixed $value
	 * @return void
	 */
	public function configure($key, $value)
	{
		$this->validateNotBooted();
		array_set($this->configs, $key, $value);
	}

	/**
	 * boot the plugin
	 * @return void
	 */
	public function boot()
	{
		$this->validateNotBooted();
		$this->load();
		$this->booted = true;
	}

	protected abstract function load();

	protected function validateNotBooted()
	{
		if ($this->booted) throw new \LogicException('Plugin already booted');
	}

}
