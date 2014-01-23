<?php
namespace Syntax\Core;

class Forum_Post_Status extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	protected $table    = 'forum_post_status';
	protected $fillable = array('forum_support_status_id');

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
		'forum_post_id'           => 'required|exists:forum_posts,uniqueId',
		'forum_support_status_id' => 'required|exists:forum_support_status,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'post'   => array('belongsTo', 'Forum_Post',			'foreignKey' => 'forum_post_id'),
		'status' => array('belongsTo', 'Forum_Support_Status',	'foreignKey' => 'forum_support_status_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

}