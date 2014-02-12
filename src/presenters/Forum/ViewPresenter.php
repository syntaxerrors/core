<?php namespace Syntax\Core;

use HTML;

class Forum_ViewPresenter extends CorePresenter {

	/**
	 * Make the last active date easier to read
	 *
	 * @return string
	 */
	public function modifiedAtReadable()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->resource->lastModified));
	}

	public function link()
	{
		switch ($this->resource->type) {
			case 'Forum_Post':
				return HTML::link('/forum/post/view/'. $this->resource->id, $this->resource->name);
			break;
			case 'Forum_Reply':
				$post = Forum_Reply::find($this->resource->id)->post;
				return HTML::link('/forum/post/view/'. $post->id .'#reply:'. $this->resource->id, $this->resource->name);
			break;
		}
	}

	public function replyCount()
	{
		if ($this->resource->type == 'Forum_Post') {
			return Forum_Reply::where('forum_post_id', $this->resource->id)->count();
		}

		return 0;
	}

	public function displayName()
	{
		if ($this->resource->morph_id != null) {
			return $this->resource->morph->name;
		} else {
			return $this->resource->author->username;
		}
	}
}