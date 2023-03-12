<?php
namespace GDO\DogAuth\lang;
return [
	'dog_help_login' => 'Authenticate with #BOT#. You will need to #CMD#register first.',
	'err_not_registered' => 'You are not registered with this #BOT#. Utilize the register command beforehand.',
	'err_already_authed' => 'You are already authenticated with #BOT#.',
	'err_login_blocked' => 'Please wait %.02fs before you try again.',
	'err_dog_authenticate' => 'The password was wrong.',
	'msg_dog_authenticated' => 'Welcome back! You are now authenticated.',
	    
	'mt_dogauth_super' => 'If you know the super secret password you get granted all permissions on this server.',
	'err_dog_superword' => 'The password is wrong!',
	'msg_dog_super_granted' => 'You were now granted all permissions on this server.',
	'msg_dog_operator_granted' => 'You were granted operator permissions on this server.',
	    
	'dog_help_register' => 'Register your account with #BOT#. Provide your old and new password to change it.',
	'err_dog_already_registered' => 'You are already registered. You can change your password with #CMD#register <oldPass> <newPass>.',
	'err_dog_wrong_old_password' => 'Your old password is wrong.',
	'msg_dog_password_changed' => 'Your password has been changed successfully.',
	'msg_dog_registered' => 'You have been registered and authenticated with #BOT#.',
	
	'dog_help_grant' => 'Show the permissions for a user.',
	'msg_dog_show_permissions' => '%s has the following permissions: %s.',
	'err_dog_grant' => 'You do need higher or equal permissions than %s to do that.',
	'err_grant_permission' => 'You need the %s permission yourself to grant it.',
	'err_grant_already_permission' => '%s already has the %s permission.',
	'msg_dog_granted_permission' => 'You granted the %s permission to %s.',
	    
	'dog_help_revoke' => 'Revoke a permission for a user',
	'err_dog_revoke_permission' => 'You need the %s permission to revoke %s permissions.',
	'msg_dog_revoked_permission' => 'You revoked the %s permissions for %s.',
	'err_dog_revoke_level' => 'Your permissions are not high enough revoke these permissions that.',
	'err_revoke_unchanged' => 'Nothing has changed.',
	
	'err_dog_not_authenticated' => 'You are not authenticated with #BOT#.',
	'msg_dog_logged_out' => 'You are now logged out.',    
];
