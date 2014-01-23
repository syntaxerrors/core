<?php namespace Syntax\Core;

class User_Permission_Role_UserPresenter extends CorePresenter {

	public function username()
	{
		return ucwords($this->resource->user->username);
	}

	public function roleName()
	{
		return ucwords($this->resource->role->name);
	}
}