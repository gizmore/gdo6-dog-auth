<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;

/**
 * Log out from the dog chatbot.
 *
 * @author gizmore
 */
final class Logout extends DOG_Command
{

	public function getCLITrigger()
	{
		return 'logout';
	}

	public function dogExecute(DOG_Message $message)
	{
		$user = $message->user;
		if (!$user->isAuthenticated())
		{
			return $message->rply('err_dog_not_authenticated');
		}
		$user->logout();
		return $message->rply('msg_dog_logged_out');
	}

}
