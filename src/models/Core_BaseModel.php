<?php

use Syntax\Core\Ardent\Ardent;

class Core_BaseModel extends Ardent {

    /********************************************************************
     * Core
     *******************************************************************/

    public $presenter = null;

     /**
     * Return a created presenter.
     *
     */
    public function __construct()
    {
        $class     = str_replace('Syntax\Core\\', '', get_called_class()) .'Presenter';
        $coreClass = 'Syntax\Core\\'. $class;

        if (class_exists($class)) {
            $this->presenter = $class;
        } elseif (class_exists($coreClass)) {
            $this->presenter = $coreClass;
        } else {
            $this->presenter = 'Syntax\Core\CorePresenter';
        }

        return parent::__construct();
    }

    /**
     * Make sure the uniqueId is always unique
     *
     * @return string
     */
    public static function findExistingReferences($model)
    {
        $invalid = true;
        while ($invalid == true) {
            $uniqueString = Str::random(10);

            $existingReferences = $model::where('uniqueId', '=', $uniqueString)->count();

            if ($existingReferences == 0) {
                $invalid = false;
            }
        }

        return $uniqueString;
    }

    /**
     * Use the custom collection that allows tapping
     *
     * @return Utility_Collection[]
     */
    public function newCollection(array $models = array())
    {
        return new Utility_Collection($models);
    }

    /********************************************************************
     * Scopes
     *******************************************************************/
    /**
     * Order by created_at ascending scope
     *
     * @param array $query The current query to append to
     */
    public function scopeOrderByCreatedAsc($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Order by name ascending scope
     *
     * @param array $query The current query to append to
     */
    public function scopeOrderByNameAsc($query)
    {
        return $query->orderBy('name', 'asc');
    }

    /**
     * Get only active rows
     *
     * @param array $query The current query to append to
     */
    public function scopeActive($query)
    {
        return $query->where('activeFlag', 1);
    }

    /**
     * Get only inactive rows
     *
     * @param array $query The current query to append to
     */
    public function scopeInactive($query)
    {
        return $query->where('activeFlag', 0);
    }

    /********************************************************************
     * Model events
     *******************************************************************/
    public static function boot()
    {
        parent::boot();
        $class        = get_called_class();
        $observer     = $class .'Observer';
        $coreObserver = 'Syntax\Core\\'. $observer;

        if (self::testClassForUniqueId($class) == true) {
            $class::creating(function($object) use ($class)
            {
                $object->uniqueId = parent::findExistingReferences($class);
            });
        }

        if (class_exists($observer)) {
            $class::observe(new $observer);
        } elseif (class_exists($coreObserver)) {
            $class::observe(new $coreObserver);
        }
    }

    /********************************************************************
     * Getters and Setters
     *******************************************************************

    /**
     * Allow id to be called
     *
     * @return int|string
     */
    public function getIdAttribute($value)
    {
        if (isset($this->uniqueId)) {
            return $this->uniqueId;
        }

        return $value;
    }

    /********************************************************************
     * Extra Methods
     *******************************************************************/

    public static function testClassForUniqueId($class)
    {
        $object = new $class;

        if ($object->primaryKey == 'uniqueId') {
            return true;
        }

        return false;
    }
}