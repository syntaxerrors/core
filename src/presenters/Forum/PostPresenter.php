<?php namespace Syntax\Core;

class Forum_PostPresenter extends CorePresenter {

	/**
	 * Make the last active date easier to read
	 *
	 * @return string
	 */
	public function modifiedAtReadable()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->resource->modified_at));
	}

	public function icon()
	{
		if ($this->resource->board->category->forum_category_type_id == \Forum_Category::TYPE_SUPPORT && $this->resource->forum_post_type_id != \Forum_Post::TYPE_ANNOUNCEMENT) {
			return $this->resource->status->icon;
		} else {
			switch ($this->resource->forum_post_type_id) {
				case \Forum_Post::TYPE_ANNOUNCEMENT:
					return '<i class="fa fa-exclamation-triangle" title="Announcement"></i>';
				break;
				case \Forum_Post::TYPE_APPLICATION:
					return '<i class="fa fa-inbox" title="Application"></i>';
				break;
				case \Forum_Post::TYPE_CONVERSATION:
					return '<i class="fa fa-comments" title="Conversation"></i>';
				break;
				case \Forum_Post::TYPE_INNER_THOUGHT:
					return '<i class="fa fa-cloud" title="Inner-Thought"></i>';
				break;
				case \Forum_Post::TYPE_LOCKED:
					return '<i class="fa fa-lock" title="Locked"></i>';
				break;
				case \Forum_Post::TYPE_STICKY:
					return '<i class="fa fa-thumb-tack" title="Sticky"></i>';
				break;
			}
		}
	}
}