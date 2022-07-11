<?php
namespace GDO\DogAuth\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\GDT_DogUser;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\User\GDT_Permission;
use GDO\User\GDO_Permission;
use GDO\Dog\Dog;
use GDO\User\GDO_UserPermission;
use GDO\Util\Arrays;

final class Revoke extends DOG_Command
{
    public $priority = 40;
    public $trigger = 'revoke';
    
    public function gdoParameters() : array
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
            $grant = Grant::byTrigger('grant');
            return $grant->showPermissions($message, $user);
        }
        else
        {
            $this->revokePermission($message, $user, $permission);
        }
    }
    
    public function revokePermission(DOG_Message $message, DOG_User $user, GDO_Permission $permission)
    {
        if ($permission->getLevel() === null)
        {
            if (!$message->getGDOUser()->hasPermissionObject($permission))
            {
                if (!$message->getGDOUser()->hasPermission(Dog::OPERATOR))
                {
                    return $message->rply('err_dog_revoke_permission', [t('perm_operator'), $permission->displayName()]);
                }
                GDO_UserPermission::revoke($user->getGDOUser(), $permission);
                $user->getGDOUser()->changedPermissions();
                return $message->rply('msg_dog_revoked_permission', [$permission->displayName(), $user->displayFullName()]);
            }
        }
        else
        {
            $level = $permission->getLevel();
            if ($message->getGDOUser()->getLevel() <= $level)
            {
                return $message->rply('err_dog_revoke_level', []);
            }
            
            $permissions = GDO_Permission::table()->allWhere("perm_level IS NOT NULL and perm_level <= $level", 'perm_level');
            $revoked = [];
            foreach ($permissions as $perm)
            {
                if ($user->getGDOUser()->hasPermissionObject($perm))
                {
                    GDO_UserPermission::revoke($user->getGDOUser(), $perm);
                    $revoked[] = $perm->displayName();
                }
            }
            
            if (!count($revoked))
            {
                return $message->rply('err_revoke_unchanged');
            }
            else
            {
                $user->getGDOUser()->changedPermissions();
                return $message->rply('msg_dog_revoked_permission', [Arrays::implodeHuman($revoked), $user->displayFullName()]);
            }
            
        }
    }

}
