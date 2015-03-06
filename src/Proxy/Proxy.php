<?php namespace Pressor\Proxy;
use Pressor\Contracts\Proxy\Proxy as ProxyContract;
use Pressor\Support\Traits\HasPathProviderTrait;
use Pressor\Contracts\Path\Provider;
use ReflectionFunction;

class Proxy implements ProxyContract {
	use HasPathProviderTrait;

	/**
	 * validated functions
	 * @var array
	 */
	protected $functions = array();

	/**
	 * constructor
	 * @param  Pressor\Contracts\Path\Provider $provider
	 */
	public function __construct(Provider $pathProvider)
	{
		$this->setPathProvider($pathProvider);
	}

	/**
	 * call a wordpress function
	 * @param  string $method
	 * @param  array $args
	 * @return mixed
	 * @throws BadFunctionCall
	 */
	public function callWordpressFunction($method, array $args)
	{
		$method = $this->extractValidMethod($method);
		return call_user_func_array($method, $args);
	}

	protected function extractValidMethod($method)
	{
		$method = snake_case($method);
		if (!array_get($this->functions, $method))
		{
			// method hasn't been validated yet, so validate it as a wordpress method
			$this->validateWordpressMethod($method);
			// now store the method so we don't have to re-validate when called again
			$this->functions[$method] = true;
		}
		return $method;
	}

	protected function validateWordpressMethod($method)
	{
		if (!function_exists($method))
		{
			throw $this->makeBadFunctionCallException('The function [' . $method . '] does not exist');
		}
		$reflection = new ReflectionFunction($method);
		if (!$path = $reflection->getFileName())
		{
			throw $this->makeBadFunctionCallException('Cannot proxy native PHP function [' . $method . ']');
		}
		if (strpos($path, $this->getWordpressPath()) === false)
		{
			throw $this->makeBadFunctionCallException('Cannot proxy function [' . $method . '] defined outside of Wordpress');
		}
	}

	protected function makeBadFunctionCallException($message)
	{
		return new \BadFunctionCallException($message);
	}

	/**
	 * get a global variable that should be defined in Wordpress
	 * @param  string $key
	 * @return mixed
	 * @throws RuntimeException
	 */
	public function getGlobal($key)
	{
		if (isset($GLOBALS[$key]))
		{
			return $GLOBALS[$key];
		}
		throw new \OutOfBoundsException('The global variable [' . $key . '] is not set');
	}

	/**
	 * ensure that we're calling a wordpress method call it
	 * @param  string $method
	 * @param  array $args
	 * @return mixed
	 * @throws BadFunctionCallException
	 */
	public function __call($method, array $args)
	{
		return $this->callWordpressFunction($method, $args);
	}

	/**
	 * convenient way to call globals
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->getGlobal($key);
	}

}
