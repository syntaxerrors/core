<?php namespace Syntax\Core\Control;

class Stacktrace {

	public $frames;

	public function __construct($file = null, $line = null, $stacktrace = null)
	{
		if (is_null($stacktrace)) {
			$stacktrace = debug_backtrace();
		}

		$lastFrame = array_shift($stacktrace);

		if (is_null($file) && is_null($line)) {
			$file = $lastFrame['file'];
			$line = $lastFrame['line'];
		}

		foreach ($stacktrace as $line) {
			$this->frames[] = $this->buildFrame($file, $line, $line['function']);

			if (isset($line['file']) && isset($line['line'])) {
				$file = $line['file'];
				$line = $line['line'];
			} else {
				$file = "[internal]";
				$line = 0;
			}
		}

		$this->frames[] = $this->buildFrame($file, $line, '[main]');
	}

	public function toArray()
	{
		return $this->frames;
	}

	private function buildFrame($file, $line, $method)
	{
		// Construct and return the frame
		return array(
			'file' => $file,
			'lineNumber' => $line,
			'method' => $method,
		);
	}
}