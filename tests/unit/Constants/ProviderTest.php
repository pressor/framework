<?php namespace Pressor\Constants;
use Pressor\Testing\TestCase;

class ProviderTest extends TestCase {

	protected $useSpy = true;

	function test_construct_ConfigsArray_ReturnsInstanceOfProviderContract()
	{
		$result = new Provider($configs = array('configs'));

		$this->assertInstanceOf('Pressor\Contracts\Constants\Provider', $result);
	}
	function test_construct_ConfigsArray_SetsConfigs()
	{
		$provider = new Provider($configs = array('configs'));

		$result = $provider->getConfigs();

		$this->assertEquals($configs, $result);
	}
	function test_construct_ConfigsArrayAndDataArray_SetsData()
	{
		$provider = new Provider(array('blacklisted' => array()), $data = array('foo' => 'bar'));

		$result = $provider->getData();

		$this->assertEquals($data, $result);
	}
	protected function makeConfigs()
	{
		return array(
			'blacklisted' => array(
				'blacklisted_key' => true,
				'whitelisted_key' => false,
			),
			'required' => array(
				'required_key' => true,
				'optional_key' => false,
			),
		);
	}
	protected function makeProvider()
	{
		$configs = $this->makeConfigs();
		return new Provider($configs);
	}
	function test_getBlacklisted_NoParams_ReturnsBlacklistedDataFromConfigs()
	{
		$provider = $this->makeProvider();

		$result = $provider->getBlacklisted();

		$this->assertEquals($this->makeConfigs()['blacklisted'], $result);
	}
	function test_getRequired_NoParams_ReturnsRequiredDataFromConfigs()
	{
		$provider = $this->makeProvider();

		$result = $provider->getRequired();

		$this->assertEquals($this->makeConfigs()['required'], $result);
	}
// set
	function test_set_KeyAndValue_SetsData()
	{
		$provider = $this->makeProvider();
		$provider->set('foo', 'bar');

		$result = $provider->getData();

		$this->assertEquals(array('foo' => 'bar'), $result);
	}
	function test_set_KeyAndValue_ReturnsSelf()
	{
		$provider = $this->makeProvider();

		$result = $provider->set('foo', 'bar');

		$this->assertEquals($provider, $result);
	}
	function test_set_BlacklistedKeyWhereFalseAndValue_SetsData()
	{
		$provider = $this->makeProvider();
		$provider->set('whitelisted_key', 'bar');

		$result = $provider->getData();

		$this->assertEquals(array('whitelisted_key' => 'bar'), $result);
	}
	function test_set_BlacklistedKeyWhereTrueAndValue_ThrowsDomainException()
	{
		$provider = $this->makeProvider();

		$this->setExpectedException('DomainException', 'Cannot set constant [blacklisted_key]');

		$provider->set('blacklisted_key', 'bar');
	}
	function test_set_NumericKeyAndValue_ThrowsDomainException()
	{
		$provider = $this->makeProvider();

		$this->setExpectedException('DomainException', 'Cannot set constant [1.1]');

		$provider->set('1.1', 'bar');
	}
	function test_set_Array_SetsData()
	{
		$provider = $this->makeProvider();
		$provider->set($expected = array('foo' => 'foo value', 'bar' => 'bar value'));

		$result = $provider->getData();

		$this->assertEquals($expected, $result);
	}
	function test_set_Array_ReturnsSelf()
	{
		$provider = $this->makeProvider();

		$result = $provider->set($expected = array('foo' => 'foo value', 'bar' => 'bar value'));

		$this->assertEquals($provider, $result);
	}
	function test_set_afterBindCalled_ThrowsLogicException()
	{
		$provider = new Provider(array('blacklisted' => array(), 'required' => array()));
		$provider->bind();

		$this->setExpectedException('LogicException', 'Pressor constants already bound');

		$provider->set('foo', 'bar');
	}
	protected function makeData()
	{
		$required = $this->makeConfigs()['required'];
		return array_fill_keys(array_keys($required), 'value');
	}
	function test_bind_WhenRequiredDataSet_CallsDefineWithKeyAndValue()
	{
		$provider = $this->makeProvider();
		$data = $this->makeData();
		$provider->set($data);
		$provider->bind();

		$result = $this->spy->getRecorder('define')->getCalls();

		$this->assertEquals(array(
			array('required_key', 'value'),
			array('optional_key', 'value'),
		), $result);
	}
	function test_bind_WhenRequiredDataSet_ReturnsSelf()
	{
		$provider = $this->makeProvider();
		$data = $this->makeData();
		$provider->set($data);

		$result = $provider->bind();

		$this->assertEquals($provider, $result);
	}
	function test_bind_WhenDataValueIsNull_NeverCallsDefineWithKeyAndValue()
	{
		$provider = $this->makeProvider();
		$data = $this->makeData();
		$data['null_key'] = null;
		$provider->set($data);

		$provider->bind();

		$this->assertFunctionNotCalledWith('define', array('null_key', null));
	}
	function test_bind_WhenOptionalDataFalse_CallsDefineWithKeysAndValues()
	{
		$provider = $this->makeProvider();
		$data = $this->makeData();
		$data['optional_key'] = false;
		$provider->set($data);
		$provider->bind();

		$result = $this->spy->getRecorder('define')->getCalls();

		$this->assertEquals(array(
			array('required_key', 'value'),
			array('optional_key', false),
		), $result);
	}
	function test_bind_WhenRequiredKeyFalse_ThrowsRuntimeException()
	{
		$provider = $this->makeProvider();
		$data = $this->makeData();
		$data['required_key'] = false;
		$provider->set($data);

		$this->setExpectedException('RuntimeException', 'The constant(s) [required_key] should be set');

		$provider->bind();
	}
// get
	function test_get_Key_CallsDefinedWithKey()
	{
		$provider = $this->makeProvider();

		$provider->get('foo');

		$this->assertFunctionLastCalledWith('defined', array('foo'));
	}
	function test_get_KeyWhenDefinedReturnsFalse_NeverCallsConstant()
	{
		$provider = $this->makeProvider();
		$this->spy['defined'] = false;

		$provider->get('foo');

		$this->assertFunctionNotCalled('constant');
	}
	function test_get_KeyWhenDefinedReturnsFalse_ReturnsNull()
	{
		$provider = $this->makeProvider();
		$this->spy['defined'] = false;

		$result = $provider->get('foo');

		$this->assertNull($result);
	}
	function test_get_KeyWhenDefinedReturnsTrue_CallsConstantWithKey()
	{
		$provider = $this->makeProvider();
		$this->spy['defined'] = true;

		$provider->get('foo');

		$this->assertFunctionLastCalledWith('constant', array('foo'));
	}
	function test_get_KeyWhenDefinedReturnsTrueAndConstantReturnsResult_ReturnsResult()
	{
		$provider = $this->makeProvider();
		$this->spy['defined'] = true;
		$this->spy['constant'] = 'result';

		$result = $provider->get('foo');

		$this->assertEquals('result', $result);
	}
/*
*/
}

function define()
{
	\UnitTesting\FunctionSpy\Spy::define();
}

function defined()
{
	return \UnitTesting\FunctionSpy\Spy::defined();
}

function constant()
{
	return \UnitTesting\FunctionSpy\Spy::constant();
}
