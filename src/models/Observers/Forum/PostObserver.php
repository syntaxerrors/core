<?php namespace Syntax\Core;

class Forum_PostObserver {

	public function deleting($model)
	{
		$model->replies->each(function($reply)
		{
			$reply->delete();
		});
		$model->history->each(function($history)
		{
			$history->delete();
		});
		$model->userViews->each(function($userView)
		{
			$userView->delete();
		});
		$model->moderations->each(function($moderation)
		{
			$moderation->delete();
		});
		if ($model->status != null) {
			$model->status->delete();
		}

		// Delete any images for this post
		$directory = public_path() .'/img/forum/posts/images/'. $model->id;

		if (\File::isDirectory($directory)) {
			\File::deleteDirectory($directory);
		}
	}
}