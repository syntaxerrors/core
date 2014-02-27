<?php namespace Syntax\Core\Utility;

use Illuminate\Support\ServiceProvider;

class UtilityServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Main utilities
		$this->registerMobile();

		$this->registerImage();

		// View utilities
		$this->registerView();

		$this->registerCrud();

		$this->registerWizard();

		$this->registerLeftTabs();

		$this->registerLeftTab();

		$this->registerBForm();

		// Response utilities
		$this->registerAjax();

		$this->registerPost();

		$this->registerBBCode();
	}

	/**
	 * Register the Mobile instance.
	 *
	 * @return void
	 */
	protected function registerMobile()
	{
		$this->app->bindShared('mobile', function($app)
		{
			return new Mobile();
		});
	}

	/**
	 * Register the Image instance.
	 *
	 * @return void
	 */
	protected function registerImage()
	{
		$this->app->bindShared('coreimage', function($app)
		{
			return new CoreImage();
		});
	}

	/**
	 * Register the View instance.
	 *
	 * @return void
	 */
	protected function registerView()
	{
		$this->app->bindShared('coreview', function($app)
		{
			return new CoreView();
		});
	}

	/**
	 * Register the CRUD instance.
	 *
	 * @return void
	 */
	protected function registerCrud()
	{
		$this->app->bindShared('crud', function($app)
		{
			return new View\Crud();
		});
	}

	/**
	 * Register the Wizard instance.
	 *
	 * @return void
	 */
	protected function registerWizard()
	{
		$this->app->bindShared('wizard', function($app)
		{
			return new View\Wizard();
		});
	}

	/**
	 * Register the Left Tabs instance.
	 *
	 * @return void
	 */
	protected function registerLeftTabs()
	{
		$this->app->bindShared('lefttabs', function($app)
		{
			return new View\LeftTabs();
		});
	}

	/**
	 * Register the Left Tabs instance.
	 *
	 * @return void
	 */
	protected function registerLeftTab()
	{
		$this->app->bindShared('lefttab', function($app)
		{
			return new View\LeftTab();
		});
	}

	/**
	 * Register the bootstrap form instance.
	 *
	 * @return void
	 */
	protected function registerBForm()
	{
		$this->app->bindShared('bform', function($app)
		{
			return new View\bForm();
		});
	}

	/**
	 * Register the Ajax instance.
	 *
	 * @return void
	 */
	protected function registerAjax()
	{
		$this->app->bindShared('ajax', function($app)
		{
			return new Response\Ajax();
		});
	}

	/**
	 * Register the Post instance.
	 *
	 * @return void
	 */
	protected function registerPost()
	{
		$this->app->bindShared('post', function($app)
		{
			return new Response\Post();
		});
	}

	/**
	 * Register the BBCode instance.
	 *
	 * @return void
	 */
	protected function registerBBCode()
	{
		$this->app->bindShared('bbcode', function($app)
		{
			return new Response\BBCode();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('mobile', 'menu', 'crud', 'ajax', 'post', 'bbcode');
	}
}