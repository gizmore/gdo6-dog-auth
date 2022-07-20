<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Crypto\GDT_Password;
use GDO\Crypto\BCrypt;
use GDO\Dog\WithBruteforceProtection;

/**
 * Register a dog user or change your password.
 * @author gizmore
 * @version 6.10.4
 * @since 6.10.0
 */
final class Register extends DOG_Command
{
    use WithBruteforceProtection;
    
    public $priority = 20;
    public $trigger = 'register';
    
    public function isRoomMethod() { return false; }
    
    public function gdoParameters() : array
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
            elseif (!$gdo_user->gdoValue('user_password')->validate($password))
            {
                return $message->rply('err_dog_wrong_old_password');
            }
            else
            {
                $gdo_user->saveVar('user_password', BCrypt::create($newPassword)->__toString());
                return $message->rply('msg_dog_password_changed');
            }
        }
        else
        {
            $gdo_user->saveVar('user_password', BCrypt::create($password)->__toString());
            $dog_user->login();
            return $message->rply('msg_dog_registered');
        }
    }
    
}
