<?php namespace Syntax\Core;

class Message_FolderObserver {

	public function deleting($model)
	{
		$model->messages->each(function($message) use ($model)
		{
			$messageFolder = Message_Folder_Message::where('message_id', $message->id)->where('folder_id', $model->id)->where('user_id', CoreView::getActiveUser()->id)->first();
			if ($messageFolder != null) {
				$messageFolder->folder_id = CoreView::getActiveUser()->inbox;
				$messageFolder->save();
			}
		});
	}
}