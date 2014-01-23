<?php 
namespace Syntax\Core\Forum\Facades;

use Illuminate\Support\Facades\Facade;

class Forum extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'core.forum'; }

}