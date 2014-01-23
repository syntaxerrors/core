<?php
namespace Syntax\Core;
use Auth;

class Message_Folder extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table      = 'message_folders';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'        => 'required|max:200',
		'user_id'     => 'required|exists:users,uniqueId',
		'parent_id'   => 'exists:message_folders,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'user'     => array('belongsTo',		'User',		'foreignKey' => 'user_id'),
		'messages' => array('belongsToMany',	'Message',	'table'      => 'message_folder_messages', 'foreignKey' => 'folder_id', 'otherKey' => 'message_id'),
	);

	/********************************************************************
	 * Model events
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	public function getUnreadMessagesAttribute()
	{
		$messages = Message_Folder_Message::where('folder_id', $this->id)->get();

		$messages = $messages->filter(function ($message) {
			if ($message->message->parent_id == null) {
				return true;
			}
		});

		$messageCount = $messages->count();

		if ($messageCount == 0) {
			$unreadCount = 0;
		} else {
			$messageIds = $messages->message_id->toArray();

			$readCount = Message_User_Read::whereIn('message_id', $messageIds)->where('user_id', Auth::user()->id)->count();

			$unreadCount = $messageCount - $readCount;
		}

		if ($unreadCount > 0) {
			return $unreadCount;
		} else {
			return 0;
		}
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}