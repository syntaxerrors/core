<?php namespace Syntax\Core\Control;

use Illuminate\Support\Facades\Facade;

class CoreBugFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'corebug';
    }
}