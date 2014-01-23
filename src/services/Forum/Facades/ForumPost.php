<?php 
namespace Syntax\Core\Forum\Facades;

use Illuminate\Support\Facades\Facade;

class ForumPost extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'forumpost'; }

}