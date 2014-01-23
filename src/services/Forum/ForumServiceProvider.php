<?php namespace Syntax\Core\Forum;

use Illuminate\Support\ServiceProvider;

class ForumServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerForumPost();
	}

	/**
	 * Register the Post instance.
	 *
	 * @return void
	 */
	protected function registerForumPost()
	{
		$this->app->bindShared('forumpost', function($app)
		{
			return new ForumPost();
		});
	}
}