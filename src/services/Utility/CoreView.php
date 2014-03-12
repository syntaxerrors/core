<?php

namespace Syntax\Core\Utility;

use HTML;
use Route;
use Request;
use View;
use Config;
use App;
use Session;
use Github;
use Github\HttpClient\CachedHttpClient;

class CoreView {

	public $route;

	public $routeParts;

	public $layout;

	public $menu;

	public $mobile;

	public $github;

	public $activeUser = null;

	public $errors;

	public $skipView = false;

	public $hasView = false;

	public $data = array();

	public $js = array();

	public $onReadyJs = array();

	public $jsInclude = array();

	public $css = array();

	/**
	 * Layouts array
	 *
	 * @var string[] $layouts Array of layout templates
	 */
	protected $layoutOptions = array(
		'default' => 'layouts.default',
		'ajax'    => 'layouts.ajax',
		'rss'     => 'layouts.rss'
	);

	public function setUp()
	{
		// Clean the route
		$this->cleanRoute();

		// Determine if we are mobile
		$this->mobile = Mobile::is_mobile();

		// Setup the github client
		$this->setGithubClient();

		// Set up the layout
		if ( is_null($this->layout) ) {
			if ( Request::ajax()) {
				$this->layout = View::make($this->layoutOptions['ajax']);
			} else {
				$this->layout = View::make($this->layoutOptions['default']);
			}
		} else {
			$this->layout = View::make($this->layout);
		}

		return $this;
	}

	public function get()
	{
		return $this;
	}

	public function make()
	{
		if (strpos($this->route, 'missingmethod') === false) {
			$this->makeView();
		}

		return $this;
	}

	public function makeView()
	{
		if (!$this->skipView && $this->checkView($this->route)) {
			$this->layout->menu       = $this->menu;
			$this->layout->mobile     = $this->mobile;
			$this->layout->activeUser = $this->activeUser;
			$this->layout->jsInclude  = $this->jsInclude;
			$this->layout->onReadyJs  = $this->onReadyJs;
			$this->layout->js         = $this->js;
			$this->layout->css        = $this->css;
			$this->layout->content    = View::make($this->route)->with($this->data);

			$this->hasView = true;
		} elseif (!$this->checkView($this->route)) {
			$this->errors['noView'] = $this->route;
		}
	}

	public function checkView($view)
	{
		if (View::exists($view)) {
			return true;
		}

		// Check the syntax views
		$syntaxDirectories  = File::directories(base_path('vendor/syntax'));
		foreach ($syntaxDirectories as $syntaxDirectory) {
			$package = explode('/', $syntaxDirectory);
			$package = end($package);

			if (View::exists($package .'::'. $view)) {
				return true;
			}
		}

		return false;
	}

	public function missingMethod($method)
	{
		$this->route = str_ireplace('missingMethod', $method, $this->route);

		return $this;
	}

	public function setActiveUser($activeUser)
	{
		$this->addData('activeUser', $activeUser);
		$this->activeUser = $activeUser;

		return $this;
	}

	public function getActiveUser()
	{
		return $this->activeUser;
	}

	public function setMenu($menu)
	{
		$this->menu = $menu;

		return $this;
	}

	public function skipView()
	{
		$this->skipView = true;

		return $this;
	}

	public function addData($key, $value)
	{
		$this->data[$key] = $value;

		$content = array();
		$content[$key] = $value;

		if ($this->hasView) {
			$this->layout->content->with($content);
		}

		return $this;
	}

	public function getData()
	{
		return $this->data;
	}

	public function setPageTitle($pageTitle)
	{
		$this->layout->pageTitle = $pageTitle;

		return $this;
	}

	public function setRoute($route)
	{
		$this->route = $route;

		if ($this->checkView($this->route)) {
			unset($this->errors['noview']);
		}

		return $this->make();
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function getRoute()
	{
		return $this->route;
	}

	public function getRouteParts()
	{
		return $this->routeParts;
	}

	public function getGithub()
	{
		return $this->github;
	}

	public function setGithubClient()
	{
		$this->github = new \Github(
			new \Github\HttpClient\CachedHttpClient(
				array('cache_dir' => storage_path() .'/cache')
			)
		);

		if ($this->activeUser != null && $this->activeUser->githubToken != null) {
			$this->github->authenticate($this->activeUser->githubToken, null, 'http_token');
		}

		$this->addData('github', $this->github);

		return $this;
	}

	// need to remove this. Moved to local.php
	protected function cleanRoute()
	{
		// Format a proper route for view to use
		$route         = str_replace('_', '.', Route::currentRouteAction());
		$routeParts    = explode('@', $route);
		$routeParts[1] = preg_replace('/^get/', '', $routeParts[1]);
		$routeParts[1] = preg_replace('/^post/', '', $routeParts[1]);
		$route         = strtolower(str_replace(array('Controller'), '', implode('.', $routeParts)));

		$prefix = 'core.';

		if (substr($route, 0, strlen($prefix)) == $prefix) {
			$route = substr($route, strlen($prefix));
		}

		$this->route     = $route;
		$this->routParts = explode('.', $route);
	}

	public static function arrayToSelect($array, $key = 'id', $value = 'name', $first = 'Select One')
	{
		if ($first != false) {
			$results = array(
				$first
			);
		}
		foreach ($array as $item) {
			$item = (object)$item;
			$results[$item->{$key}] = stripslashes($item->{$value});
		}

		return $results;
	}

	public function addJs($newJs)
	{
		$this->js[] = $newJs;

		return $this;
	}

	public function addOnReadyJs($newJs)
	{
		$this->onReadyJs[] = $newJs;

		return $this;
	}

	public function addJsInclude($newJs)
	{
		$this->jsInclude[] = $newJs;

		return $this;
	}

	public function addCss($css)
	{
		$this->css[] = $css;

		return $this;
	}

}