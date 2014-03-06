<?php namespace Syntax\Core\View;

use Illuminate\View\Environment as BaseEnvironment;
use Illuminate\View\View;
use File;

class Environment extends BaseEnvironment {

    /**
     * Get a evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View
     */
    public function make($view, $data = array(), $mergeData = array())
    {
        if (!$this->exists($view)) {
            $coreView  = 'core::'. $view;
            $chatView  = 'chat::'. $view;
            $forumView = 'forum::'. $view;

            if ($this->exists($coreView)) {
                $view = $coreView;
            } elseif ($this->exists($chatView)) {
                $view = $chatView;
            } elseif ($this->exists($forumView)) {
                $view = $forumView;
            }
        }

        $path = $this->finder->find($view);

        $data = array_merge($mergeData, $this->parseData($data));

        $newView = new View($this, $this->getEngineFromPath($path), $view, $path, $data);

        return $newView;
    }

}