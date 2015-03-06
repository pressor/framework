<?php namespace Pressor\Path;
use Pressor\Testing\TestCase;

class ProviderTest extends TestCase {

	protected $useSpy = true;

	function test_construct_NoParams_ReturnsInstanceOfProviderContract()
	{
		$result = new Provider();

		$this->assertInstanceOf('Pressor\Contracts\Path\Provider', $result);
	}
	function test_construct_NoParams_SetsContainerAsNull()
	{
		$provider = new Provider();

		$result = $provider->getContainer();

		$this->assertNull($result);
	}
	function test_construct_Container_SetsContainer()
	{
		$provider = new Provider($container = $this->fakeContainer());

		$result = $provider->getContainer();

		$this->assertEquals($container, $result);
	}
	function test_wordpress_NoParamsContainerSet_CallsOffsetExistsOnContainerWithPathWordressKey()
	{
		$provider = new Provider($mockContainer = $this->fakeContainer());

		$mockContainer->shouldReceive('offsetExists')->once()->with('path.wordpress');

		$provider->wordpress();
	}
	function test_wordpress_NoParamsContainerSetOffsetExistsOnContainerReturnsTrue_CallsOffsetGetOnContainerWithPathWordpressKey()
	{
		$provider = new Provider($mockContainer = $this->fakeContainer());
		$mockContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(true);

		$mockContainer->shouldReceive('offsetGet')->once()->with('path.wordpress');

		$provider->wordpress();
	}
	function test_wordpress_NoParamsContainerSetOffsetExistsOnContainerReturnsTrueAndOffsetGetOnContainerReturnsPath_ReturnsPath()
	{
		$provider = new Provider($stubContainer = $this->fakeContainer());
		$stubContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(true);
		$stubContainer->shouldReceive('offsetGet')->with('path.wordpress')->andReturn('result');

		$result = $provider->wordpress();

		$this->assertEquals('result', $result);
	}
	function test_wordpress_SubpathContainerSetOffsetExistsOnContainerReturnsTrueAndOffsetGetOnContainerReturnsPath_ReturnsPathDirectorySeparatorSubpath()
	{
		$provider = new Provider($stubContainer = $this->fakeContainer());
		$stubContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(true);
		$stubContainer->shouldReceive('offsetGet')->with('path.wordpress')->andReturn('result');

		$result = $provider->wordpress('subpath');

		$this->assertEquals('result' . DIRECTORY_SEPARATOR . 'subpath', $result);
	}
	function test_wordpress_NoParamsContainerSetOffsetExistsOnContainerReturnsFalse_CallsDefinedWithABSPATH()
	{
		$provider = new Provider($stubContainer = $this->fakeContainer());
		$stubContainer->shouldReceive('offsetExists')->with('path.wordpress')->andReturn(false);

		$provider->wordpress();

		$this->assertFunctionLastCalledWith('defined', array('ABSPATH'));
	}
	function test_wordpress_NoParamsWhenContainerNotSet_CallsDefinedWithABSPATH()
	{
		$provider = new Provider();

		$provider->wordpress();

		$this->assertFunctionLastCalledWith('defined', array('ABSPATH'));
	}
	function test_wordpress_NoParamsWhenContainerNotSetAndDefinedReturnsFalse_ReturnsNull()
	{
		$provider = new Provider();
		$this->spy['defined'] = false;

		$result = $provider->wordpress();

		$this->assertNull($result);
	}
	function test_wordpress_NoParamsWhenContainerNotSetAndDefinedReturnsTrue_CallsConstantWithABSPATH()
	{
		$provider = new Provider();
		$this->spy['defined'] = false;

		$provider->wordpress();

		$this->assertFunctionLastCalledWith('constant', array('ABSPATH'));
	}
	function test_wordpress_NoParamsWhenContainerNotSetAndDefinedReturnsTrueAndConstantReturnsResult_ReturnsResult()
	{
		$provider = new Provider();
		$this->spy['defined'] = false;
		$this->spy['constant'] = 'result';

		$result = $provider->wordpress();

		$this->assertEquals('result', $result);
	}

/*
*/
}

function defined()
{
	return \UnitTesting\FunctionSpy\Spy::defined();
}
function constant()
{
	return \UnitTesting\FunctionSpy\Spy::constant();
}
