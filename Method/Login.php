<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\User\GDT_Password;
use GDO\Date\GDT_Duration;

final class Login extends DOG_Command
{
    public $group = 'Auth';
    public $trigger = 'login';
    
    private $attempts = [];
    
    public function getConfigBot()
    {
        return array(
            GDT_Duration::make('timeout')->initial('10'),
        );
    }
    
    public function getTimeout()
    {
        return $this->getConfigValueBot('timeout');
    }
    
    public function gdoParameters()
    {
        return array(
            GDT_Password::make('password')->notNull(),
        );
    }
    
    public function dogExecute(DOG_Message $message, $password)
    {
        $dog_user = $message->user;
        $gdo_user = $dog_user->getGDOUser();

        if (!$dog_user->isRegistered())
        {
            return $message->rply('err_not_registered');
        }
        
        $time = microtime(true);
        $last = isset($this->attempts[$dog_user->getID()]) ? $this->attempts[$dog_user->getID()] : $time;
        $wait = $time - $last;
        if ($wait < 10)
        {
            $wait = 10 - $wait;
            return $message->rply('err_login_blocked', [$wait]);
        }
        
        
        if ($gdo_user->getValue('user_password')->validate($password))
        {
            $dog_user->login();
            $message->rply('msg_dog_authenticated');
        }
        else
        {
            $this->attempts[$dog_user->getID()] = $time;
            $message->rply('err_dog_authenticate');
        }
    }
    
}
