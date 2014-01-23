<?php namespace Syntax\Core;

class Forum_ReplyPresenter extends CorePresenter {

	public function icon()
	{
		switch ($this->resource->forum_reply_type_id) {
			case \Forum_Reply::TYPE_ACTION:
				return '<i class="fa fa-exchange" title="Action"></i>';
			break;
			case \Forum_Reply::TYPE_CONVERSATION:
				return '<i class="fa fa-comments" title="Conversation"></i>';
			break;
			case \Forum_Reply::TYPE_INNER_THOUGHT:
				return '<i class="fa fa-cloud" title="Inner-Thought"></i>';
			break;
		}
		return false;
	}
}