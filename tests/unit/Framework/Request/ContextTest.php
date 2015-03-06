<?php namespace Pressor\Framework\Request;
use Pressor\Testing\TestCase;

class ContextTest extends TestCase {

	function test_construct_Constants_ReturnsInstsanceOfContextContract()
	{
		$result = new Context($this->fakePressorConstants());

		$this->assertInstanceOf('Pressor\Contracts\Framework\Request\Context', $result);
	}
	function test_construct_Constants_SetsConstantsProvider()
	{
		$context = new Context($constants = $this->fakePressorConstants());

		$result = $context->getConstantsProvider();

		$this->assertEquals($constants, $result);
	}

	protected function makeContext()
	{
		return new Context($this->fakePressorConstants());
	}

	function test_isAdmin_NoParams_CallsGetOnConstantsWithWpBlogAdminKey()
	{
		$context = $this->makeContext();

		$context->getConstantsProvider()->shouldReceive('get')->once()->with('WP_BLOG_ADMIN');

		$context->isAdmin();
	}
	function test_isAdmin_NoParamsWhenGetOnConstantsWithWpBlogAdminKeyReturnsTrue_ReturnsTrue()
	{
		$context = $this->makeContext();
		$context->getConstantsProvider()->shouldReceive('get')->with('WP_BLOG_ADMIN')->andReturn(true);

		$result = $context->isAdmin();

		$this->assertTrue($result);
	}
	function test_isAdmin_NoParamsWhenGetOnConstantsWithWpBlogAdminKeyReturnsNull_ReturnsFalse()
	{
		$context = $this->makeContext();
		$context->getConstantsProvider()->shouldReceive('get')->with('WP_BLOG_ADMIN')->andReturn(null);

		$result = $context->isAdmin();

		$this->assertFalse($result);
	}

	function test_isClient_NoParams_CallsGetOnConstantsWithWpBlogAdminKey()
	{
		$context = $this->makeContext();

		$context->getConstantsProvider()->shouldReceive('get')->once()->with('WP_BLOG_ADMIN');

		$context->isClient();
	}
	function test_isClient_NoParamsWhenGetOnConstantsWithWpBlogAdminKeyReturnsTrue_ReturnsFalse()
	{
		$context = $this->makeContext();
		$context->getConstantsProvider()->shouldReceive('get')->with('WP_BLOG_ADMIN')->andReturn(true);

		$result = $context->isClient();

		$this->assertFalse($result);
	}
	function test_isClient_NoParamsWhenGetOnConstantsWithWpBlogAdminKeyReturnsNull_ReturnsTrue()
	{
		$context = $this->makeContext();
		$context->getConstantsProvider()->shouldReceive('get')->with('WP_BLOG_ADMIN')->andReturn(null);

		$result = $context->isClient();

		$this->assertTrue($result);
	}

	function test_isAjax_NoParams_CallsGetOnConstantsWithDoingAjaxKey()
	{
		$context = $this->makeContext();

		$context->getConstantsProvider()->shouldReceive('get')->once()->with('DOING_AJAX');

		$context->isAjax();
	}
	function test_isAjax_NoParamsWhenGetOnConstantsWithDoingAjaxKeyReturnsTrue_ReturnsTrue()
	{
		$context = $this->makeContext();
		$context->getConstantsProvider()->shouldReceive('get')->with('DOING_AJAX')->andReturn(true);

		$result = $context->isAjax();

		$this->assertTrue($result);
	}
	function test_isAjax_NoParamsWhenGetOnConstantsWithDoingAjaxKeyReturnsNull_ReturnsFalse()
	{
		$context = $this->makeContext();
		$context->getConstantsProvider()->shouldReceive('get')->with('DOING_AJAX')->andReturn(null);

		$result = $context->isAjax();

		$this->assertFalse($result);
	}

/*
*/
}
