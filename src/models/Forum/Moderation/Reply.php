<?php
namespace Syntax\Core;

class Forum_Moderation_Reply extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'forum_moderation_replies';

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
		'forum_moderation_id' => 'required|exists:forum_moderation,id',
		'user_id'             => 'required|exists:users,uniqueId',
		'content'             => 'required',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'moderation' => array('belongsTo', 'Forum_Moderation',	'foreignKey' => 'forum_moderation_id', 'withTrashed'),
		'user'       => array('belongsTo', 'User',				'foreignKey' => 'user_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

}