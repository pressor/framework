<?php namespace Pressor\Options;
use Pressor\Contracts\Options\Provider as ProviderContract;
use Pressor\Support\Traits\HasHooksFactoryTrait;
use Pressor\Contracts\Hooks\Factory as HooksFactory;


class Provider implements ProviderContract {
	use HasHooksFactoryTrait;

	/**
	 * array of prefixes
	 * @var array
	 */
	protected $prefixes = array(
		'option' => 'pre_option_',
		'transient' => 'pre_transient_',
		'siteTransient' => 'pre_site_transient_',
	);

	/**
	 * array of data that will be returned by Wordpress filters
	 * @var array
	 */
	protected $data = array();

	/**
	 * array of filters that have been registered
	 * @var array
	 */
	protected $registered = array();

	public function __construct(HooksFactory $hooks)
	{
		$this->setHooksFactory($hooks);
	}

	/**
	 * get the stored data
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * set a key with optional prefix with a value and register the filter
	 * @param  string $key
	 * @param  mixed $value
	 * @param  string $prefix
	 * @throws LogicException
	 */
	public function set($key, $value = null, $prefix = '')
	{
		if (is_array($key))
		{
			$prefix = $value ? : $prefix;
			foreach ($key as $k => $value)
			{
				$this->set($k, $value, $prefix);
			}
			return;
		}
		$key = $prefix . $key;
		if ($value === false)
		{
			throw new \LogicException('Setting key [' .  $key . '] as false will have no effect');
		}
		$this->data[$key] = $value;
		$this->registerFilterIfNotRegistered($key);
	}

	/**
	 * create the filter hook with priority 0
	 */
	protected function registerFilterIfNotRegistered($key)
	{
		if (!array_get($this->registered, $key))
		{
			$this->getHooksFactory()->filter($key, array($this, 'get'), 0, array($key));
			$this->registered[$key] = true;
		}
	}

	/**
	 * get the value from data. called by wordpress apply_filters
	 * @param  string $key
	 * @return mixed
	 */
	public function get($key)
	{
		$value = array_get($this->data, $key, false);
		return $value;
	}

	/**
	 * magic method to handle other types of option setting on wordpress
	 * @param  string $method
	 * @param  array $args
	 * @return void
	 * @throws BadMethodCallException
	 */
	public function __call($method, array $args)
	{
		if (!$prefix = array_get($this->prefixes, $method))
		{
			throw new \BadMethodCallException('The method [' . $method . '] does not exist');
		}
		list($key, $value) = $args;
		$this->set($key, $value, $prefix);
	}

}
