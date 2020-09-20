<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\GDT_DogUser;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\User\GDT_Permission;
use GDO\User\GDO_Permission;

final class Grant extends DOG_Command
{
    public $group = 'Auth';
    public $trigger = 'grant';
    
    public function gdoParameters()
    {
        return array(
            GDT_DogUser::make('user')->notNull(),
            GDT_Permission::make('permission'),
        );
    }
    
    public function dogExecute(DOG_Message $message, DOG_User $user, GDO_Permission $permission=null)
    {
        if (!$permission)
        {
            $this->showPermissions($message, $user);
        }
        
        else
        {
            $this->grantPermission($message, $user, $permission);
        }
    }
    
    public function showPermissions(DOG_Message $message, DOG_User $user)
    {
        
    }
    
    public function grantPermission(DOG_Message $message, DOG_User $user, GDO_Permission $permission=null)
    {
        
    }
    
}
