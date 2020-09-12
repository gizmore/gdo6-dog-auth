<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\User\GDT_Password;

final class Register extends DOG_Command
{
    public $group = 'Auth';
    public $trigger = 'register';
    
    public function gdoParameters()
    {
        return array(
            GDT_Password::make('password'),
            GDT_Password::make('new_password'),
        );
    }
    
    public function dogExecute(DOG_Message $message, $password, $newPassword)
    {
        $user = $message->user;
    }
    
}