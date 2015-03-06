<?php namespace Pressor\Plugins\UpdateDisabler;
use Pressor\Support\Plugins\Plugin as BasePlugin;
use Pressor\Support\Traits\HasPressorTrait;
use Pressor\Contracts\Framework\Pressor;

class UpdateDisabler extends BasePlugin {
	use HasPressorTrait;

	/**
	 * array of updates that can be disabled
	 * @var array
	 */
	protected $disables = array(
		'core',
		'scheduled',
		'plugins',
		'themes',
		'translations',
	);

	/**
	 * holds transient object so we don't have to repeatedly make it
	 * @var object
	 */
	protected $transient;

	public function __construct(Pressor $pressor, array $configs = array())
	{
		$this->setPressor($pressor);
		$this->setConfigs($configs);
	}

	protected function load()
	{
		foreach ($this->disables as $type)
		{
			if (!array_get($this->configs, $type)) continue;
			$method = 'disable' . ucfirst($type);
			$this->{$method}();
		}
	}

	protected function disableCore()
	{
		$this->pressor['hooks']->remove(array(
			array('admin_init', '_maybe_update_core'),
			array('wp_version_check', 'wp_version_check'),
		));

		$this->pressor['hooks']->action('admin_menu', array($this, 'removeUpdateNag'));

		$this->pressor['options']->siteTransient('core', $this->makeTransientObject());
	}

	/**
	 * remove the admin_nag action
	 * @return void
	 */
	public function removeUpdateNag()
	{
		$this->pressor['hooks']->remove('admin_notices', 'update_nag', 3);
	}

	protected function disableScheduled()
	{
		$this->pressor['hooks']->remove(array(
			array('wp_maybe_auto_update', 'wp_maybe_auto_update'),
			array('init', 'wp_schedule_update_checks'),
		));
	}

	protected function disablePlugins()
	{
		$this->pressor['hooks']->remove(array(
			array('load-plugins.php', 'wp_update_plugins'),
			array('load-update.php', 'wp_update_plugins'),
			array('load-update-core.php', 'wp_update_plugins'),
			array('admin_init', '_maybe_update_plugins'),
			array('wp_update_plugins', 'wp_update_plugins'),
		));

		$this->pressor['options']->siteTransient('plugins', $this->makeTransientObject());
	}

	/**
	 * disable the plugin updates
	 */
	protected function disableThemes()
	{
		$this->pressor['hooks']->remove(array(
			array('load-themes.php', 'wp_update_themes'),
			array('load-update.php', 'wp_update_themes'),
			array('load-update-core.php', 'wp_update_themes'),
			array('admin_init', '_maybe_update_themes'),
			array('wp_update_themes', 'wp_update_themes'),
		));

		$this->pressor['options']->siteTransient('themes', $this->makeTransientObject());
	}

	protected function disableTranslations()
	{
		$this->pressor['options']->siteTransient('available_translations', array());
	}

	protected function makeTransientObject()
	{
		if (!$this->transient)
		{
			$version = $this->pressor['proxy']->getGlobal('wp_version');
			$this->transient = (object) array(
				'last_checked'    => 9999999999,
				'updates'         => array(),
				'version_checked' => $version,
			);
		}
		return $this->transient;
	}


}
