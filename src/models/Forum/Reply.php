<?php
namespace Syntax\Core;

class Forum_Reply extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'forum_replies';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	const TYPE_ACTION        = 4;
	const TYPE_CONVERSATION  = 2;
	const TYPE_INNER_THOUGHT = 3;
	const TYPE_STANDARD      = 1;

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
		'user_id'             => 'required|exists:users,uniqueId',
		'forum_post_id'       => 'required|exists:forum_posts,uniqueId',
		'forum_reply_type_id' => 'required|exists:forum_reply_types,id',
		'content'             => 'required',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'post'        => array('belongsTo',	'Forum_Post',			'foreignKey' => 'forum_post_id'),
		'author'      => array('belongsTo',	'User',					'foreignKey' => 'user_id'),
		'type'        => array('belongsTo',	'Forum_Reply_Type',		'foreignKey' => 'forum_reply_type_id'),
		'roll'        => array('hasOne',	'Forum_Reply_Roll',		'foreignKey' => 'forum_reply_id'),
		'history'     => array('hasMany',	'Forum_Reply_Edit',		'foreignKey' => 'forum_reply_id', 'orderBy' => array('created_at', 'desc')),
		'moderations' => array('morphMany',	'Forum_Moderation',		'name'       => 'resource'),
		'morph'       => array('morphTo', 'withTrashed'),
		'quote'       => array('morphTo'),
	);

	/********************************************************************
	 * Model events
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getImagesAttribute()
	{
		return str_replace(public_path(), '', \File::files(public_path() .'/img/forum/replies/images/'. $this->id));
	}

    /**
     * Get count of moderation reports
     *
     * @return int
     */
	public function getModerationCountAttribute()
	{
		return $this->moderations->count();
	}

    /**
     * Get display name (will be morphed or user)
     *
     * @return string
     */
	public function getDisplayNameAttribute()
	{
		if ($this->morph != null) {
			return $this->morph->name;
		} else {
			return $this->author->username;
		}
	}

    /**
     * Get board this reply belongs to
     *
     * @return Forum_Board
     */
    public function getBoardAttribute()
    {
    	return $this->post->board;
    }

	/**
	* Get an easy reference for what model we are dealing with
	*/
	public function getForumTypeAttribute()
	{
		return 'reply';
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	public function addEdit($reason)
	{
		$edit                 = new Forum_Reply_Edit;
		$edit->forum_reply_id = $this->id;
		$edit->user_id        = Auth::user()->id;
		$edit->reason         = $reason;

		$edit->save();
	}

}