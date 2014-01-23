<?php namespace Syntax\Core;

class User_Permission_Action_RolePresenter extends CorePresenter {

	public function actionName()
	{
		return ucwords($this->resource->action->name);
	}

	public function roleName()
	{
		return ucwords($this->resource->role->name);
	}
}