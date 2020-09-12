<?php
namespace GDO\DogAuth;

use GDO\Core\GDO_Module;

final class Module_DogAuth extends GDO_Module
{
    public function getDependencies() { return ['Dog']; }
    
    public function onLoadLanguage() { return $this->loadLanguage('lang/auth'); }
    
}
