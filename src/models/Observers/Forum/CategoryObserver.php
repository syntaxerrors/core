<?php namespace Syntax\Core;

class Forum_CategoryObserver {

	public function deleting($model)
	{
		$model->boards->each(function($board)
		{
			$board->delete();
		});
	}
}