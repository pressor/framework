<?php namespace Pressor\Framework;
use Pressor\Contracts\Framework\Pressor as PressorContract;
use ArrayAccess;
use Pressor\Support\Traits\HasContainerTrait;
use Illuminate\Container\Container;
use Pressor\Contracts\Hooks\Factory as HooksFactory;
use Pressor\Contracts\Framework\Extensions\Registry;
use Pressor\Contracts\Framework\Request\Context as RequestContext;

class Pressor implements PressorContract, ArrayAccess {
	use HasContainerTrait;

	/**
	 * bind event
	 * @var string
	 */
	protected $bindEvent = 'plugins_loaded';

	public function __construct(Container $container)
	{
		$this->setContainer($container);
	}

	/**
	 * boot pressor
	 * @return void
	 * @throws LogicException
	 */
	public function boot()
	{
		$this->validateNotBooted();
		$this->registerCallbacksOnHooks($this['hooks']);
		$this->registerExtensionsOnRegistry($this['registry']);
		$this['booted'] = true;
	}

	protected function validateNotBooted()
	{
		if (isset($this['booted'])) throw new \LogicException('Pressor already booted');
	}

	protected function registerCallbacksOnHooks(HooksFactory $hooks)
	{
		$hooks->registerBaseCallbacks();
		$hooks->action($this->bindEvent, array($this, 'bind'));
	}

	protected function registerExtensionsOnRegistry(Registry $registry)
	{
		$registry->bootstrap();
	}

	/**
	 * bind pressor
	 * @return void
	 */
	public function bind()
	{
		$this->validateNotBound();
		$this->bindExtensionsOnRegistryUsingRequest($this['registry'], $this['request']);
		$this->bindCallbacksOnHooks($this['hooks']);
		$this['bound'] = true;
	}

	protected function validateNotBound()
	{
		if (isset($this['bound'])) throw new \LogicException('Pressor already bound');
	}

	protected function bindExtensionsOnRegistryUsingRequest(Registry $registry, RequestContext $request)
	{
		$registry->bind($request);
	}

	protected function bindCallbacksOnHooks(HooksFactory $hooks)
	{
		$hooks->bind();
	}

	/**
	 * check if offset exists
	 * @param  string $key
	 * @return boolean
	 */
	public function offsetExists($key)
	{
		$key = $this->makeContainerKey($key);
		return isset($this->container[$key]);
	}

	/**
	 * retrieve offset key
	 * @param  string $key
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		$key = $this->makeContainerKey($key);
		return $this->container[$key];
	}

	/**
	 * set a key
	 * @param  string $key
	 * @param  mixed $value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		$key = $this->makeContainerKey($key);
		$this->container[$key] = $value;
	}

	/**
	 * unset a key
	 * @param  string $key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		$key = $this->makeContainerKey($key);
		unset($this->container[$key]);
	}

	protected function makeContainerKey($key)
	{
		return 'pressor.' . $key;
	}
}
