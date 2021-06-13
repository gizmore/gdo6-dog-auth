<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Core\GDT_Secret;
use GDO\Dog\DOG_Message;
use GDO\Date\GDT_Duration;
use GDO\Dog\Dog;
use GDO\User\GDO_UserPermission;
use GDO\Dog\WithBruteforceProtection;

/**
 * Grant all permissions to a user.
 * @author gizmore
 * @version 6.10.4
 * @since 6.10.0
 */
final class Super extends DOG_Command
{
    use WithBruteforceProtection;
    
    public $priority = 50;
    
    public $trigger = 'super';
    
    public function isAuthRequired() { return true; }
    public function isUserRequired() { return true; }
    public function isRoomMethod() { return false; }
    
    public function getConfigBot()
    {
        return [
            GDT_Duration::make('timeout')->initial('10'),
            GDT_Secret::make('super_password')->notNull()->initial('supergiz'),
            GDT_Secret::make('super_admin_password')->notNull()->initial('supergizmore'),
        ];
    }

    public function getConfigServer()
    {
        return array(
            GDT_Secret::make('super_password'),
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
        if ($this->isBruteforcing($message))
        {
            return false;
        }
      
        if ($password === $this->getConfigValueBot('super_admin_password'))
        {
            $permissions = array(Dog::VOICE, Dog::HALFOP, Dog::STAFF, Dog::OPERATOR, Dog::OWNER, Dog::ADMIN);
            foreach ($permissions as $permission)
            {
                GDO_UserPermission::grant($message->user->getGDOUser(), $permission);
            }
            $message->user->getGDOUser()->changedPermissions();
            return $message->rply('msg_dog_super_granted');
        }
        
        elseif ($password === $this->getConfigValueBot('super_password'))
        {
            $permissions = array(Dog::VOICE, Dog::HALFOP, Dog::STAFF, Dog::OPERATOR, Dog::OWNER);
            foreach ($permissions as $permission)
            {
                GDO_UserPermission::grant($message->user->getGDOUser(), $permission);
            }
            $message->user->getGDOUser()->changedPermissions();
            return $message->rply('msg_dog_super_granted');
        }
        
        elseif ($password === $this->getConfigValueServer($message->server, 'super_password'))
        {
            $permissions = array(Dog::VOICE, Dog::HALFOP, Dog::STAFF, Dog::OPERATOR);
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
