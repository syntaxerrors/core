<?php
namespace Syntax\Core;

class Forum extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	/**
	 * Get all users that have a forum role
	 *
	 * @return array
	 */
	public function users()
	{
		// Get all forum roles
		return Role::where('group', 'Forum')->orderBy('priority', 'asc')->get()->users();
	}

	/**
	 * Get the recent non-support posts
	 *
	 * @return array
	 */
	public function recentPosts()
	{
		// Get all non-support categories
		return Forum_Category::with('boards.posts')
			->where('forum_category_type_id', '!=', Forum_Category::TYPE_SUPPORT)
			->get()
			->boards
			->posts
			->take(5);
	}

	/**
	 * Get the recent posts for a category
	 *
	 * @return array
	 */
	public function recentCategoryPosts($categoryId)
	{
		// Get all non-support categories
		return Forum_Category::with('boards.posts')
			->where('uniqueId', $categoryId)
			->boards
			->posts
			->take(10);
	}

	/**
	 * Get the recent support posts
	 *
	 * @return array
	 */
	public function recentSupportPosts()
	{
		// Get all non-support categories
		return Forum_Category::with('boards.posts')
			->where('forum_category_type_id', Forum_Category::TYPE_SUPPORT)
			->get()
			->boards
			->posts
			->take(3);
	}

	/**
	 * Get the unread posts for user
	 *
	 * @return Forum_Post[]
	 */
	public function unreadPostsByUser($userId)
	{
		// Get all viewed posts
		$viewedPostIds = Forum_Post_View::where('user_id', $userId)->get()->id->toArray();

		$posts = Forum_Post::whereNotIn('uniqueId', $viewedPostIds)->get();

		return $posts;
	}

	/**
	 * Set all posts read for a user
	 *
	 * @return boolean
	 */
	public function markAllReadByUser($userId)
	{
		$posts = $this->unreadPostsByUser($userId);

		if (count($posts) > 0) {
			foreach ($posts as $post) {
				$post->userViewed($userId);
			}
		}

		return true;
	}
}