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

		// Check for existing record
		$existingError = \DB::connection('exceptions')->table('site_exceptions')->where('name', $this->name)->where('message', $this->message)->where('siteKeyName', $this->site)->first();

		if (count($existingError) == 0) {
			\DB::connection('exceptions')->table('site_exceptions')->insert(array(
				'name'        => $this->name,
				'message'     => $this->message,
				'file'        => $this->file,
				'line'        => $this->line,
				'siteKeyName' => $this->site,
				'userIp'      => $this->userIp,
				'fatal'       => $this->fatal,
				'userId'      => $this->userId,
				'username'    => $this->username,
				'count'       => 1,
				'created_at'  => $date,
				'updated_at'  => null
			));
		} elseif ($existingError->created_at != $date && $existingError->updated_at != $date) {
			\DB::connection('exceptions')->table('site_exceptions')->insert(array(
				'name'        => $this->name,
				'message'     => $this->message,
				'file'        => $this->file,
				'line'        => $this->line,
				'siteKeyName' => $this->site,
				'userIp'      => $this->userIp,
				'fatal'       => $this->fatal,
				'userId'      => $this->userId,
				'username'    => $this->username,
				'count'       => 1,
				'created_at'  => $date,
				'updated_at'  => null
			));
		}
	}
}