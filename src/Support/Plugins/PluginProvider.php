<?php namespace Pressor\Support\Plugins;
use Illuminate\Support\ServiceProvider;
use Pressor\Contracts\Plugins\PluginProvider as PluginProviderContract;
use Pressor\Support\Traits\HasContainerTrait;
use Illuminate\Container\Container;
use ReflectionClass;
use Pressor\Contracts\Hooks\Factory as HooksFactory;
use Pressor\Contracts\Plugins\Plugin;
use Pressor\Contracts\Framework\Request\Context as RequestContext;

abstract class PluginProvider extends ServiceProvider implements PluginProviderContract {
	use HasContainerTrait;

	/**
	 * the class of the plugin
	 * @var string
	 */
	protected $classname;

	/**
	 * the wordpres event for the plugin to boot
	 * @var string
	 */
	protected $bootEvent = 'plugins_loaded';

	public function __construct(Container $app)
	{
		parent::__construct($app);
		$this->setContainer($app);
	}

	/**
	 * regsiter the plugin
	 * @return void
	 * @throws RuntimeException
	 */
	public function register()
	{
		$this->registerPlugin();
		$this->afterRegister();
	}

	/**
	 * allows developer to run any other configurations for the plugin, e.g. setting config file location
	 * @return void
	 */
	protected function afterRegister()
	{
	}

	protected function registerPlugin()
	{
		$classname = $this->extractValidClassname();
		$me = $this;
		$this->app->bindShared($classname, function($app) use ($me) {
			$instance = $me->makePlugin($app);
			$me->registerBootEventForPlugin($app['pressor.hooks'], $instance);
			return $instance;
		});
	}

	protected function extractValidClassname()
	{
		$classname = $this->classname;

		switch (true)
		{
			case !$classname:
				// no classname provided, let's try to guess it from the provider's classname
				$provider = get_class($this);
				$classname = preg_replace('/(Plugin)?Provider$/', '', $provider);
				if ($classname === $provider)
				{
					// our guess yielded same classname as the provider's
					$classname = '';
				}
				break;
			case count(explode('\\', $classname)) === 1:
				// classname doesn't have any namespace separator, let's try to namespace it
				$reflection = new ReflectionClass($this);
				$classname = $reflection->getNamespaceName() . '\\' . $classname;
				break;
		}
		if (!class_exists($classname))
		{
			// still didn't find it. we'll throw an exception
			throw new \RuntimeException('The classname [' . $this->classname . '] could not be resolved');
		}
		// classname is valid! let's store it to speed things up next time we need it.
		return $this->classname = $classname;
	}

	/**
	 * actually instantiate the plugin
	 * @param  Illuminate\Container\Container $app
	 * @return Pressor\Contracts\Plugins\Plugin
	 */
	public function makePlugin(Container $app)
	{
		$classname = $this->extractValidClassname();
		return new $classname;
	}

	/**
	 * register the plugin's boot() method with a wordpress event
	 * @param  Pressor\Container\Hooks\Factory $hooks
	 * @param  Pressor\Contracts\Plugins\Plugin $plugin
	 * @return void
	 */
	public function registerBootEventForPlugin(HooksFactory $hooks, Plugin $plugin)
	{
		$hooks->action($this->bootEvent, array($plugin, 'boot'));
	}

	/**
	 * should the plugin load given the request?
	 * @param  Pressor\Contracts\Framework\Request\Context $request
	 * @return boolean
	 */
	public function shouldLoadOnRequest(RequestContext $request)
	{
		return true;
	}

	/**
	 * bind the plugin
	 * @return void
	 */
	public function bindPlugin()
	{
		$classname = $this->extractValidClassname();
		$this->app[$classname];
	}

}
