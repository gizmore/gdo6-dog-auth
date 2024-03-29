<?php
namespace GDO\DogAuth\Method;

use GDO\Crypto\GDT_Password;
use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\WithBruteforceProtection;

/**
 * Authenticate a dog user.
 *
 * @author gizmore
 */
final class Login extends DOG_Command
{

	use WithBruteforceProtection;

	public int $priority = 10;

	public function getCLITrigger(): string
	{
		return 'login';
	}

	protected function isRoomMethod(): bool { return false; }

	public function gdoParameters(): array
	{
		return [
			GDT_Password::make('password')->notNull(),
		];
	}

	public function dogExecute(DOG_Message $message, $password)
	{
		$dog_user = $message->user;
		$gdo_user = $dog_user->getGDOUser();

		if (!$dog_user->isRegistered())
		{
			return $message->rply('err_not_registered');
		}

		if ($dog_user->isAuthenticated())
		{
			return $message->rply('err_already_authed');
		}

		if ($this->isBruteforcing($message))
		{
			return false;
		}

		if ($gdo_user->gdoValue('user_password')->validate($password))
		{
			$dog_user->login();
			$message->rply('msg_dog_authenticated');
		}
		else
		{
			$message->rply('err_dog_authenticate');
		}
	}

}
