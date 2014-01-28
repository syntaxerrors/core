<?php
namespace Syntax\Core;

use HTML;

class Forum_View extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'forum_view';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;


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
		'user_id'             => 'required|exists:users,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'author' => array('belongsTo', 'User', 'foreignKey' => 'user_id'),
		'morph'  => array('morphTo'),
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/
	public function scopeStatus($query, $statusId)
	{
			return $query->join('forum_post_status', 'forum_post_status.forum_post_id', '=', 'forum_view.uniqueId')->where('forum_post_status.forum_support_status_id', $statusId);
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getModifiedAtReadableAttribute()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->lastModified));
	}

	public function getCreatedAtReadableAttribute()
	{
		return $this->created_at->format('F jS, Y \a\t h:ia');
	}

	public function getLinkAttribute()
	{
		switch ($this->type) {
			case 'Forum_Post':
				return HTML::link('/forum/post/view/'. $this->id, $this->name);
			break;
			case 'Forum_Reply':
				return HTML::link('/forum/post/view/'. $this->relation()->post->id .'#reply:'. $this->id, $this->name);
			break;
		}
	}

	public function getReplyCountAttribute()
	{
		if ($this->type == 'Forum_Post') {
			return $this->relation()->replies->count();
		}

		return $this->relation()->post->replies->count();
	}

	public function getDisplayNameAttribute()
	{
		if ($this->morph_id != null) {
			return $this->morph->name;
		} else {
			return $this->author->username;
		}
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public function relation()
	{
		$model = new $this->type;
		return $model::find($this->id);
	}
}