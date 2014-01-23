<?php
namespace Syntax\Core;

class Forum_Moderation extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'forum_moderation';

	const REMOVE_REPORT = 'Post removed from moderation';
	const ADMIN_REVIEW  = 'Post moved to admin review';
	const DELETE_POST   = 'Post deleted by an administrator';

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
		'user_id' => 'required|exists:users,uniqueId',
		'reason'  => 'required',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'user'     => array('belongsTo',	'User',						'foreignKey' => 'user_id'),
		'logs'     => array('hasMany',		'Forum_Moderation_Log',		'foreignKey' => 'forum_moderation_id'),
		'replies'  => array('hasMany',		'Forum_Moderation_Reply',	'foreignKey' => 'forum_moderation_id'),
		'resource' => array('morphTo',		'withTrashed'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	public function getHistoryAttribute()
	{
		$history = array();
		if ($this->replies->count() > 0) {
			$history = $this->replies;
		}
		if ($this->logs->count() > 0) {
			if (count($history) == 0) {
				$history = $this->logs;
			} else {
				$history->merge($this->logs);
			}
		}

		if (count($history) > 0) {
			$history = $history->sortBy(function($historyObject) {
				return $historyObject->created_at;
			});
		}

		return $history;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

}