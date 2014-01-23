<?php namespace Syntax\Core;

class SeedObserver {

	public function created($model)
	{
		$model->runSeed();
	}
}