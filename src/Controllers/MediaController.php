<?php

namespace Flysap\Media\Controllers;

use App\Http\Controllers\Controller;
use Parfumix\TableManager;

class ImageController extends Controller {

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
}