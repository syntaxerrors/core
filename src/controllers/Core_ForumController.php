<?php

class Core_ForumController extends BaseController {

	public function getIndex()
	{
		// Get the categories
		$categories         = Forum_Category::with(array('type', 'boards'))->orderBy('position', 'asc')->get();
		$statuses           = Forum_Post_Status::all();

		$openIssues       = 0;
		$inProgressIssues = 0;
		$resolvedIssues   = 0;
		foreach ($statuses as $status) {
			switch ($status->forum_support_status_id) {
				case Forum_Support_Status::TYPE_OPEN:
					$openIssues++;
				break;
				case Forum_Support_Status::TYPE_IN_PROGRESS:
					$inProgressIssues++;
				break;
				case Forum_Support_Status::TYPE_RESOLVED:
					$resolvedIssues++;
				break;
			}
		}

		$forum              = new Forum;
		$recentPosts        = $forum->recentPosts();
		$recentSupportPosts = $forum->recentSupportPosts();

		// Set the template
		$this->setViewData('categories', $categories);
		$this->setViewData('openIssues', $openIssues);
		$this->setViewData('inProgressIssues', $inProgressIssues);
		$this->setViewData('resolvedIssues', $resolvedIssues);
		$this->setViewData('recentPosts', $recentPosts);
		$this->setViewData('recentSupportPosts', $recentSupportPosts);
	}

	public function getSearch()
	{
		$typesArray = [
			'all'         => 'All types',
			'Forum_Post'  => 'Post',
			'Forum_Reply' => 'Reply'
		];

		$users = User::orderByNameAsc()->get();
		$users = $this->arrayToSelect($users, 'id', 'username', 'Select a user');

		$this->setViewData('typesArray', $typesArray);
		$this->setViewData('users', $users);
	}

	public function postSearch()
	{
		$searchTerm = Input::get('keyword');

		$posts   = Forum_View::where('name', 'LIKE', '%'. $searchTerm .'%')->orWhere('content', 'LIKE', '%'. $searchTerm .'%')->paginate(20);

		$typesArray = [
			'all'         => 'All types',
			'Forum_Post'  => 'Post',
			'Forum_Reply' => 'Reply'
		];

		$users = User::orderByNameAsc()->get();
		$users = $this->arrayToSelect($users, 'id', 'username', 'Select a user');

		$this->setViewData('typesArray', $typesArray);
		$this->setViewData('users', $users);
		$this->setViewData('posts', $posts);
	}

	public function getSearchResults()
	{
		$searchTerm = Input::get('keyword');
		$type       = Input::get('type');
		$user       = Input::get('user');

		$posts   = Forum_View::orderBy('lastModified', 'desc');

		if ($user != '0') {
			$posts->where('user_id', $user);
		}

		if ($type != 'all') {
			$posts->where('type', $type);
		}

		if ($searchTerm != '') {
			if ($user != '0' || $type != 'all') {
				$posts->where(function ($query) use ($searchTerm) {
					$query->where('name', 'LIKE', '%'. $searchTerm .'%');
					$query->orWhere('content', 'LIKE', '%'. $searchTerm .'%');
				});
			} else {
				$posts->where('name', 'LIKE', '%'. $searchTerm .'%')->orWhere('content', 'LIKE', '%'. $searchTerm .'%');
			}
		}

		$posts = $posts->paginate(20);

		$this->setViewData('posts', $posts);
	}

	public function postPreview()
	{
		$this->skipView();
		$input = Input::all();
		return BBCode::parse(e($input['update']));
	}

	public function getMarkAllRead()
	{
		$this->skipView();

		$forum = new Forum;
		$forum->markAllReadByUser($this->activeUser->id);

		$this->redirect('back', 'All posts marked read.');
	}
}