<?php
namespace GDO\DogAuth;

use GDO\Core\GDO_Module;

final class Module_DogAuth extends GDO_Module
{
    public function getDependencies() : array { return ['Dog']; }
    
    public function onLoadLanguage() : void { $this->loadLanguage('lang/auth'); }
    
}
