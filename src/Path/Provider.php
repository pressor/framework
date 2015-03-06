<?php namespace Pressor\Path;
use Pressor\Contracts\Path\Provider as ProviderContract;
use Pressor\Support\Traits\HasContainerTrait;
use Illuminate\Container\Container;

class Provider implements ProviderContract {
	use HasContainerTrait;

	public function __construct(Container $container = null)
	{
		if ($container) $this->setContainer($container);
	}

	/**
	 * get the wordpress path
	 * @param  string $path
	 * @return string|null
	 */
	public function wordpress($path = null)
	{
		if (!$result = $this->getPathFromContainer()) $result = $this->getPathFromConstant();
		if ($path) $result .= DIRECTORY_SEPARATOR . $path;
		return $result;
	}

	protected function getPathFromContainer()
	{
		if ($this->container and isset($this->container['path.wordpress']))
		{
			return $this->container['path.wordpress'];
		};
	}

	protected function getPathFromConstant()
	{
		$name = 'ABSPATH';
		return defined($name) ? : constant($name);
	}

}
