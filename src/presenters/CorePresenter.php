<?php namespace Syntax\Core;

use McCool\LaravelAutoPresenter\BasePresenter;

class CorePresenter extends BasePresenter {

	public function __construct($object)
	{
		$this->resource = $object;
	}

	/**
	 * Make the last active date easier to read
	 *
	 * @return string
	 */
	public function createdAtReadable()
	{
		return $this->resource->created_at->format('F jS, Y \a\t h:ia');
	}

	/**
	 * Strip slashes from any name
	 *
	 * @return string
	 */
	public function name()
	{
		return stripslashes($this->resource->name);
	}

	public function hidden()
	{
		return $this->resource->hiddenFlag == 1 ? 'Hidden' : null;
	}

	public function active()
	{
		return $this->resource->hiddenFlag == 1 ? 'Hidden' : null;
	}
}