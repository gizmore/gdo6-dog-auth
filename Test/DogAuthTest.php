<?php
namespace GDO\DogAuth\Test;

use GDO\Dog\Test\DogTestCase;
use function PHPUnit\Framework\assertStringContainsString;

/**
 * Test dog authentication.
 *
 * @author gizmore
 */
final class DogAuthTest extends DogTestCase
{

	public function testRegister()
	{
		$r = $this->bashCommand('register 11111111');
		assertStringContainsString('You have been registered', $r);
	}

	public function testChangePassword()
	{
		$r = $this->bashCommand('register --new_password=22222222,11111111');
		assertStringContainsString('Your password has been changed successfully.', $r);
	}

	public function testLogout()
	{
		$r = $this->bashCommand('logout');
		assertStringContainsString('You are now logged out.', $r);
	}

}
