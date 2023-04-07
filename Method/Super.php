<?php
namespace GDO\DogAuth\Method;

use GDO\Core\GDT_Secret;
use GDO\Date\GDT_Duration;
use GDO\Dog\Dog;
use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\WithBruteforceProtection;
use GDO\User\GDO_UserPermission;

/**
 * Grant all permissions to a user.
 *
 * @version 6.10.4
 * @since 6.10.0
 * @author gizmore
 */
final class Super extends DOG_Command
{

	use WithBruteforceProtection;

	public int $priority = 50;

	public function getCLITrigger(): string
	{
		return 'super';
	}

	public function isAuthRequired() { return true; }

	public function isUserRequired(): bool { return true; }

	public function isRoomMethod() { return false; }

	public function getConfigServer(): array
	{
		return [
			GDT_Secret::make('super_password'),
		];
	}

	public function gdoParameters(): array
	{
		return [
			GDT_Secret::make('password')->notNull(),
		];
	}

	public function getConfigBot(): array
	{
		return [
			GDT_Duration::make('timeout')->initial('10'),
			GDT_Secret::make('super_password')->notNull()->initial('supergiz'),
			GDT_Secret::make('super_admin_password')->notNull()->initial('supergizmore'),
		];
	}

	public function dogExecute(DOG_Message $message, $password)
	{
		if ($this->isBruteforcing($message))
		{
			return false;
		}

		if ($password === $this->getConfigValueBot('super_admin_password'))
		{
			$permissions = [Dog::VOICE, Dog::HALFOP, Dog::OPERATOR, Dog::STAFF, Dog::ADMIN];
			foreach ($permissions as $permission)
			{
				GDO_UserPermission::grant($message->user->getGDOUser(), $permission);
			}
			$message->user->getGDOUser()->changedPermissions();
			return $message->rply('msg_dog_super_granted');
		}

		elseif ($password === $this->getConfigValueBot('super_password'))
		{
			$permissions = [Dog::VOICE, Dog::HALFOP, Dog::STAFF, Dog::OPERATOR, Dog::ADMIN];
			foreach ($permissions as $permission)
			{
				GDO_UserPermission::grant($message->user->getGDOUser(), $permission);
			}
			$message->user->getGDOUser()->changedPermissions();
			return $message->rply('msg_dog_super_granted');
		}

		elseif ($password === $this->getConfigValueServer($message->server, 'super_password'))
		{
			$permissions = [Dog::VOICE, Dog::HALFOP, Dog::STAFF, Dog::OPERATOR];
			foreach ($permissions as $permission)
			{
				GDO_UserPermission::grant($message->user->getGDOUser(), $permission);
			}
			$message->user->getGDOUser()->changedPermissions();
			return $message->rply('msg_dog_operator_granted');
		}

		else
		{
			return $message->rply('err_dog_superword');
		}
	}

}
