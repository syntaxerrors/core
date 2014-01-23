<?php

namespace Syntax\Core\Utility;

class Menu {

	/**
	 * The main top menu
	 */
	public $menu;

	public function __construct()
	{
		$this->menu = new \Utility_Collection();
	}

	public function get()
	{
		return $this;
	}

	public function addMenuItem($item, $link, $icon = null, $position = null, $alignment = 'left')
	{
		$menuItem            = new \stdClass();
		$menuItem->title     = $item;
		$menuItem->link      = $link;
		$menuItem->icon      = $icon;
		$menuItem->alignment = $alignment;
		$menuItem->children  = new \Utility_Collection();

		if ($position == null) {
			$this->menu->add($menuItem);
		} else {
			$this->menu->insertAfter($position, $menuItem, $position);
		}

		return $this;
	}

	public function addMenuChild($menuItem, $childText, $childLink, $childIcon = null)
	{
		$child           = new \stdClass();
		$child->title    = $childText;
		$child->link     = $childLink;
		$child->icon     = $childIcon;
		$child->children = new \Utility_Collection();

		foreach ($this->menu as $menu) {
			if ($menu->title == $menuItem) {
				$menu->children->add($child);
			}
		}

		return $this;
	}

	public function addChildChild($menuItem, $childItem, $childText, $childLink)
	{
		$child           = new \stdClass();
		$child->title    = $childText;
		$child->link     = $childLink;

		foreach ($this->menu as $menu) {
			if ($menu->title == $menuItem) {
				foreach ($menu->children as $menuChild) {
					if ($menuChild->title == $childItem) {
						$menuChild->children->add($child);
					}
				}
			}
		}

		return $this;
	}

	public function emptyMenu()
	{
		$this->menu = new \Utility_Collection();
	}
}