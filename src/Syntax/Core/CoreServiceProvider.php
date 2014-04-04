<?php namespace Syntax\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	const version = '1.0.0';

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('syntax/core');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->shareWithApp();
		$this->loadConfig();
		$this->registerViews();
		$this->registerAliases();
	}

	/**
	 * Share the package with application
	 *
	 * @return void
	 */
	protected function shareWithApp()
	{
		$this->app['core'] = $this->app->share(function($app)
		{
			require_once(__DIR__.'/../../../views/helpers/customFormFields.php');
			return true;
		});
	}

	/**
	 * Load the config for the package
	 *
	 * @return void
	 */
	protected function loadConfig()
	{
		$this->app['config']->package('syntax/core', __DIR__.'/../../../config');
	}

	/**
	 * Register views
	 *
	 * @return void
	 */
	protected function registerViews()
	{
		$this->app['view']->addNamespace('core', __DIR__.'/../../../views');
	}

	/**
	 * Register aliases
	 *
	 * @return void
	 */
	protected function registerAliases()
	{
		$aliases = [
			'HTML'                        => 'Syntax\Core\HTML',
			'View'                        => 'Syntax\Core\View\ViewFacade',
			'Mobile'                      => 'Syntax\Core\Utility\Facades\Mobile',
			'CoreView'                    => 'Syntax\Core\Utility\Facades\CoreView',
			'CoreImage'                   => 'Syntax\Core\Utility\Facades\CoreImage',
			'Crud'                        => 'Syntax\Core\Utility\Facades\Crud',
			'Wizard'                      => 'Syntax\Core\Utility\Facades\Wizard',
			'LeftTabs'                    => 'Syntax\Core\Utility\Facades\LeftTabs',
			'LeftTab'                     => 'Syntax\Core\Utility\Facades\LeftTab',
			'bForm'                       => 'Syntax\Core\Utility\Facades\bForm',
			'Ajax'                        => 'Syntax\Core\Utility\Facades\Ajax',
			'Post'                        => 'Syntax\Core\Utility\Facades\Post',
			'BBCode'                      => 'Syntax\Core\Utility\Facades\BBCode',
			'SocketIOClient'              => 'ElephantIO\Client',
			'Github'                      => 'Github\Client',
			'User'                        => 'Syntax\Core\User',
			'Message'                     => 'Syntax\Core\Message',
			'Message_Folder'              => 'Syntax\Core\Message_Folder',
			'Message_Folder_Message'      => 'Syntax\Core\Message_Folder_Message',
			'Message_Type'                => 'Syntax\Core\Message_Type',
			'Message_User_Delete'         => 'Syntax\Core\Message_User_Delete',
			'Message_User_Read'           => 'Syntax\Core\Message_User_Read',
			'User_Preference'             => 'Syntax\Core\User_Preference',
			'User_Preference_User'        => 'Syntax\Core\User_Preference_User',
			'User_Permission_Action'      => 'Syntax\Core\User_Permission_Action',
			'User_Permission_Action_Role' => 'Syntax\Core\User_Permission_Action_Role',
			'User_Permission_Role'        => 'Syntax\Core\User_Permission_Role',
			'User_Permission_Role_User'   => 'Syntax\Core\User_Permission_Role_User',
			'Seed'                        => 'Syntax\Core\Seed',
			'Migration'                   => 'Syntax\Core\Migration',
			'Control_Exception'           => 'Syntax\Core\Control_Exception',
		];

		$appAliases = \Config::get('core::nonCoreAliases');

		foreach ($aliases as $alias => $class) {
			if (!is_null($appAliases)) {
				if (!in_array($alias, $appAliases)) {
					\Illuminate\Foundation\AliasLoader::getInstance()->alias($alias, $class);
				}
			} else {
				\Illuminate\Foundation\AliasLoader::getInstance()->alias($alias, $class);
			}
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}