<?php
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Core_BaseController extends Controller {

	protected $activeUser;

	protected $layout;

	protected $github;

	/**
	 * Create a new Controller instance.
	 * Assigns the active user
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Set up our view
		CoreView::setUp();

		// Login required options
		if (Auth::check()) {
			if (!Session::has('activeUser')) {
				Session::put('activeUser', Auth::user());
			}
			$this->activeUser = Session::get('activeUser');
			$this->activeUser->updateLastActive();

			CoreView::setActiveUser($this->activeUser);

			$this->setGithubClient();
		}

		// Set up the page details
		$this->setAreaDetails(Request::segment(1));

		// Set up the menu
		$this->getMenu();
		$this->setMenu();
	}

	public function setGithubClient()
	{
		$github = new Github(
			new \Github\HttpClient\CachedHttpClient(
				array('cache_dir' => storage_path() .'/cache')
			)
		);

		if ($this->activeUser->githubToken != null) {
			$github->authenticate($this->activeUser->githubToken, null, 'http_token');
		}

		$this->github = $github;
	}

	public function setMenu()
	{
		// Handle the different menus
		$siteMenu = isset($this->activeUser) ? $this->activeUser->getPreferenceValueByKeyName('SITE_MENU') : Config::get('core::menu');

		if (CoreView::get()->mobile == true || $siteMenu == 'twitter') {
			// Set the menu to twitter's style
			Menu::handler('main')->addClass('nav navbar-nav');
			Menu::handler('mainRight')->addClass('nav navbar-nav navbar-right');

			// Handle children
			Menu::handler('main')->getItemsByContentType('Menu\Items\Contents\Link')
				->map(function($item) {
					if ($item->hasChildren()) {
						$item->getContent()->addClass('dropdown-toggle')->dataToggle('dropdown');
						$item->getContent()->value($item->getContent()->getValue() .' <b class="caret"></b>');
						$item->getChildren()->addClass('dropdown-menu');
					}
				});
			Menu::handler('mainRight')->getItemsByContentType('Menu\Items\Contents\Link')
				->map(function($item) {
					if ($item->hasChildren()) {
						$item->getContent()->addClass('dropdown-toggle')->dataToggle('dropdown');
						$item->getContent()->value($item->getContent()->getValue() .' <b class="caret"></b>');
						$item->getChildren()->addClass('dropdown-menu');
					}
				});

			CoreView::setMenu('twitter');
		} elseif ($siteMenu == 'utopian') {
			// Set the menu to utopian's style
			Menu::handler('main')->id('utopian-navigation')->addClass('black utopian');
			Menu::handler('mainRight')->id('utopian-navigation')->addClass('black utopian');

			// Handle children
			Menu::handler('main')->getItemsByContentType('Menu\Items\Contents\Link')
				->map(function($item) {
					if ($item->hasChildren()) {
						$item->addClass('dropdown');
					}
				});
			Menu::handler('mainRight')->getItemsByContentType('Menu\Items\Contents\Link')
				->map(function($item) {
					if ($item->hasChildren()) {
						$item->addClass('dropdown');
					}
				});

			CoreView::setMenu('utopian');
		}
	}

	/********************************************************************
	 * Templating
	 *******************************************************************/
	/**
	 * Master template method
	 * Sets the template based on location and passes variables to the view.
	 *
	 * @return void
	 */
	public function setupLayout()
	{
		CoreView::setPageTitle($this->pageTitle);

		$this->layout = CoreView::make()->layout;
	}

	public function __call($method, $parameters = array())
	{
		CoreView::missingMethod($method)->make();
	}

	public function missingMethod($parameters = array())
	{
		CoreView::missingMethod($parameters[0])->make();
	}

	public function setViewData($text, $data)
	{
		CoreView::addData($text, $data);
	}

	public function setViewPath($view)
	{
		CoreView::setRoute($view);
	}

	public function setPageTitle($title)
	{
		CoreView::setPageTitle($title)->make();
	}

	public function skipView()
	{
		CoreView::skipView();
	}

	protected function checkView($view)
	{
		return CoreView::checkView($view);
	}

	/********************************************************************
	 * Permissions
	 *******************************************************************/
	public function hasRole($roles)
	{
		if (Auth::check()) {
			if ($this->activeUser->is('DEVELOPER')) {
				return true;
			}
			$access = $this->activeUser->is($roles);

			if ($access === true) {
				return true;
			}
		}
		Session::put('pre_login_url', Request::path());
		return false;
	}

	public function checkPermission($actionKeyName)
	{
		$check = $this->hasPermission($actionKeyName);

		if ($check == false) {
			$this->errorRedirect();
		}
	}

	public function hasPermission($permissions)
	{
		if (Auth::check()) {
			$access = $this->activeUser->checkPermission($permissions);

			if ($access === true) {
				return true;
			}
		}
		Session::put('pre_login_url', Request::path());
		return false;
	}

	/********************************************************************
	 * Post Requests
	 *******************************************************************/
	public function errorRedirect()
	{
		Post::addError('permission', 'You lack the permission(s) required to view this area');
		return Post::redirect('back');
	}

	public function redirect($location = null, $message = null)
	{
		return Post::redirect($location, $message);
	}

	public function save($model)
	{
		return Post::save($model);
	}

	public function checkErrorsSave($model, $path = null)
	{
		return Post::checkErrorsSave($model, $path);
	}

	public function errorCount()
	{
		return Post::errorCount();
	}

	public function getErrors()
	{
		return Post::getErrors();
	}

	public function addError($errorKey, $errorMessage)
	{
		return Post::addError($errorKey, $errorMessage);
	}

	public function setSuccessPath($path)
	{
		return Post::setSuccessPath($path);
	}

	public function setSuccessMessage($message)
	{
		return Post::setSuccessMessage($message);

	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public static function arrayToSelect($array, $key, $value, $first)
	{
		return CoreView::arrayToSelect($array, $key, $value, $first);
	}

	public function getArray($class, $message)
	{
		$objects     = $class::orderByNameAsc()->get();
		$objectArray = $this->arrayToSelect($objects, 'id', 'name', $message);

		return $objectArray;
	}
}