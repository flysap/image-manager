<?php

namespace Flysap\Media\Controllers;

use App\Http\Controllers\Controller;
use Flysap\Media\Media;
use Parfumix\TableManager;
use Parfumix\FormBuilder;

class MediaController extends Controller {

    protected $repository;

    public function __construct() {
        $this->repository = (new Media);
    }

    /**
     * Lists all images .
     *
     * @return \Illuminate\View\View
     */
    public function lists() {
        $table = TableManager\table($this->repository, 'eloquent', ['class' => 'table table-hover']);

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
            'addRoute' => route('media_create'),
            'table' => $table
        ]);
    }

    /**
     * Adding create logic business .
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function create() {
        if(! $_POST) {
            $form = FormBuilder\create_form([
                'action' => route('media_create'),
                'method' => FormBuilder\Form::METHOD_POST
            ]);

            $elements[] = FormBuilder\element_text('slug', [
                'name'  => 'slug',
                'group' => 'default'
            ]);

            $form->addElements($elements);

            return view('scaffold::scaffold.edit', compact('form'));
        }

        $mediaRow = $this->repository
            ->create($_POST);

        return redirect(
            route('media_edit', ['id' => $mediaRow->id])
        );

    }

    /**
     * Remove by id .
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        if( $media = $this->repository->where('id', $id) )
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