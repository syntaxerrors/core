<?php 
namespace Syntax\Core\Utility\Facades;

use Illuminate\Support\Facades\Facade;

class Crud extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'crud'; }

}