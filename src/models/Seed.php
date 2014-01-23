<?php
namespace Syntax\Core;

use File;
use Session;
use Artisan;

class Seed extends \BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	/**
	 * Validation rules
	 *
	 * @static
	 * @var array $rules All rules this model must follow
	 */
	public static $rules = array(
		'name' => 'unique:seeds,name',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public function runSeed()
	{
		// Move the update file to local
		$updatePath   = app_path() .'/core/database/seeds/updates';
		$fullSeedName = $this->name .'.php';

		File::copy($updatePath .'/'. $fullSeedName, app_path() .'/database/seeds/'. $fullSeedName);
		exec('chmod 755 '. app_path() .'/database/seeds/'. $fullSeedName);

		// Load the new file
		exec('/usr/local/bin/php '. base_path() .'/artisan dump-autoload');

		// Run the seed
		exec('/usr/local/bin/php '. base_path() .'/artisan db:seed --class='. $this->name);
	}
}