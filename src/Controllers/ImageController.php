<?php

namespace Flysap\ImageManager\Controllers;

use App\Http\Controllers\Controller;
use Eloquent\ImageAble\Attachment;
use Flysap\TableManager;

class ImageController extends Controller {

    /**
     * Lists all images .
     *
     * @return \Illuminate\View\View
     */
    public function lists() {

        $table = TableManager\table((new Attachment), 'eloquent', ['class' => 'table table-hover']);

        return view('themes::pages.table', [
            'title' => _('Images'),
            'table' => $table
        ]);
    }
}