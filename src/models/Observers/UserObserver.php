<?php namespace Syntax\Core;

class UserObserver {

	public function updated($model)
	{
		if (\Session::has('activeUser')) {
			// Forget the stored active user when updated
			$storedActiveUser = \Session::get('activeUser');

			if ($storedActiveUser->id == $model->id) {
				\Session::forget('activeUser');
			}
		}
	}
}