<?php namespace Pressor\Plugins\SiteOptions;
use Pressor\Testing\TestCase;

class SiteOptionsTest extends TestCase {

	function test_construct_OptionsProvider_SetsOptionsProvider()
	{
		$disabler = new SiteOptions($provider = $this->fakePressorOptions());

		$result = $disabler->getOptionsProvider();

		$this->assertEquals($provider, $result);
	}
	function test_construct_OptionsProviderAndConfigs_SetsConfigs()
	{
		$disabler = new SiteOptions($this->fakePressorOptions(), $configs = array('configs'));

		$result = $disabler->getConfigs();

		$this->assertEquals($configs, $result);
	}
	protected function makeSiteOptions()
	{
		return new SiteOptions($this->fakePressorOptions());
	}

	function test_boot_NoParamsWhenConfigsSet_CallsOptionOnOptionsProviderWithFirstKeyAndValue()
	{
		$plugin = $this->makeSiteOptions();
		$plugin->setConfigs(array('foo' => 'foo value', 'bar' => 'bar value'));

		$plugin->getOptionsProvider()->shouldReceive('option')->once()->with('foo', 'foo value');

		$plugin->getOptionsProvider()->shouldReceive('option');
		$plugin->boot();
	}
	function test_boot_NoParamsWhenConfigsSet_CallsOptionOnOptionsProviderWithLastKeyAndValue()
	{
		$plugin = $this->makeSiteOptions();
		$plugin->setConfigs(array('foo' => 'foo value', 'bar' => 'bar value'));

		$plugin->getOptionsProvider()->shouldReceive('option')->once()->with('bar', 'bar value');

		$plugin->getOptionsProvider()->shouldReceive('option');
		$plugin->boot();
	}
	function test_boot_NoParamsWhenConfigsKeySetAsFalse_NeverCallsOptionWithKey()
	{
		$plugin = $this->makeSiteOptions();
		$plugin->configure('foo', false);

		$plugin->getOptionsProvider()->shouldReceive('option')->never();

		$plugin->boot();
	}

/*
*/
}

