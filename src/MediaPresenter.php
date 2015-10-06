<?php

namespace Flysap\Media;

use Robbo\Presenter\Presenter;

class MediaPresenter extends Presenter {

    /**
     * Render image .
     *
     * @return string
     */
    public function image() {
        return '<img src="'.$this->path.'">';
    }

    /**
     * Get path .
     *
     * @return mixed
     */
    public function url() {
        return $this->path;
    }

    /**
     * Get full path .
     *
     * @return mixed
     */
    public function fullPath() {
        return $this->full_path;
    }
}