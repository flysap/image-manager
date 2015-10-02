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

        $table->addColumn(['closure' => function($value, $attributes) {
            $elements = $attributes['elements'];

            $edit_route = route('media_edit', ['id' => $elements['id']]);
            $delete_route = route('media_delete', ['id' => $elements['id']]);

            return <<<DOC
<a href="$edit_route">Edit</a><br />
<a href="$delete_route">Delete</a><br />
DOC;
            ;
        }], 'action');

        return view('themes::pages.table', [
            'title' => _('Images'),
            'table' => $table
        ]);
    }

    /**
     * Update by id .
     *
     * @param $id
     */
    public function update($id) {

    }

    /**
     * Remove by id .
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        if( $media = Media::where('id', $id) )
            $media->delete();

        return redirect()
            ->back();
    }

    /**
     * Edit by id .
     *
     * @param $id
     */
    public function edit($id) {

    }
}