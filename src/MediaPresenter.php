<?php

namespace Flysap\Media;

use Robbo\Presenter\Presenter;

class MediaPresenter extends Presenter {

    /**
     * Get path .
     *
     * @return mixed
     */
    public function url() {
        return $this->path;
    }
}