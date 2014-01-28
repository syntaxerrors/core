<?php namespace Syntax\Core;

class Forum_PostPresenter extends CorePresenter {

	public function classes()
	{
		$classes = array();

		if ($this->resource->forum_post_type_id == \Forum_Post::TYPE_ANNOUNCEMENT) {
			$classes[] = 'announcement';
		} elseif ($this->resource->forum_post_type_id == \Forum_Post::TYPE_STICKY) {
			$classes[] = 'sticky';
		} elseif ($this->resource->forum_post_type_id == \Forum_Post::TYPE_APPLICATION) {
			$classes[] = 'application';
		}
		if ($this->resource->checkUserViewed(\CoreView::getActiveUser()->id)) {
			$classes[] = 'unread';
		}

		return implode(' ', $classes);
	}

	public function link()
	{
		if ($this->resource->checkUserViewed(\CoreView::getActiveUser()->id)) {
			return '<strong>'. \HTML::link('/forum/post/view/'. $this->resource->id, $this->resource->name) .'</strong>';
		}

		return \HTML::link('/forum/post/view/'. $this->resource->id, $this->resource->name, array('class' => 'text-disabled'));
	}

	public function startedBy()
	{
		$label = null;
		if ($this->resource->forum_post_type_id == \Forum_Post::TYPE_ANNOUNCEMENT) {
			$label = '<span class="label label-default">Announcement</span>';
		} elseif ($this->resource->forum_post_type_id == \Forum_Post::TYPE_STICKY) {
			$label = '<span class="label label-default">Sticky</span>';
		} elseif ($this->resource->forum_post_type_id == \Forum_Post::TYPE_APPLICATION) {
			$label = '<span class="label label-default">Application</span>';
		}

		$block = '<small>';

		if ($label != null) {
			$block .= $label .' ';
		}

		$block .= 'Started by '. \HTML::link('/user/view/'. $this->resource->author->id, $this->resource->author->username);
		$block .= '</small>';

		return $block;
	}

	public function repliesBlock()
	{
		return '<small>
			'. $this->resource->repliesCount .' '. \Str::plural('Reply', $this->resource->repliesCount) .'
			<br />
			'. $this->resource->views .' '. \Str::plural('View', $this->resource->views) .'
		</small>';
	}

	public function lastPostBlock()
	{
		$lastUpdateType = $this->resource->lastUpdate->type->keyName;
		$lastUpdateUser = ($this->resource->lastUpdate->morph_id == null || $lastUpdateType == 'application'
			? $this->resource->lastUpdate->author : $this->resource->lastUpdate->morph);
		$lastUpdateName = ($lastUpdateUser instanceof \UserPresenter ? $lastUpdateUser->username : $lastUpdateUser->name);

		return '<small>
			'. $this->resource->lastUpdate->created_at .'
			<br />
			by '. \HTML::link('/user/view/'. $lastUpdateUser->id, $lastUpdateName) .'
		</small>';
	}

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