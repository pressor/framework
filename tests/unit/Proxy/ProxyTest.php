<?php namespace Pressor\Proxy;
use Pressor\Testing\TestCase;

class ProxyTest extends TestCase {

	protected $useSpy = true;

	public function setUp()
	{
		parent::setUp();
		require_once __DIR__ . '/stubs/proxy_test_stub.php';
	}
	function test_construct_PathProvider_ReturnsInstanceOfProxyContract()
	{
		$result = new Proxy($pathProvider = $this->fakePressorPath());

		$this->assertInstanceOf('Pressor\Contracts\Proxy\Proxy', $result);
	}
	function test_construct_PathProvider_SetsPathProvider()
	{
		$proxy = new Proxy($pathProvider = $this->fakePressorPath());

		$result = $proxy->getPathProvider();

		$this->assertEquals($pathProvider, $result);
	}
	protected function makeProxy()
	{
		$fakePathProvider = $this->fakePressorPath();
		$fakePathProvider->shouldReceive('wordpress')->byDefault()->andReturn(__DIR__ . '/stubs');
		return new Proxy($fakePathProvider);
	}
	function test_callWordpressFunction_WordpressMethodAndArgs_CallsGetContainerOnPressorWithNoArgs()
	{
		$proxy = $this->makeProxy();

		$proxy->getPathProvider()->shouldReceive('wordpress')->once()->withNoArgs()->andReturn(__DIR__ . '/stubs');

		$proxy->callWordpressFunction('doWordpressFunction', array('param1', 'param2'));
	}
	function test_callWordpressFunction_WordpressSnakeCaseMethodAndArgs_CallsMethodWithArgs()
	{
		$proxy = $this->makeProxy();

		$proxy->callWordpressFunction('do_wordpress_function', array('param1', 'param2'));

		$this->assertFunctionLastCalledWith('do_wordpress_function', array('param1', 'param2'));
	}
	function test_callWordpressFunction_WordpressCamelCaseMethodAndArgs_CallsSnakeCaseMethodWithArgs()
	{
		$proxy = $this->makeProxy();

		$proxy->callWordpressFunction('doWordpressFunction', array('param1', 'param2'));

		$this->assertFunctionLastCalledWith('do_wordpress_function', array('param1', 'param2'));
	}
	function test_callWordpressFunction_WordpressMethodReturnsResult_ReturnsResult()
	{
		$proxy = $this->makeProxy();
		$this->spy['do_wordpress_function'] = 'result';

		$result = $proxy->callWordpressFunction('doWordpressFunction', array('param1', 'param2'));

		$this->assertEquals('result', $result);
	}
	function test_callWordpressFunction_NonExistentMethod_ThrowsBadFunctionCallException()
	{
		$proxy = $this->makeProxy();

		$this->setExpectedException('BadFunctionCallException', 'The function [invalid_function] does not exist');

		$proxy->callWordpressFunction('invalid_function', array());
	}
	function test_callWordpressFunction_NonExistentCamelCalseMethod_ThrowsBadFunctionCallException()
	{
		$proxy = $this->makeProxy();

		$this->setExpectedException('BadFunctionCallException', 'The function [invalid_function] does not exist');

		$proxy->callWordpressFunction('invalidFunction', array());
	}
	function test_callWordpressFunction_NativeMethod_ThrowsBadFunctionCallException()
	{
		$proxy = $this->makeProxy();

		$this->setExpectedException('BadFunctionCallException', 'Cannot proxy native PHP function [define]');

		$proxy->callWordpressFunction('define', array());
	}
	function test_callWordpressFunction_NonWordpressMethod_ThrowsBadFunctionCallException()
	{
		$proxy = $this->makeProxy();

		$this->setExpectedException('BadFunctionCallException', 'Cannot proxy function [array_get] defined outside of Wordpress');

		$proxy->callWordpressFunction('array_get', array());
	}
	function test_callWordpressFunction_WordpressMethodAlreadyCalled_CallsGetContainerOnPressorWithPathWordpressKeyOnce()
	{
		$proxy = $this->makeProxy();

		$proxy->getPathProvider()->shouldReceive('wordpress')->once()->withNoArgs()->andReturn(__DIR__ . '/stubs');

		$proxy->callWordpressFunction('doWordpressFunction', array('param1', 'param2'));
		$proxy->callWordpressFunction('doWordpressFunction', array('param1', 'param2'));
	}

// getGlobal
	function test_getGlobal_GlobalVariableSet_ReturnsGlobalVariable()
	{
		$proxy = $this->makeProxy();
		$GLOBALS['proxy_test_global_var'] = 'result';

		$result = $proxy->getGlobal('proxy_test_global_var');

		$this->assertEquals('result', $result);
	}
	function test_getGlobal_GlobalVariableNotSet_ThrowsOutOfBoundsException()
	{
		$proxy = $this->makeProxy();

		$this->setExpectedException('OutOfBoundsException', 'The global variable [proxy_test_invalid_global_var] is not set');

		$proxy->getGlobal('proxy_test_invalid_global_var');
	}
	function test_overloadedGet_GlobalVariableSet_ReturnsGlobalVariable()
	{
		$proxy = $this->makeProxy();
		$GLOBALS['proxy_test_global_var'] = 'result';

		$result = $proxy->proxy_test_global_var;

		$this->assertEquals('result', $result);
	}
/*
*/
}
