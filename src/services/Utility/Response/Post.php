<?php

namespace Syntax\Core\Utility\Response;

use Request;
use Redirect;

class Post {

	/**
	 * The status of this ajax request.
	 */
	public $status = 'error';

	/**
	 * The location to send successful updates
	 */
	public $successPath;

	/**
	 * The message to display on success
	 */
	public $successMessage;

	/**
	 * The model being modified
	 */
	public $model;

	/**
	 * The input from the form
	 */
	public $input;

	/**
	 * A list of the errors in this ajax request
	 */
	public $errors = array();

	public function __construct($path = null, $message = null)
	{
		if ($path != null) {
			$this->setSuccessPath($path);
		} else {
			$this->setSuccessPath(Request::path());
		}

		$this->setSuccessMessage($message);
	}

	public function get()
	{
		return $this;
	}

	public function setUp($model, $input)
	{
		$this->model = $model;
		$this->input = $input;

		return $this;
	}

	public function update(array $properties)
	{
		foreach ($properties as $property) {
			if (is_array($property)) {
				$value    = $property[1];
				$property = $property[0];
			} else {
				$value = $this->input[$property];
			}

			$this->model->{$property} = $value;
		}

		return $this;
	}

    /**
     * Attempt to save the model
     *
     * @param  object  $model
     * @return Utility_Response_Post
     */
	public function save($model, $redirect = false)
	{
		$dirty = $model->getDirty();

		if (count($dirty) > 0) {
			$model->save();

			if ($model == true && count($model->errors()->all()) > 0) {

				// Messages from aware are in a different format. Parse them into the error format.
				foreach ($model->errors()->all() as $key => $message) {
					$this->addError($key, $message);
				}
			}
		}

		return $this;
	}

    /**
     * Add more than one error to the ajax response
     *
     * @param  array  $errors
     * @return Utility_Response_Post
     */
	public function addErrors(array $errors)
	{
		$this->errors = array_merge($this->errors, $errors);

		return $this;
	}

    /**
     * Add an error to the ajax response
     *
     * @param  string  $errorKey
     * @param  string  $errorMessage
     * @return Utility_Response_Post
     */
	public function addError($errorKey, $errorMessage)
	{
		$this->errors[$errorKey] = $errorMessage;

		return $this;
	}

    /**
     * Get the currect response errors
     *
     * @return array
     */
	public function getErrors()
	{
		return $this->errors;
	}

    /**
     * count the errors in the current response
     *
     * @return int
     */
	public function errorCount()
	{
		return count($this->errors);
	}

    /**
     * Save then check for errors, call redirect if there are any
     *
     * @return int
     */
	public function checkErrorsSave($model, $path = null)
	{
		$this->save($model);

		if ($this->errorCount() > 0) {
			return $this->redirect($path);
		}
	}

    /**
     * Set the response status
     *
     * @param  string  $newStatus
     * @return Utility_Response_Post
     */
	public function setStatus($newStatus)
	{
		$this->status = $newStatus;

		return $this;
	}

    /**
     * Set the success parameters
     *
     * @param  string  $path
     * @param  string  $message
     * @return Utility_Response_Post
     */
	public function setSuccess($path = null, $message = null)
	{
		$this->setSuccessPath($path);
		$this->setSuccessMessage($message);

		return $this;
	}

    /**
     * Set the success path
     *
     * @param  string  $path
     * @return Utility_Response_Post
     */
	public function setSuccessPath($path = null)
	{
		if ($path != null) {
			$this->successPath    = $path;
		}

		return $this;
	}

    /**
     * Set the success message
     *
     * @param  string  $message
     * @return Utility_Response_Post
     */
	public function setSuccessMessage($message = null)
	{
		if ($message != null) {
			$this->successMessage    = $message;
		}

		return $this;
	}

    /**
     * get the response status
     *
     * @return string
     */
	public function getStatus()
	{
		return $this->status;
	}

    /**
     * Convert this object to a json response and send it
     *
     * @param  string  $path
     * @param  string  $message
     * @return Redirect
     */
	public function redirect($path = null, $message = null)
	{
		$this->setSuccess($path, $message);

		if ($this->errorCount() > 0) {
			if ($path == 'back') {
				$back = $this->redirectBack();
				return $back->with('errors', $this->getErrors())->send();
			} else {
				return Redirect::to(Request::path())->with('errors', $this->getErrors())->send();
			}
		} else {
			if ($this->successMessage == null) {
				if ($this->successPath == 'back') {
					$back = $this->redirectBack();
					return $back->send();
				}
				return Redirect::to($this->successPath)->send();
			} else {
				if ($this->successPath == 'back') {
					$back = $this->redirectBack();
					return $back->with('message', $this->successMessage)->send();
				}
				return Redirect::to($this->successPath)->with('message', $this->successMessage)->send();
			}
		}
	}

	protected function redirectBack()
	{
		if (!Redirect::getUrlGenerator()->getRequest()->headers->get('referer')) {
			return Redirect::to('/');
		} else {
			return Redirect::back();
		}
	}
}