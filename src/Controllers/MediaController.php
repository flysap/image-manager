<?php

namespace Flysap\Media\Controllers;

use App\Http\Controllers\Controller;
use Flysap\Media\Media;
use Parfumix\TableManager;

class MediaController extends Controller {

    /**
     * Lists all images .
     *
     * @return \Illuminate\View\View
     */
    public function lists() {

        $table = TableManager\table((new Media), 'eloquent', ['class' => 'table table-hover']);

        return view('themes::pages.table', [
            'title' => _('Images'),
            'table' => $table
        ]);
    }

    public function update() {

    }

    public function remove() {

    }

    public function edit() {

    }
}