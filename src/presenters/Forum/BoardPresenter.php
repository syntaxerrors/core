<?php namespace Syntax\Core;

class Forum_BoardPresenter extends CorePresenter {

	public function classes()
	{
		if (\CoreView::getActiveUser()->checkUnreadBoard($this->resource->id)) {
			return 'unread';
		}

		return null;
	}

	public function link()
	{
		if (\CoreView::getActiveUser()->checkUnreadBoard($this->resource->id)) {
			$link = '<strong class="boardLink">'. \HTML::link('forum/board/view/'. $this->resource->id, $this->resource->name) .'</strong>';
		} else {
			$link = '<span class="boardLink">'. \HTML::link('/forum/board/view/'. $this->resource->id, $this->resource->name, array('class' => 'text-disabled')) .'</span>';
		}

		if ($this->resource->children->count() > 0) {
			$link .= '<br />
			<small style="margin-left: 15px;"><small>'. $this->resource->childLinks .'</small></small>';
		}

		return $link;
	}

	public function repliesBlock()
	{
		return $this->resource->postsCount .' '. \Str::plural('Post', $this->resource->postsCount) .'
			<br />
			'. $this->resource->repliesCount .' '. \Str::plural('Reply', $this->resource->repliesCount);
	}

	public function lastPostBlock()
	{
		if ($this->resource->lastUpdate !== false) {
			$lastUpdateType = $this->resource->lastUpdate->type->keyName;
			$lastUpdateUser = ($this->resource->lastUpdate->morph_id == null || $lastUpdateType == 'application'
				? $this->resource->lastUpdate->author : $this->resource->lastUpdate->morph);
			$lastUpdateName = ($lastUpdateUser instanceof \UserPresenter || $lastUpdateUser instanceof \User ? $lastUpdateUser->username : $lastUpdateUser->name);

			return '<small>
				Last Post by '. \HTML::link('/user/view/'. $this->resource->lastUpdate->author->id, $lastUpdateName) .'
				<br />
				in '. \HTML::link('forum/post/view/'. $this->resource->lastPost->id .'#reply:'. $this->resource->lastUpdate->id, $this->resource->lastUpdate->name) .'
				<br />
				on '. $this->resource->lastUpdate->created_at->format('F jS, Y \a\t h:ia') .'
			</small>';
		} else {
			return '<small>
				No posts.
			</small>';
		}
	}
}