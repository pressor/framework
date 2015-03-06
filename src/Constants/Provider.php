<?php namespace Pressor\Constants;
use Pressor\Contracts\Constants\Provider as ProviderContract;

class Provider implements ProviderContract {

	/**
	 * configurations of blacklisted and required keys
	 * @var array
	 */
	protected $configs = array();

	/**
	 * array to hold data to be bound to wordpress
	 * @var array
	 */
	protected $data = array();

	/**
	 * list of keys that were registered
	 * @var array
	 */
	protected $registered = array();

	/**
	 * tracks if this instance is booted
	 * @var boolean
	 */
	protected $bound = false;

	public function __construct(array $configs, array $data = array())
	{
		$this->configs = $configs;
		if ($data) $this->set($data);
	}

	/**
	 * get the all the configuration
	 * @return array
	 */
	public function getConfigs()
	{
		return $this->configs;
	}

	/**
	 * get the blacklisted keys
	 * @return array
	 */
	public function getBlacklisted()
	{
		return $this->configs['blacklisted'];
	}

	/**
	 * get the required keys
	 * @return array
	 */
	public function getRequired()
	{
		return $this->configs['required'];
	}

	/**
	 * set the data
	 * @param  string|array $key
	 * @param  mixed $value
	 * @return Pressor\Constants\Provider
	 */
	public function set($key, $value = null)
	{
		$this->validateNotBound();
		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->setData($k, $v);
			}
		}
		else
		{
			$this->setData($key, $value);
		}
		return $this;
	}

	protected function setData($key, $value)
	{
		if (is_numeric($key) or $this->isBlacklisted($key))
		{
			throw new \DomainException('Cannot set constant [' . $key . ']');
		}
		$this->data[$key] = $value;
	}

	protected function isBlacklisted($key)
	{
		return array_get($this->getBlacklisted(), $key);
	}

	/**
	 * get the data
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	protected function validateNotBound()
	{
		if ($this->bound)
		{
			throw new \LogicException('Pressor constants already bound');
		}
	}

	/**
	 * bind the data to Wordpress by defining the constants
	 * @return Pressor\Constants\Provider
	 */
	public function bind()
	{
		foreach ($this->data as $key => $value)
		{
			$this->defineAndRegister($key, $value);
		}
		$this->validateRequired();
		$this->bound = true;
		return $this;
	}

	protected function defineAndRegister($key, $value)
	{
		if (is_null($value)) return;
		define($key, $value);
		if ($value)	$this->registered[$key] = true;
	}

	protected function validateRequired()
	{
		if ($missing = array_filter(array_diff_key($this->getRequired(), $this->registered)))
		{
			$keys = array_keys($missing);
			throw new \RuntimeException('The constant(s) [' . join(', ', $keys) . '] should be set');
		}
	}

	/**
	 * get a constant's value
	 * @param  string $key
	 * @return mixed
	 */
	public function get($key)
	{
		return defined($key) ? constant($key) : null;
	}

}
