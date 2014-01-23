<?php
namespace Syntax\Core;

class User_Permission_Role_User extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'role_users';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

    /**
     * Validation rules
     *
     * @static
     * @var array $rules All rules this model must follow
     */
	public static $rules = array(
		'user_id'       => 'required|exists:users,uniqueId',
		'role_id'       => 'required|exists:roles,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'user' => array('belongsTo', 'User',					'foreignKey' => 'user_id'),
		'role' => array('belongsTo', 'User_Permission_Role',	'foreignKey' => 'role_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}