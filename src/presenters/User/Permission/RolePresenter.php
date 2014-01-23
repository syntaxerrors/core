<?php namespace Syntax\Core;

class User_Permission_RolePresenter extends CorePresenter {

	public function fullname()
	{
		return $this->resource->group .' - '. $this->resource->name;
	}
}