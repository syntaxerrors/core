<?php namespace Syntax\Core\Control;

class CoreBug {

	public $name;

	public $message;

	public $file;

	public $line;

	public $userIp;

	public $userId = null;

	public $username = null;

	public $site;

	public $fatal = 0;

	public function notifyException($exception)
	{
		$this->site    = \Config::get('core::controlRoomDetail');
		$this->name    = get_class($exception);
		$this->message = $exception->getMessage();
		$this->file    = $exception->getFile();
		$this->line    = $exception->getLine();
		$this->userIp  = \Request::server('REMOTE_ADDR');

		if (stripos($this->message, 'undefined variable: mobile') !== false) {
			// Check core view for errors
			$coreViewErrors = \CoreView::getErrors();

			if (array_key_exists('noView', $coreViewErrors)) {
				// Use the view finder to get a proper view not found message
				$viewFinder = \View::getFinder();
				try {
					$viewFinder->find($coreViewErrors['noView']);
				} catch (\InvalidArgumentException $e) {
					$this->name    = 'InvalidArgumentException';
					$this->message = $e->getMessage();
					$this->file    = $e->getFile();
					$this->line    = $e->getLine();
				}
			}
		}

		if ($this->name == 'Symfony\Component\Debug\Exception\FatalErrorException') {
			$this->fatal = 1;
		}

		$activeUser = \CoreView::getActiveUser();

		if (!is_null($activeUser)) {
			$this->userId   = $activeUser->id;
			$this->username = $activeUser->username;
		}

		$date = date('Y-m-d H:i:s');

		$object = $this;

		// Send the error to core
		\Queue::push(function($job) use ($object) {
			$response = \cURL::newRequest('post', 'http://control.stygianvault.com/siteExceptions/'. $object->site, ['post' => (array)$object])
			->setHeader('User-Agent', 'Control')
			->send();

			$job->delete();
		});
	}
}