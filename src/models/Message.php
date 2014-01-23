<?php
namespace Syntax\Core;
use Auth;

class Message extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table      = 'messages';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/**
	 * Soft Delete users instead of completely removing them
	 *
	 * @var bool $softDelete Whether to delete or soft delete
	 */
	protected $softDelete = true;

	const STANDARD           = 1;
	const EXPERIENCE         = 2;
	const ACTION_APPROVAL    = 4;
	const CHARACTER_APPROVAL = 5;
	const MODERATION         = 3;
	const PERMISSION         = 6;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'sender_id'       => 'required|exists:users,uniqueId',
		'receiver_id'     => 'required|exists:users,uniqueId',
		'title'           => 'required|max:200',
		'content'         => 'required',
		'message_type_id' => 'required|exists:message_types,id',
		'child_id'        => 'exists:messages,uniqueId',
		'parent_id'       => 'exists:messages,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'sender'   => array('belongsTo', 'User',					'foreignKey' => 'sender_id'),
		'receiver' => array('belongsTo', 'User',					'foreignKey' => 'receiver_id'),
		'child'    => array('belongsTo', 'Message',					'foreignKey' => 'child_id'),
		'parent'   => array('belongsTo', 'Message',					'foreignKey' => 'parent_id'),
		'type'     => array('belongsTo', 'Message_Type',			'foreignKey' => 'message_type_id'),
		'deletes'  => array('hasMany',   'Message_User_Delete',		'foreignKey' => 'message_id'),
		'reads'    => array('hasMany',   'Message_User_Read',		'foreignKey' => 'message_id'),
		'folders'  => array('hasMany',   'Message_Folder_Message',	'foreignKey' => 'message_id'),
	);

	/********************************************************************
	 * Model events
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * See if this message has been deleted by the user
	 *
	 * @return boolean
	 */
	public function getDeletedAttribute()
	{
		$deleted = Message_User_Delete::where('message_id', $this->id)->where('user_id', Auth::user()->id)->first();
		return ($deleted == null ? 0 : 1);
	}

	public function getReadIconAttribute()
	{
		$read = $this->userRead(Auth::user()->id);

		if ($read == 1) {
			return '<i class="fa fa-circle text-info"></i>';
		} else {
			return '<i class="fa fa-circle-o text-info"></i>';
		}
	}

	public function getReadAttribute()
	{
		return $this->userRead(Auth::user()->id);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	public function inFolder($userId)
	{
		$folders = $this->folders();

		if (count($folders) > 0) {
			$folders = $folders->filter(function ($folder) {
				if ($folder->user_id == $userId) {
					return true;
				}
			});

			if (count($folders) > 0) {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * See if this message has been read by the user
	 *
	 * @return boolean
	 */
	public function userRead($userId)
	{
		$read = Message_User_Read::where('message_id', $this->id)->where('user_id', $userId)->first();
		return ($read == null ? 0 : 1);
	}

	/**
	 * See if this message has been deleted by the user
	 *
	 * @return boolean
	 */
	public function userDeleted($userId)
	{
		$delete = Message_User_Delete::where('message_id', $this->id)->where('user_id', $userId)->first();
		return ($delete == null ? 0 : 1);
	}
}