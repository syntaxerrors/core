<?php namespace Syntax\Core;

class Forum_ReplyObserver {

	public function deleting($model)
	{
		$model->history->each(function($history)
		{
			$history->delete();
		});
		$model->moderations->each(function($moderation)
		{
			$moderation->delete();
		});
		if ($model->roll != null) {
			$model->roll->delete();
		}

		// Delete any images for this post
		$directory = public_path() .'/img/forum/replies/images/'. $model->id;

		if (\File::isDirectory($directory)) {
			\File::deleteDirectory($directory);
		}
	}
}