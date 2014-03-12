<?php

namespace Syntax\Core\Utility\View;

use HTML;
use CoreView;

class Crud {

	/**
	 * The title of the page
	 */
	public $title;

	/**
	 * The column to sort on
	 */
	public $sortProperty;

	/**
	 * A flag to show the delete button
	 */
	public $deleteFlag = true;

	/**
	 * The location to send deletes
	 */
	public $deleteLink;

	/**
	 * The column to find the object by
	 */
	public $deleteProperty = 'id';

	/**
	 * The resources that will be displayed
	 */
	public $resources;

	/**
	 * Sets the display to paginated
	 */
	public $paginationFlag = false;

	/**
	 * A flag to switch between multiview and standard
	 */
	public $multiViewFlag = false;

	/**
	 * Multiview object
	 */
	public $multiView;

	/**
	 * The fields to display on the page
	 */
	public $displayFields;

	/**
	 * The fields to use in the add/edit form
	 */
	public $formFields;

	public function __construct()
	{
		$this->displayFields = new \stdClass();
		$this->formFields    = new \stdClass();
	}

	public function get()
	{
		return $this;
	}

	public function make()
	{
		CoreView::setRoute('helpers.crud')->addData('settings', $this);
	}

	public function setUpMultiColumn()
	{
		$this->multiViewFlag = true;
		return new Crud\Crud_MultiView($this);
	}

	/**
	 * Add a field to the display
	 *
	 * @param  string  $fieldName
	 * @param  string  $linkLocation
	 * @param  string  $linkProperty
	 * @return Utility_Crud
	 */
	public function addDisplayField($fieldName, $linkLocation = null, $linkProperty = null)
	{
		$this->displayFields->{$fieldName}               = new \stdClass();
		$this->displayFields->{$fieldName}->linkLocation = $linkLocation;
		$this->displayFields->{$fieldName}->linkProperty = $linkProperty;

		return $this;
	}

	/**
	 * Add a field to the form
	 *
	 * @param  string  $fieldName
	 * @param  string  $fieldType
	 * @param  array   $selectArray
	 * @param  string  $placeholder
	 * @return Utility_Crud
	 */
	public function addFormField($fieldName, $fieldType, $selectArray = null, $required = false, $placeholder = null)
	{
		$this->formFields->{$fieldName}              = new \stdClass();
		$this->formFields->{$fieldName}->field       = $fieldType;
		$this->formFields->{$fieldName}->selectArray = $selectArray;
		$this->formFields->{$fieldName}->required    = $required;
		$this->formFields->{$fieldName}->placeholder = $placeholder;

		return $this;
	}

	/**
	 * Set the pagination flag
	 *
	 * @param  string  $paginationFlag
	 * @return Utility_Crud
	 */
	public function setPaginationFlag($paginationFlag)
	{
		$this->paginationFlag = $paginationFlag;

		return $this;
	}

	/**
	 * Set the title
	 *
	 * @param  string  $title
	 * @return Utility_Crud
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Set the resources
	 *
	 * @param  Utility_Collection  $resources
	 * @return Utility_Crud
	 */
	public function setResources($resources)
	{
		$this->resources = $resources;

		return $this;
	}

	/**
	 * Set the sort property
	 *
	 * @param  string  $sortProperty
	 * @return Utility_Crud
	 */
	public function setSortProperty($sortProperty)
	{
		$this->sortProperty = $sortProperty;

		return $this;
	}

	/**
	 * Set the delete flag
	 *
	 * @param  string  $deleteFlag
	 * @return Utility_Crud
	 */
	public function setDeleteFlag($deleteFlag)
	{
		$this->deleteFlag = $deleteFlag;

		return $this;
	}

	/**
	 * Set the delete link
	 *
	 * @param  string  $deleteLink
	 * @return Utility_Crud
	 */
	public function setDeleteLink($deleteLink)
	{
		$this->deleteLink = $deleteLink;

		return $this;
	}

	/**
	 * Set the delete property
	 *
	 * @param  string  $deleteProperty
	 * @return Utility_Crud
	 */
	public function setDeleteProperty($deleteProperty)
	{
		$this->deleteProperty = $deleteProperty;

		return $this;
	}
}