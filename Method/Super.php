<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Core\GDT_Secret;
use GDO\Dog\DOG_Message;
use GDO\Date\GDT_Duration;
use GDO\Dog\Dog;
use GDO\User\GDO_UserPermission;

/**
 * Grant all permissions to a user.
 * @author gizmore
 */
final class Super extends DOG_Command
{
    public $group = 'Auth';
    public $trigger = 'super';
    
    private $attempts = [];
    
    public function isUserRequired() { return true; }
    public function isPrivateMethod() { return true; }
    
    public function getConfigBot()
    {
        return array(
            GDT_Secret::make('super_password')->notNull()->initial('supergizmore'),
            GDT_Duration::make('super_timeout')->notNull()->initial('10'),
        );
    }
    
    public function gdoParameters()
    {
        return array(
            GDT_Secret::make('password')->notNull(),
        );
    }
    
    public function dogExecute(DOG_Message $message, $password)
    {
        if ($wait = $this->bruteforce($message))
        {
            return $message->rply('err_dog_bruteforce', [$wait]);
        }
        
        if ($password !== $this->getConfigValueBot('super_password'))
        {
            $this->attempts[$message->user->getID()] = microtime(true);
            return $message->rply('err_dog_superword');
        }
        
        $permissions = array(Dog::VOICE, Dog::HALFOP, Dog::OPERATOR, Dog::OWNER);
        foreach ($permissions as $permission)
        {
            GDO_UserPermission::grant($message->user->getGDOUser(), $permission);
        }
        $message->user->getGDOUser()->changedPermissions();
        
        return $message->rply('msg_dog_super_granted');
    }
    
    private function bruteforce(DOG_Message $message)
    {
        $time = microtime(true);
        $old = isset($this->attempts[$message->user->getID()]) ? $this->attempts[$message->user->getID()] : 0;
        $waited = $time - $old;
        $timeout = $this->getConfigValueBot('super_timeout');
        if ($waited < $timeout)
        {
            return $timeout - $waited;
        }
        return 0;
    }
}
