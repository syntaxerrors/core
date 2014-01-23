<?php namespace Syntax\Core;

class Forum_BoardObserver {

	public function deleting($model)
	{
		// Make any child boards normal boards
		$childBoards = \Forum_Board::where('parent_id', $model->id)->get();

		if ($childBoards->count() > 0) {
			foreach ($childBoards as $childBoard) {
				$childBoard->parent_id = null;
				$childBoard->save();
			}
		}

		$model->posts->each(function($post)
		{
			$post->delete();
		});
	}
}