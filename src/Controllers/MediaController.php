<?php

namespace Flysap\Media\Controllers;

use App\Http\Controllers\Controller;
use Eloquent\Translatable\Translatable;
use Flysap\Media\Media;
use Parfumix\TableManager;
use Parfumix\FormBuilder;
use Localization as Locale;
use Illuminate\Http\Request;

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
    public function create(Request $request) {
        if(! $_POST) {
            $form = FormBuilder\create_form([
                'action' => route('media_create'),
                'enctype' => FormBuilder\Form::ENCTYPE_MULTIPART,
                'method' => FormBuilder\Form::METHOD_POST
            ]);

            $elements[] = FormBuilder\element_file('file', [
                'name'  => 'file',
                'group' => 'default'
            ]);

            $elements[] = FormBuilder\element_checkbox('active', [
                'name'  => 'active',
                'group' => 'default'
            ]);

            if( $this->repository instanceof Translatable ) {
                $locales = Locale\get_locales();

                foreach($locales as $locale => $attributes) {
                    foreach($this->repository->translatedAttributes() as $attribute => $type) {
                        $elements[]  = FormBuilder\get_element($type, [
                            'group' => 'translations',
                            'label' => ucfirst($attribute) . ' ' . $locale,
                            'name'  => $locale . '['.$attribute.']',
                        ]);
                    }
                }
            }

            $form->addElements($elements, true);

            return view('scaffold::scaffold.edit', compact('form'));
        }

        $images = $this->upload(
            $request->file('file')
        );

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

    /**
     * Upload images .
     *
     * @param array $images
     * @param null $path
     * @param array $filters
     * @return array
     */
    protected function upload($images = array(), $path = null, $filters = array()) {
        if(! is_array($images))
            $images = [$images];

        $processor = $this->getProcessor();

        if( is_null($path) )
            $path = config('media-manager.store_path', '');

        $path = storage_path($path);

        $filters = array_merge($filters, config('media-manager.filters'));

        return $processor->upload(
            $images, $path, $filters
        );
    }

    /**
     * Get image processor .
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    protected function getProcessor() {
        return app('image-processor');
    }
}