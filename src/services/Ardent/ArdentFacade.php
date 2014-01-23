<?php namespace Syntax\Core\Ardent\Facades;

/*
 * This file is part of the Ardent package.
 *
 * (c) Max Ehsan <contact@Core.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Facade;

class Ardent extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'ardent'; }

}