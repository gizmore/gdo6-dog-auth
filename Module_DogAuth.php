<?php
namespace GDO\DogAuth;

use GDO\Core\GDO_Module;

/**
 * Authentication module for the dog chatbot.
 *
 * @author gizmore
 * @version 7.0.2
 * @since 6.2.0
 */
final class Module_DogAuth extends GDO_Module
{

	public function getDependencies(): array
	{
		return [
			'Dog',
			'Login',
			'Register',
		];
	}

	public function onLoadLanguage(): void
	{
		$this->loadLanguage('lang/auth');
	}

}
