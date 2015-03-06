<?php namespace Pressor\Plugins\SiteOptions;
use Pressor\Support\Plugins\Plugin as BasePlugin;
use Pressor\Support\Traits\HasOptionsProviderTrait;
use Pressor\Contracts\Options\Provider as OptionsProvider;

class SiteOptions extends BasePlugin {
	use HasOptionsProviderTrait;

	public function __construct(OptionsProvider $options, array $configs = array())
	{
		$this->setOptionsProvider($options);
		$this->setConfigs($configs);
	}

	protected function load()
	{
		$provider = $this->getOptionsProvider();
		foreach ($this->configs as $key => $value)
		{
			if ($value === false) continue;
			$provider->option($key, $value);
		}
	}
}
