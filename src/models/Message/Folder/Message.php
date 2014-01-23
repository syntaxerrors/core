<?php
namespace Syntax\Core;

class Message_Folder_Message extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table = 'message_folder_messages';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'message_id' => 'required|exists:messages,uniqueId',
		'folder_id'  => 'required|exists:message_folders,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'message' => array('belongsTo', 'Message',			'foreignKey' => 'message_id'),
		'folder'  => array('belongsTo', 'Message_Folder',	'foreignKey' => 'folder_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}