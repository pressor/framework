<?php namespace Pressor\Plugins\UpdateDisabler;
use Pressor\Testing\TestCase;

class UpdateDisablerTest extends TestCase {

	protected $useApp = true;

	function test_construct_Pressor_SetsPressor()
	{
		$disabler = new UpdateDisabler($pressor = $this->fakePressor());

		$result = $disabler->getPressor();

		$this->assertEquals($pressor, $result);
	}
	function test_construct_PressorAndConfigs_SetsConfigs()
	{
		$disabler = new UpdateDisabler($this->fakePressor(), $configs = array('configs'));

		$result = $disabler->getConfigs();

		$this->assertEquals($configs, $result);
	}
	protected function makeDisabler()
	{
		$this->app['pressor.proxy'] = $proxy = $this->fakePressorProxy();
		$proxy->shouldReceive('getGlobal')->byDefault()->atMost()->times(1)->with('wp_version')->andReturn('version');
		$this->app['pressor.hooks'] = $hooks = $this->fakePressorHooks();
		$hooks->shouldReceive('remove', 'action')->byDefault();
		$this->app['pressor.options'] = $options = $this->fakePressorOptions();
		$options->shouldReceive('siteTransient')->byDefault();
		return new UpdateDisabler($this->makePressorWithApp());
	}

// core updates
	function test_boot_WhenConfigsCoreKeyIsTrue_CallsRemoveOnHooksWithEvents()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('core', true);

		$this->app['pressor.hooks']->shouldReceive('remove')->once()->with(array(
			array('admin_init', '_maybe_update_core'),
			array('wp_version_check', 'wp_version_check'),
		));

		$disabler->boot();
	}
	function test_boot_WhenConfigsCoreKeyIsTrue_CallsActionOnHooksWithAdminMenuEventArrayWithSelfAndRemoveUpdateNagMethod()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('core', true);

		$this->app['pressor.hooks']->shouldReceive('action')->once()->with('admin_menu', array($disabler, 'removeUpdateNag'));

		$disabler->boot();
	}
	function test_boot_WhenConfigsCoreKeyIsTrue_CallsSiteTransientOnOptionWithCoreKeyAndTransientObject()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('core', true);

		$this->app['pressor.options']->shouldReceive('siteTransient')->once()->andReturnUsing(function($event, $transient)
		{
			$values = get_object_vars($transient);
			$result = array($event, $values);
			$this->assertEquals(array('core', array(
				'last_checked'    => 9999999999,
				'updates'         => array(),
				'version_checked' => 'version',
			)), $result);
		});

		$disabler->boot();
	}
	function test_removeUpdateNag_NoParams_CallsRemoveOnHooksWithAdminNotcesEventUpdateNagFunctionAndPriority3()
	{
		$disabler = $this->makeDisabler();

		$this->app['pressor.hooks']->shouldReceive('remove')->once()->with('admin_notices', 'update_nag', 3);

		$disabler->removeUpdateNag();
	}
// scheduled
	function test_boot_WhenConfigsScheduledKeyIsTrue_CallsRemoveOnHooksWithEvents()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('scheduled', true);

		$this->app['pressor.hooks']->shouldReceive('remove')->once()->with(array(
			array('wp_maybe_auto_update', 'wp_maybe_auto_update'),
			array('init', 'wp_schedule_update_checks'),
		));

		$disabler->boot();
	}
// plugins
	function test_boot_WhenConfigsPluginsKeyIsTrue_CallsRemoveOnHooksWithEvents()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('plugins', true);

		$this->app['pressor.hooks']->shouldReceive('remove')->once()->with(array(
			array('load-plugins.php', 'wp_update_plugins'),
			array('load-update.php', 'wp_update_plugins'),
			array('load-update-core.php', 'wp_update_plugins'),
			array('admin_init', '_maybe_update_plugins'),
			array('wp_update_plugins', 'wp_update_plugins'),
		));

		$disabler->boot();
	}
	function test_boot_WhenConfigsPluginsKeyIsTrue_CallsSiteTransientOnOptionsWithPluginsKeyAndTransientObject()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('plugins', true);

		$this->app['pressor.options']->shouldReceive('siteTransient')->once()->andReturnUsing(function($event, $transient)
		{
			$values = get_object_vars($transient);
			$result = array($event, $values);
			$this->assertEquals(array('plugins', array(
				'last_checked'    => 9999999999,
				'updates'         => array(),
				'version_checked' => 'version',
			)), $result);
		});

		$disabler->boot();
	}
// themes
	function test_boot_WhenConfigsThemesKeyIsTrue_CallsRemoveOnHooksWithEvents()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('themes', true);

		$this->app['pressor.hooks']->shouldReceive('remove')->once()->with(array(
			array('load-themes.php', 'wp_update_themes'),
			array('load-update.php', 'wp_update_themes'),
			array('load-update-core.php', 'wp_update_themes'),
			array('admin_init', '_maybe_update_themes'),
			array('wp_update_themes', 'wp_update_themes'),
		));

		$disabler->boot();
	}
	function test_boot_WhenConfigsThemesKeyIsTrue_CallsSiteTransientOnOptionsWithThemesKeyAndTransientObject()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('themes', true);

		$this->app['pressor.options']->shouldReceive('siteTransient')->once()->andReturnUsing(function($event, $transient)
		{
			$values = get_object_vars($transient);
			$result = array($event, $values);
			$this->assertEquals(array('themes', array(
				'last_checked'    => 9999999999,
				'updates'         => array(),
				'version_checked' => 'version',
			)), $result);
		});

		$disabler->boot();
	}
// translations
	function test_boot_WhenConfigsTranslationsKeyIsTrue_CallsSiteTransientOnOptionsWithAvailableTranslationsKeyAndEmptyArray()
	{
		$disabler = $this->makeDisabler();
		$disabler->configure('translations', true);

		$this->app['pressor.options']->shouldReceive('siteTransient')->once()->with('available_translations', array());

		$disabler->boot();
	}
	function test_boot_WhenManyKeysTrue_CallsGetGlobalOnProxyWithWpVersionKeyOnlyOnce()
	{
		$disabler = $this->makeDisabler();
		$disabler->setConfigs(array('core' => true, 'plugins' => true, 'themes' => true));

		$this->app['pressor.proxy']->shouldReceive('getGlobal')->with('wp_version')->once();

		$disabler->boot();
	}

/*
*/
}

