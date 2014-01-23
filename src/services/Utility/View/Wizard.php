<?php

namespace Syntax\Core\Utility\View;

use HTML;

/**
 * This class is used to help create wizards.  To use make sure you have views for each step in the viewLocation.
 * They should be named step1.blade.php, step2.blade.php, etc.
 */

class Wizard {

	/**
	 * The title of each step
	 */
	public $steps = array();

	/**
	 * The location of the step files
	 */
	public $viewLocation;

	public function get()
	{
		return $this;
	}

	public function make()
	{
		\CoreView::setRoute('helpers.wizard')->addData('settings', $this);
	}

	public function setViewLocation($path)
	{
		$this->viewLocation = $path;

		return $this;
	}

	public function addStep($stepTitle)
	{
		$this->steps[] = $stepTitle;

		return $this;
	}

	public function addSteps(array $steps)
	{
		$this->steps = array_merge($this->steps, $steps);

		return $this;
	}

	public function getSteps()
	{
		return $this->steps;
	}
}