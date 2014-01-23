<?php namespace Syntax\Core;

class Forum_Post_StatusPresenter extends CorePresenter {

	public function icon()
	{
		switch ($this->resource->forum_support_status_id) {
			case \Forum_Support_Status::TYPE_OPEN:
				return '<i class="fa fa-bolt text-info" title="Open" style="font-size: 14px;"></i>';
			break;
			case \Forum_Support_Status::TYPE_IN_PROGRESS:
				return '<i class="fa fa-clock-o text-warning" title="In progress" style="font-size: 14px;"></i>';
			break;
			case \Forum_Support_Status::TYPE_RESOLVED:
				return '<i class="fa fa-check-square-o text-success" title="Resolved" style="font-size: 14px;"></i>';
			break;
			case \Forum_Support_Status::TYPE_WONT_FIX:
				return '<i class="fa fa-ban text-error" title="Won\'t fix" style="font-size: 14px;"></i>';
			break;
		}
		return false;
	}
}