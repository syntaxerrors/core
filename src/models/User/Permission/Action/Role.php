<?php
namespace Syntax\Core;

class User_Permission_Action_Role extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'action_roles';

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
		'action_id' => 'required|exists:actions,id',
		'role_id'   => 'required|exists:roles,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'action' => array('belongsTo', 'User_Permission_Action',	'foreignKey' => 'action_id'),
		'role'   => array('belongsTo', 'User_Permission_Role',		'foreignKey' => 'role_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}