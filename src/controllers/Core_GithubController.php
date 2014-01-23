<?php

class Core_GithubController extends BaseController {

	public function getIndex()
	{
		$issues        = array();
		$allRepos      = Config::get('app.allRepos');

		foreach ($allRepos as $githubUser => $repos) {
			foreach ($repos as $repo => $displayName) {
				if (!Cache::has($repo .' Issues')) {
					try {
						$this->github->api('repo')->collaborators()->check($githubUser, $repo, $this->activeUser->githubLogin);
					} catch (Exception $e) {
						continue;
					}
					$issues[$repo]['githubUser']  = $githubUser;
					$issues[$repo]['displayName'] = $displayName;
					$issues[$repo]['issues']      = $this->github->api('issue')
																 ->all($githubUser, $repo, array('state' => 'open'));

					Cache::put($repo .' Issues', $issues[$repo]['issues'], 60);
				} else {
					$issues[$repo]['githubUser']  = $githubUser;
					$issues[$repo]['displayName'] = $displayName;
					$issues[$repo]['issues']      = Cache::get($repo .' Issues');
				}
			}
		}

		$this->setViewData('issues', $issues);
		$this->setViewData('primary', Config::get('app.primaryRepo'));
	}

	public function getRefresh($repo)
	{
		$this->skipView();

		Cache::forget($repo .' Issues');

		$this->redirect('back', $repo .'\'s issues have been refreshed');
	}

	public function getTab($tab)
	{
		$this->skipView();
		Session::put('repoTab', $tab);
	}

	public function getAdd($githubUser, $repo)
	{
		$labelOptions = array('enhancement', 'bug', 'question', 'duplicate', 'invalid', 'wontfix');

		$milestones = $this->github->api('issues')->milestones()->all($githubUser, $repo, array('state' => 'open'));
		$milestones = $this->arrayToSelect($milestones, 'number', 'title', 'Select a miletone');

		$contributors = $this->github->api('repo')->contributors($githubUser, $repo);
		$contributors = $this->arrayToSelect($contributors, 'login', 'login', 'Assign to user');

		$this->setViewData('repo', $repo);
		$this->setViewData('labelOptions', $labelOptions);
		$this->setViewData('milestones', $milestones);
		$this->setViewData('contributors', $contributors);
	}

	public function postAdd($githubUser, $repo)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			if ($input['title'] == null || $input['body'] == null) {
				$this->redirect(Request::path(), 'An issue requires a title and a body.');
			}

			$labels = array();

			foreach ($input['labels'] as $label => $flag) {
				if ($flag == 1) {
					$labels[] = $label;
				}
			}
			$newIssue = $this->github->api('issue')->create($githubUser, $repo, array(
				'title'     => $input['title'],
				'body'      => $input['body'],
				'assignee'  => ($input['assignee'] != '0' ? $input['assignee'] : null),
				'milestone' => ($input['milestone'] != '0' ? $input['milestone'] : null),
				'labels'    => $labels
			));
		}

		Cache::forget($repo .' Issues');

		$this->redirect('/github', 'Issue created successfully.');
	}

	public function getEdit($githubUser, $repo, $issueNumber)
	{
		$issue    = $this->github->api('issue')->show($githubUser, $repo, $issueNumber);

		$labels       = array();
		$labelOptions = array('enhancement', 'bug', 'question', 'duplicate', 'invalid', 'wontfix');

		foreach ($issue['labels'] as $label) {
			$labels[] = $label['name'];
		}

		$milestones = $this->github->api('issues')->milestones()->all($githubUser, $repo, array('state' => 'open'));
		$milestones = $this->arrayToSelect($milestones, 'number', 'title', 'Select a miletone');

		$contributors = $this->github->api('repo')->contributors($githubUser, $repo);
		$contributors = $this->arrayToSelect($contributors, 'login', 'login', 'Assign to user');

		$this->setViewData('repo', $repo);
		$this->setViewData('issue', $issue);
		$this->setViewData('labels', $labels);
		$this->setViewData('labelOptions', $labelOptions);
		$this->setViewData('milestones', $milestones);
		$this->setViewData('contributors', $contributors);
	}

	public function postEdit($githubUser, $repo, $issueNumber)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			if ($input['title'] == null || $input['body'] == null) {
				$this->redirect(Request::path(), 'An issue requires a title and a body.');
			}

			$labels = array();

			foreach ($input['labels'] as $label => $flag) {
				if ($flag == 1) {
					$labels[] = $label;
				}
			}

			$issueFields = array(
				'title'     => $input['title'],
				'body'      => $input['body'],
				'labels'    => $labels
			);

			if ($input['assignee'] != '0') {
				$issueFields['assignee'] = $input['assignee'];
			}

			if ($input['milestone'] != 0) {
				$issueFields['milestone'] = $input['milestone'];
			}
			$newIssue = $this->github->api('issue')->update($githubUser, $repo, $issueNumber, $issueFields);
		}

		Cache::forget($repo .' Issues');

		$this->redirect('/github', 'Issue Updated successfully.');
	}

	public function getDelete($githubUser, $repo, $issueNumber)
	{
		$this->skipview();

		$this->github->api('issue')->update($githubUser, $repo, $issueNumber, array('state' => 'closed'));

		$this->redirect('/github', 'Issue closed successfully.');
	}

	public function getUser()
	{
		$issues = $this->github->api('current_user')->issues(array('sort' => 'updated', 'direction' => 'desc'));
		// ppd($issues);
		$this->setViewData('issues', $issues);
	}

	public function getComments($githubUser, $repo, $issueNumber)
	{
		$issue    = $this->github->api('issue')->show($githubUser, $repo, $issueNumber);
		$comments = $this->github->api('issue')->comments()->all($githubUser, $repo, $issueNumber);
		$events   = $this->github->api('issue')->events()->all($githubUser, $repo, $issueNumber);

		// ppd($events);
		// ppd($issue);

		$this->setViewData('repo', $repo);
		$this->setViewData('issue', $issue);
		$this->setViewData('comments', $comments);
		$this->setViewData('events', $events);
		$this->setViewData('githubUser', $githubUser);
	}

	public function postComments($githubUser, $repo, $issueNumber)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$this->github->api('issue')->comments()->create($githubUser, $repo, $issueNumber, array('body' => $input['body']));

			if (isset($input['close'])) {
				$this->github->api('issue')->update($githubUser, $repo, $issueNumber, array('state' => 'closed'));
			}
		}

		Cache::forget($repo .' Issues');

		return Redirect::to(Request::path())->with('message', 'Comment submitted.');
	}

	public function getDeleteComment($githubUser, $repo, $issueNumber, $commentId)
	{
		$this->skipView();

		$this->github->api('issue')->comments()->remove($githubUser, $repo, $commentId);

		Cache::forget($repo .' Issues');

		return Redirect::to('/github/comments/'. $githubUser .'/'. $repo .'/'. $issueNumber)->with('message', 'Comment removed.');
	}

	public function getMilestones($githubUser, $repo)
	{
		$milestones = $this->github->api('issue')->milestones()->all($githubUser, $repo);

		foreach ($milestones as $key => $milestone) {
			$milestone['total_issues']      = $milestone['open_issues'] + $milestone['closed_issues'];
			$milestone['percent']           = percent($milestone['closed_issues'], $milestone['total_issues']);
			$milestone['open_issue_list']   = $this->github->api('issue')->all($githubUser, $repo, array('milestone' => $milestone['number']));
			$milestone['closed_issue_list'] = $this->github->api('issue')->all($githubUser, $repo, array('milestone' => $milestone['number'], 'state' => 'closed'));
			$milestones[$key]          = $milestone;
		}

		$this->setViewData('milestones', $milestones);
		$this->setViewData('githubUser', $githubUser);
		$this->setViewData('repo', $repo);
	}
}