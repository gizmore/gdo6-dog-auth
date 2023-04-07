<?php
namespace GDO\DogAuth\Method;

use GDO\Crypto\BCrypt;
use GDO\Crypto\GDT_Password;
use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\WithBruteforceProtection;

/**
 * Register a dog user or change your password.
 *
 * @version 6.10.4
 * @since 6.10.0
 * @author gizmore
 */
final class Register extends DOG_Command
{

	use WithBruteforceProtection;

	public int $priority = 20;

	public function getCLITrigger(): string
	{
		return 'register';
	}

	public function isRoomMethod() { return false; }

	public function gdoParameters(): array
	{
		return [
			GDT_Password::make('password')->notNull(),
			GDT_Password::make('new_password')->label('new_password'),
		];
	}

	public function dogExecute(DOG_Message $message, $password, $newPassword)
	{
		$dog_user = $message->user;
		$gdo_user = $dog_user->getGDOUser();

		if ($dog_user->isRegistered())
		{
			if (!$newPassword)
			{
				return $message->rply('err_dog_already_registered');
			}
			elseif (!$gdo_user->settingValue('Login', 'password')->validate($password))
			{
				return $message->rply('err_dog_wrong_old_password');
			}
			else
			{
				$hash = BCrypt::create($newPassword)->__toString();
				$gdo_user->saveSettingVar('Login', 'password', $hash);
				return $message->rply('msg_dog_password_changed');
			}
		}
		else
		{
			$hash = BCrypt::create($password)->__toString();
			$gdo_user->saveSettingVar('Login', 'password', $hash);
			$dog_user->login();
			return $message->rply('msg_dog_registered');
		}
	}

}
