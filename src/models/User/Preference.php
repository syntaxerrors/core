<?php
namespace Syntax\Core;

class User_Preference extends \BaseModel 
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'preferences';

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
		'name'    => 'required',
		'value'   => 'required',
		'default' => 'required',
		'display' => 'required',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/


	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'users' => array('belongsToMany', 'User', 'table' => 'preferences_users', 'foreignKey' => 'user_id', 'otherKey' => 'preference_id'),
	);

	/********************************************************************
	 * Model events
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	public function getPreferenceOptionsArray()
	{
		$preferenceOptions = explode('|', $this->value);
		$preferenceArray   = array();

		foreach ($preferenceOptions as $preferenceOption) {
			$preferenceArray[$preferenceOption] = ucwords($preferenceOption);
		}

		return $preferenceArray;
	}
}