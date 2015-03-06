<?php namespace Pressor\Testing;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Pressor\Framework\Pressor;
use Illuminate\Support\Facades\Facade;
use Illuminate\Foundation\Application;

abstract class TestCase extends BaseTestCase {
	use \UnitTesting\MockeryHelper\MockeryTrait;
	use \UnitTesting\FunctionSpy\SpyTrait;

	protected $useApp = false;
	protected $useSpy = false;

	public function setUp()
	{
		if ($this->useApp and !$this->app) $this->refreshApplication();
		if ($this->useSpy and !$this->spy) $this->initSpy();
	}

	public function tearDown()
	{
		$this->closeMocks();
		if ($this->useSpy) $this->flushSpy();
	}

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		Facade::setFacadeApplication($app = new Application());
		$app['env'] = 'testing';
		$app['path'] = 'app_path';
		$app['path.config'] = 'config_path';

		return $app;
	}

	protected function extractSrcPath($path)
	{
		return realpath(__DIR__ . '/../' . $path);
	}

	protected function prepareAppForServiceProvider()
	{
		$this->app['config'] = $config = $this->mock('Illuminate\Contracts\Config\Repository');
		$config->shouldReceive('set')->byDefault();
		$config->shouldReceive('get')->byDefault()->andReturn(array());
	}

	protected function prepareAppForPluginProvider()
	{
		$this->prepareAppForServiceProvider();
		$this->app['pressor.hooks'] = $hooks = $this->fakePressorHooks();
		$hooks->shouldReceive('action')->byDefault();
	}

	protected function fakeContainer()
	{
		return $this->mock('Illuminate\Container\Container');
	}

	protected function makePressorWithApp()
	{
		return new Pressor($this->app);
	}

	protected function fakePressor()
	{
		return $this->mock('Pressor\Contracts\Framework\Pressor');
	}

	protected function fakePressorHooks()
	{
		return $this->mock('Pressor\Contracts\Hooks\Factory');
	}

	protected function fakePressorProxy()
	{
		return $this->mock('Pressor\Contracts\Proxy\Proxy');
	}

	protected function fakePressorRequest()
	{
		return $this->mock('Pressor\Contracts\Framework\Request\Context');
	}

	protected function fakePressorRegistry()
	{
		return $this->mock('Pressor\Contracts\Framework\Extensions\Registry');
	}

	protected function fakePressorConstants()
	{
		return $this->mock('Pressor\Contracts\Constants\Provider');
	}

	protected function fakePressorPath()
	{
		return $this->mock('Pressor\Contracts\Path\Provider');
	}

	protected function fakePressorOptions()
	{
		return $this->mock('Pressor\Contracts\Options\Provider');
	}

/*
*/
}
