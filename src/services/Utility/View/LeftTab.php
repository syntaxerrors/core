<?php

namespace Syntax\Core\Utility\View;

use HTML;

class LeftTab {

	/**
	 * Template name to load at the top of the page.
	 */
	public $header = null;

	/**
	 * Tab that is loaded on page load.
	 */
	public $defaultTab = null;

	/**
	 * Html to display while the page is loaded via ajax.
	 */
	public $loadingIcon = '<i class="fa fa-spinner fa-spin"></i>';

	/**
	 * Should the panels collapse
	 */
	public $collapasable = false;

	/**
	 * Panel objects
	 */
	public $panels = null;

	/**
	 * When the class is constructed assign a new collection to 
	 * the panels var.
	 */
	public function __construct()
	{
		$this->panels = new \Utility_Collection();
	} 

	/**
	 * Set the template that will display above the left tabs
	 *
	 * @param $headerPath The path to the view file.
	 * @return LeftTab
	 */
	public function setHeader($headerPath)
	{
		if (\CoreView::checkView($headerPath)) {
			$this->header = $headerPath;
		}

		return $this;
	}

	/**
	 * Add a new panel to the left tab
	 *
	 * @return LeftTab_Panel
	 */
	public function addPanel()
	{
		return new LeftTab\LeftTab_Panel($this);
	}

	/**
	 * The the default tab loaded at page load.
	 *
	 * @param $tab The id or number of the tab to load.
	 * @return LeftTab
	 */
	public function setDefaultTab($tab)
	{
		if (intval($tab)) {
			$this->setDefaultTab($this->panels->tabs[($tab-1)]->id);
		}
		else {
			$this->defaultTab = $tab;
		}

		return $this;
	}

	/**
	 * Set the loading html while the tap is loaded via ajax.
	 *
	 * @param $loadingIcon The HTML to display.
	 * @return LeftTab
	 */
	public function setLoadingIcon($loadingIcon)
	{
		$this->loadingIcon = $loadingIcon;

		return $this;
	}

	/**
	 * Set  the panels collapse.
	 *
	 * @param bool $collapasable
	 * @return LeftTab
	 */
	public function setCollapsable($collapasable)
	{
		$this->collapasable = (bool) $collapasable;

		return $this;
	}

	/**
	 * Build the left tab helper.
	 *
	 * @return void
	 */
	public function make()
	{
		// Set the default tab
		if ($this->defaultTab == null) {
			$this->setDefaultTab($this->panels->tabs->first()->id);
		}

		\CoreView::setRoute('helpers.lefttab')->addData('settings', $this);
	}
}