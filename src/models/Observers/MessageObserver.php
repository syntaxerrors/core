<?php namespace Syntax\Core;

class MessageObserver {

	public function created($model)
	{
		// Move the message to the receivers's inbox
		$folder             = new Message_Folder_Message;
		$folder->user_id    = $model->receiver->id;
		$folder->message_id = $model->id;
		$folder->folder_id  = $model->receiver->inbox;

		$folder->save();

		// Only send to the senders inbox if the sender and receiver are different
		if ($model->receiver->id != $model->sender->id) {
			// Move the message to the senders's inbox
			$folder             = new Message_Folder_Message;
			$folder->user_id    = \CoreView::getActiveUser()->id;
			$folder->message_id = $model->id;
			$folder->folder_id  = \CoreView::getActiveUser()->inbox;

			$folder->save();
		}

		// If this is a reply, let the child know
		if ($model->child_id != null) {
			$child            = Message::find($model->child_id);
			$child->parent_id = $model->id;

			$child->save();
		}

		// Set as read if this is a reply
		$readMessage             = new Message_User_Read;
		$readMessage->message_id = $model->id;
		$readMessage->user_id    = \CoreView::getActiveUser()->id;

		$readMessage->save();
	}
}