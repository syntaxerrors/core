<?php namespace Syntax\Core;

class User_PreferencePresenter extends CorePresenter {

	public function hidden()
	{
		return $this->resource->hiddenFlag == 1 ? 'Hidden' : null;
	}
}