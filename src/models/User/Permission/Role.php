<?php
namespace Syntax\Core;

class User_Permission_Role extends \BaseModel 
{
    /********************************************************************
     * Declarations
     *******************************************************************/
    protected $table = 'roles';

    /********************************************************************
     * Aware validation rules
     *******************************************************************/

    /********************************************************************
     * Scopes
     *******************************************************************/

    /********************************************************************
     * Relationships
     *******************************************************************/
    public static $relationsData = array(
        'actions' => array('belongsToMany', 'User_Permission_Action',   'table' => 'action_roles',  'foreignKey' => 'role_id', 'otherKey' => 'action_id'),
        'users'   => array('belongsToMany', 'User',                     'table' => 'role_users',    'foreignKey' => 'role_id', 'otherKey' => 'user_id'),
    );


    /********************************************************************
     * Model Events
     *******************************************************************/

    /********************************************************************
     * Getter and Setter methods
     *******************************************************************/

    /********************************************************************
     * Extra Methods
     *******************************************************************/

}