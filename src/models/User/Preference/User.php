<?php
namespace Syntax\Core;

class User_Preference_User extends \BaseModel
{
    /********************************************************************
     * Declarations
     *******************************************************************/

    /**
     * Table declaration
     *
     * @var string $table The table this model uses
     */
    protected $table = 'preferences_users';


    /********************************************************************
     * Relationships
     *******************************************************************/
    public static $relationsData = array(
        'user'       => array('belongsTo', 'User',              'foreignKey' => 'user_id'),
        'preference' => array('belongsTo', 'User_Preference',   'foreignKey' => 'preference_id'),
    );

    /********************************************************************
     * Getter and Setter methods
     *******************************************************************/

    /********************************************************************
     * Extra Methods
     *******************************************************************/
    public function validateValue()
    {
        return (preg_match('/'. $this->preference->value .'/', $this->value) == 1 ? true : false);
    }
}
