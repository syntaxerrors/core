<?php
namespace Syntax\Core;

class Forum_Category extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'forum_categories';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	const TYPE_STANDARD = 1;
	const TYPE_SUPPORT  = 2;

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
		'keyName'             => 'required|max:200',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'type'              => array('belongsTo',	'Forum_Category_Type',	'foreignKey' => 'forum_category_type_id'),
		'boards'            => array('hasMany',		'Forum_Board',			'foreignKey' => 'forum_category_id', 'orderBy' => array('position', 'asc')),
		'boardsByPostCount' => array('hasMany',		'Forum_Board',			'foreignKey' => 'forum_category_id', 'orderBy' => array('postsCount', 'asc')),
	);

	/********************************************************************
	 * Model events
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

    /**
     * Get count of posts in this category
     *
     * @return int
     */
	public function getPostsCountAttribute()
	{
		$postCount = $this->boards()->with('posts')->get()->posts->count();
		return $postCount;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

    /**
     * Move this category up one position
     *
     * @return int
     */
	public function moveUp()
	{
		$newValue = $this->position - 1;
		$this->position = $newValue;
		$this->save();
	}

    /**
     * Move this category down one position
     *
     * @return int
     */
	public function moveDown()
	{
		$newValue = $this->position + 1;
		$this->position = $newValue;
		$this->save();
	}

}