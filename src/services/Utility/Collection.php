<?php
/**
 * Dev-Toolbox Custom Utility Collection.
 *
 * Extra methods for the eloquent collection.
 *
 *
 * @author      RiDdLeS <riddles@dev-toolbox.com>
 * @version     0.1
 */

class Utility_Collection extends Illuminate\Database\Eloquent\Collection {

	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		$newCollection = new Utility_Collection();
		foreach ($this->items as $item) {
			if ($item instanceof Utility_Collection) {
				foreach ($item as $subItem) {
					$newCollection->put($newCollection->count(), $subItem->$key);
				}
			}
			elseif (is_object($item) && !$item instanceof Utility_Collection && $item->$key instanceof Utility_Collection) {
				foreach ($item->$key as $subItem) {
					$newCollection->put($newCollection->count(), $subItem);
				}
			}
			else {
				$newCollection->put($newCollection->count(), $item->$key);
			}
		}
		return $newCollection;
	}

	/**
	 * Allow a method to be run on the enitre collection.
	 *
	 * @param string $method
	 * @param array $args
	 * @return Utility_Collection
	 */
	public function __call($method, $args)
	{
		if ($this->count() <= 0) {
			return $this;
		}

		foreach ($this->items as $item) {
			if (!is_object($item)) {
				continue;
			}
			call_user_func_array(array($item, $method), $args);
		}

		return $this;
	}

	/**
	 * Insert into an object
	 *
	 * @param int 	$key
	 * @param mixed	$value
	 * @param int 	$afterKey
	 *
	 * @return Utility_Collection
	 */
	public function insertAfter($key, $value, $afterKey)
	{
		$new_object = new Utility_Collection();
		// unset($new_object->items);
		$afterFlag  = false;
		$afterValue = $key;

		foreach ((array) $this->items as $k => $v) {
			if ($afterKey == $k) {
				$new_object->add($value);
				$afterFlag        = true;
			}

			$new_object->add($v);
		}

		$this->items = $new_object->items;

		return $this;
	}

	/**
	 * Shitty first try at a where function for a collection
	 */
	public function where($column,/* $operator = null,*/ $value = null/*, $boolean = 'and'*/)
	{
		foreach ($this->items as $key => $item) {

			if (strstr($column, '->')) {
				$taps = explode('->', $column);

				$objectToSearch = $item;
				foreach ($taps as $tapKey => $tap) {
					// pp($objectToSearch);
					// break the foreach when we hit the last tap.
					// pp($tapKey);
					// if (count($taps) == $tapKey+1){
					// 	// die('we broke');
					// 	break;
					// }

					// Keep tapping till we hit the last object.
					$objectToSearch = $objectToSearch->$tap;
				}

				if ($objectToSearch instanceof Utility_Collection) {
					if (!in_array($value, $objectToSearch->toArray())) {
						$this->forget($key);
						// pp('column diff in sub tap forgot ' . $key);
						continue;
					}
				}
				else {
					if ($objectToSearch != $value) {
						$this->forget($key);
						// pp('column diff in sub tap forgot ' . $key);
						continue;
					}
				}
			} else {
				if (!$item->$column) {
					$this->forget($key);
					// pp('No column forgot ' . $key);
					continue;
				}

				if ($item->$column != $value) {
					$this->forget($key);
					// pp('column diff forgot ' . $key);
					continue;
				}
			}
		}

		return $this;
	}

	public function toSelectArray($firstOptionText = 'Select one', $id = 'id', $name = 'name')
	{
		$selectArray = array();

		if ($firstOptionText != false) {
			$selectArray[0] = $firstOptionText;
		}

		foreach ($this->items as $item) {
			$selectArray[$item->{$id}] = $item->{$name};
		}
		return $selectArray;
	}
}