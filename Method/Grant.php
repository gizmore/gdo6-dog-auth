<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\GDT_DogUser;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\User\GDT_Permission;
use GDO\User\GDO_Permission;
use GDO\Util\Arrays;
use GDO\User\GDO_UserPermission;

final class Grant extends DOG_Command
{
    public $priority = 30;
    public $group = '';
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
        $gdo_user = $user->getGDOUser();
        $perms = $gdo_user->loadPermissions();
        $displayPerms = [];
        foreach (array_keys($perms) as $perm)
        {
            $displayPerms[] = t('perm_'.$perm);
        }
        $message->rply('msg_dog_show_permissions', [$user->displayFullName(), Arrays::implodeHuman($displayPerms)]);
    }
    
    public function grantPermission(DOG_Message $message, DOG_User $user, GDO_Permission $permission)
    {
        if ($permission->getLevel() === null) # single without level
        {
            if (!$message->getGDOUser()->hasPermissionObject($permission))
            {
                return $message->rply('err_grant_permission', [$permission->displayName()]);
            }
            
            if ($user->getGDOUser()->hasPermissionObject($permission))
            {
                return $message->rply('err_grant_already_permission', [$user->displayFullName(), $permission->displayName()]);
            }
            
            GDO_UserPermission::grantPermission($user->getGDOUser(), $permission);
            $user->getGDOUser()->changedPermissions();
            return $message->rply('msg_dog_granted_permission', [$permission->displayName(), $user->displayFullName()]);
        }
        
        else # multiple by level
        {
            $level = $permission->getLevel();
            if ($message->getGDOUser()->getLevel() < $level)
            {
                return $message->rply('err_grant_permission', [$permission->displayName()]);
            }
            $permissions = GDO_Permission::table()->allWhere("perm_level IS NOT NULL AND perm_level <= $level", 'perm_level');
            $granted = [];
            $u = $user->getGDOUser();
            foreach ($permissions as $perm)
            {
                if (!$u->hasPermissionObject($perm))
                {
                    GDO_UserPermission::grant($u, $perm);
                    $granted[] = $perm->displayName();
                }
            }
            
            if (!count($granted))
            {
                return $message->rply('err_grant_already_permission', [$user->displayFullName(), $permission->displayName()]);
            }
            else
            {
                $u->changedPermissions();
                return $message->rply('msg_dog_granted_permission', [Arrays::implodeHuman($granted), $user->displayFullName()]);
            }
        }
    }
    
}
