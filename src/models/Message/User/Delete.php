<?php
namespace Syntax\Core;

class Message_User_Delete extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'message_user_deletes';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'user_id'    => 'required|exists:users,uniqueId',
		'message_id' => 'required|exists:messages,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'user'    => array('belongsTo', 'User',		'foreignKey' => 'user_id'),
		'message' => array('belongsTo', 'Message',	'foreignKey' => 'message_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

}