<?php namespace Pressor\Framework\Extensions;
use Pressor\Contracts\Framework\Extensions\Registry as RegistryContract;
use Pressor\Support\Traits\HasContainerTrait;
use Illuminate\Container\Container;
use Pressor\Contracts\Plugins\PluginProvider;
use Pressor\Contracts\Framework\Request\Context as RequestContext;

class Registry implements RegistryContract {
	use HasContainerTrait;

	/**
	 * array of providers configuration
	 * @var array
	 */
	protected $providers = array();

	/**
	 * array of plugin providers
	 * @var array
	 */
	protected $plugins = array();

	/**
	 * tracks whether or not instance has been bound
	 * @var boolean
	 */
	protected $bound = false;

	public function __construct(Container $container, array $providers = array())
	{
		$this->setContainer($container);
		$this->setProviders($providers);
	}

	/**
	 * set the providers configuration
	 * @param  array $providers
	 * @return void
	 */
	public function setProviders(array $providers)
	{
		$this->providers = $providers;
	}

	/**
	 * get the providers configuration
	 * @return array
	 */
	public function getProviders()
	{
		return $this->providers;
	}

	/**
	 * get the plugins
	 * @return array
	 */
	public function getPlugins()
	{
		return $this->plugins;
	}

	/**
	 * register a plugin on the container
	 * @param  string $class
	 * @return void
	 */
	public function plugin($class)
	{
		$this->validateNotBound();
		if (is_array($class))
		{
			$providers = array();
			foreach ($class as $c)
			{
				$providers[] = $this->registerPluginProvider($c);
			}
			return $providers;
		}
		return $this->registerPluginProvider($class);
	}

	protected function registerPluginProvider($class)
	{
		$provider = $this->container->register($class);
		$this->manifestPluginProvider($class, $provider);
		return $provider;
	}

	protected function manifestPluginProvider($class, PluginProvider $provider)
	{
		$this->plugins[$class] = $provider;
	}

	/**
	 * add an alias on the container
	 * @param  string $key
	 * @param  string $alias
	 * @return void
	 */
	public function alias($key, $alias)
	{
		$this->container->alias($key, $alias);
	}

	/**
	 * bootstrap the registry
	 * @return void
	 */
	public function bootstrap()
	{
		$this->registerProvidedPlugins();
		$this->registerProvidedAliases();
	}

	protected function registerProvidedPlugins()
	{
		if (!$plugins = array_get($this->providers, 'plugins')) return;
		if (!$plugins = array_keys(array_filter($plugins))) return;
		$this->plugin($plugins);
	}

	protected function registerProvidedAliases()
	{
		if ($aliases = array_get($this->providers, 'aliases'))
		{
			foreach ($aliases as $key => $alias)
			{
				$this->alias($key, $alias);
			}
		}
	}

	/**
	 * bind the plugins
	 * @return void
	 */
	public function bind(RequestContext $context)
	{
		$this->validateNotBound();
		foreach ($this->plugins as $provider)
		{
			// if the provider says we should load the plugin, then we'll have provider bind the plugin
			if ($provider->shouldLoadOnContext($context)) $provider->bindPlugin();
		}
		$this->bound = true;
	}

	protected function validateNotBound()
	{
		if ($this->bound) throw new \LogicException('Pressor registry already bound');
	}

}
