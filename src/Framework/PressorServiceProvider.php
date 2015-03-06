<?php namespace Pressor\Framework;
use Illuminate\Support\ServiceProvider;
use Pressor\Path\Provider as PathProvider;
use Pressor\Proxy\Proxy;
use Pressor\Hooks\Factory as HooksFactory;
use Pressor\Options\Provider as OptionsProvider;

class PressorServiceProvider extends ServiceProvider {

	/**
	 * list of base service providers to register
	 * @var array
	 */
	protected $providers = array(
		'Pressor\Constants\ConstantsServiceProvider',
		'Pressor\Framework\Extensions\RegistryServiceProvider',
	);

	/**
	 * contracts and their aliases
	 * @var array
	 */
	protected $contracts = array(
		'Pressor\Contracts\Constants\Provider' => 'pressor.constants',
		'Pressor\Contracts\Framework\Extensions\Registry' => 'pressor.registry',
		'Pressor\Contracts\Framework\Request\Context' => 'pressor.request',
		'Pressor\Contracts\Path\Provider' => 'pressor.path',
		'Pressor\Contracts\Proxy\Proxy' => 'pressor.proxy',
		'Pressor\Contracts\Hooks\Factory' => 'pressor.hooks',
		'Pressor\Contracts\Options\Provider' => 'pressor.options',
		'Pressor\Contracts\Framework\Pressor' => 'pressor',
	);

	/**
	 * classes and their singleton keys
	 * @var array
	 */
	protected $singletons = array(
		'Pressor\Framework\Request\Context' => 'pressor.request',
		'Pressor\Path\Provider' => 'pressor.path',
		'Pressor\Proxy\Proxy' => 'pressor.proxy',
		'Pressor\Hooks\Factory' => 'pressor.hooks',
		'Pressor\Options\Provider' => 'pressor.options',
		'Pressor\Framework\Pressor' => 'pressor',
	);

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->resolveWordpressPath();
		$this->registerContractAliases();
		$this->registerPressorProviders();
		$this->registerSingletonAliases();
	}

	protected function resolveWordpressPath()
	{
		$key = 'path.wordpress';
		if (!isset($this->app[$key])) $this->app[$key] = base_path('wordpress');
	}

	protected function registerContractAliases()
	{
		foreach ($this->contracts as $contract => $key)
		{
			$this->app->alias($key, $contract);
		}
	}

	protected function registerPressorProviders()
	{
		foreach ($this->providers as $provider)
		{
			$this->app->register($provider);
		}
	}

	protected function registerSingletonAliases()
	{
		foreach ($this->singletons as $singleton => $key)
		{
			$this->app->singleton($key, $singleton);
		}
	}
}
