<?php
namespace Syntax\Core;

use Auth;

class Forum_Post extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'forum_posts';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	const TYPE_ANNOUNCEMENT  = 4;
	const TYPE_APPLICATION   = 8;
	const TYPE_CONVERSATION  = 5;
	const TYPE_INNER_THOUGHT = 6;
	const TYPE_LOCKED        = 2;
	const TYPE_STANDARD      = 1;
	const TYPE_STICKY        = 3;

	/**
	 * Soft Delete users instead of completely removing them
	 *
	 * @var bool $softDelete Whether to delete or soft delete
	 */
	protected $softDelete = true;


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
		'name'                => 'required|max:200',
		'content'             => 'required',
		'forum_board_id'      => 'required|exists:forum_boards,uniqueId',
		'user_id'             => 'required|exists:users,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'board'       => array('belongsTo',	'Forum_Board',			'foreignKey' => 'forum_board_id'),
		'author'      => array('belongsTo',	'User',					'foreignKey' => 'user_id'),
		'type'        => array('belongsTo',	'Forum_Post_Type',		'foreignKey' => 'forum_post_type_id'),
		'replies'     => array('hasMany',	'Forum_Reply',			'foreignKey' => 'forum_post_id'),
		'userViews'   => array('hasMany',	'Forum_Post_View',		'foreignKey' => 'forum_post_id'),
		'history'     => array('hasMany',	'Forum_Post_Edit',		'foreignKey' => 'forum_post_id', 'orderBy' => array('created_at', 'desc')),
		'status'      => array('hasOne',	'Forum_Post_Status',	'foreignKey' => 'forum_post_id'),
		'moderations' => array('morphMany',	'Forum_Moderation',		'name'       => 'resource'),
		'morph'       => array('morphTo'),
	);

	/********************************************************************
	 * Model events
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getImagesAttribute()
	{
		return str_replace(public_path(), '', \File::files(public_path() .'/img/forum/posts/images/'. $this->id));
	}

	public function getRepliesCountAttribute()
	{
		return $this->replies()->count();
	}
	public function getModerationCountAttribute()
	{
		return $this->moderations->count();
	}
	public function getLastUpdateAttribute()
	{
		$lastReply = $this->replies()
			->orderBy('created_at', 'desc')
			->first();

		if ($lastReply != null) {
			return $lastReply;
		}
		return $this;
	}
	public function getDisplayNameAttribute()
	{
		if ($this->morph_id != null) {
			return $this->morph->name;
		} else {
			return $this->author->username;
		}
	}

	/**
	* Get the next post in order of modified at
	*/
	public function getNextPostAttribute()
	{
		return Forum_Post::where('forum_board_id', '=', $this->forum_board_id)
			->where('modified_at', '<', $this->modified_at)
			->orderBy('modified_at', 'desc')
			->first();
	}

	/**
	* Get the previous post in order of modified at
	*/
	public function getPreviousPostAttribute()
	{
		return Forum_Post::where('forum_board_id', '=', $this->forum_board_id)
			->where('modified_at', '>', $this->modified_at)
			->orderBy('modified_at', 'asc')
			->first();
	}

	/**
	* Get an easy reference for what model we are dealing with
	*/
	public function getForumTypeAttribute()
	{
		return 'post';
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	public function incrementViews()
	{
		$this->views = $this->views + 1;
		$this->save();
	}

	public function userViewed($userId)
	{
		$viewed = Forum_Post_View::where('forum_post_id', '=', $this->id)
			->where('user_id', '=', $userId)
			->first();

		if ($viewed == null) {
			$viewed                = new Forum_Post_View;
			$viewed->forum_post_id = $this->id;
			$viewed->user_id       = $userId;
			$viewed->save();
		}
	}

	public function checkUserViewed($userId)
	{
		$viewed = Forum_Post_View::where('forum_post_id', '=', $this->id)
			->where('user_id', '=', $userId)
			->first();

		if ($viewed != null) {
			return true;
		}
		return false;
	}

	public function deleteViews()
	{
		$this->userViews->each(function($view)
		{
			$view->delete();
		});
	}

	public function addEdit($reason)
	{
		$edit                = new Forum_Post_Edit;
		$edit->forum_post_id = $this->id;
		$edit->user_id       = Auth::user()->id;
		$edit->reason        = $reason;

		$edit->save();
	}

	public function setAttachmentEdit($imageName)
	{
		$edit                = new Forum_Post_Edit;
		$edit->forum_post_id = $this->id;
		$edit->user_id       = $this->activeUser->id;
		$edit->reason        = 'Uploaded File: '. $imageName;

		$edit->save();
	}

	public function setStatus($statusId)
	{
		$status                          = new Forum_Post_Status;
		$status->forum_post_id           = $this->id;
		$status->forum_support_status_id = $statusId;

		$status->save();

		if (count($status->getErrors()->all()) > 0) {
			ppd($status->getErrors()->all());
		}
	}

}