<?php

namespace Syntax\Core\Utility\View;

use HTML;

/**
 * This class is used to help create wizards.  To use make sure you have views for each step in the viewLocation.
 * They should be named step1.blade.php, step2.blade.php, etc.
 */

class LeftTabs {

	/**
	 * The panels that will appear on the left
	 */
	public $panels = array();

	/**
	 * The view to use at the top of the page
	 */
	public $header = null;

	/**
	 * The ajax details
	 */
	public $ajax;

	public function get()
	{
		return $this;
	}

	public function make()
	{
		\CoreView::setRoute('helpers.lefttabs')->addData('settings', $this);
	}

	public function addPanel($title, $items, $preference = null)
	{
		foreach ($items as $key => $item) {
			if (is_int($key)) {
				$newKey = \Str::lower(str_replace(' ', '-', $item));
				$items[$newKey] = $item;
				unset($items[$key]);
			}
		}

		$this->panels[$title]             = new \stdClass();
		$this->panels[$title]->preference = $preference;
		$this->panels[$title]->items      = $items;

		return $this;
	}

	public function setHeader($headerPath)
	{
		$this->header = $headerPath;

		return $this;
	}

	public function setAjax($link, $initial)
	{
		$this->ajax          = new \stdClass();

		$this->ajax->link    = $link;
		$this->ajax->initial = $initial;

		return $this;
	}
}