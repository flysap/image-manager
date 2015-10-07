<?php

namespace Flysap\Media\Controllers;

use App\Http\Controllers\Controller;
use Cartalyst\Tags\TaggableInterface;
use Eloquent\Translatable\Translatable;
use Flysap\Media\Media;
use Parfumix\TableManager;
use Parfumix\FormBuilder;
use Localization as Locale;
use Illuminate\Http\Request;
use FLysap\Support;

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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws FormBuilder\ElementException
     */
    public function create(Request $request) {
        if(! $_POST) {
            $form = FormBuilder\create_form([
                'action' => route('media_create'),
                'enctype' => FormBuilder\Form::ENCTYPE_MULTIPART,
                'method' => FormBuilder\Form::METHOD_POST
            ]);


            $elements[] = FormBuilder\element_file('', [
                'before' => '<div class="btn btn-default btn-file"><i class="fa fa-paperclip"></i> Attachment',
                'after'  => '</div>',
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

            if( $this->repository instanceof TaggableInterface ) {
                $addTag = FormBuilder\element_text(_('New tags'), [
                    'name'  => 'tags[]',
                    'group' => 'tags'
                ]);

                $elements[] = $addTag;
            }

            $form->addElements($elements, true);

            return view('scaffold::scaffold.edit', compact('form'));
        }

        $images = $this->upload(
            $request->file('file')
        );

        $mediaRow = $this->repository
            ->create($_POST);

        array_walk($images, function($image) use($mediaRow) {
            $mediaRow
                ->fill([
                    'path' => '/'. config('media-manager.path', '') . DIRECTORY_SEPARATOR . $image->basename,
                    'full_path' => public_path(config('media-manager.path', '') . DIRECTORY_SEPARATOR . $image->basename),
                ])->save();
        });

        if( $mediaRow instanceof TaggableInterface )
            if( isset($params['tags']) )
                $mediaRow->setTags($params['tags']);

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
        if( $media = $this->repository->where('id', $id)->first() ) {
            if( Support\is_path_exists( $media->getPresenter()->fullPath() ) )
                Support\remove_paths( $media->getPresenter()->fullPath() );

            $media->delete();
        }

        return redirect()
            ->back();
    }

    /**
     * Edit by id .
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     * @throws FormBuilder\ElementException
     */
    public function edit(Request $request, $id) {
        $mediaRow = $this->repository
            ->find($id);

        if(! $_POST) {
            $form = FormBuilder\create_form([
                'action' => route('media_edit', ['id' => $id]),
                'enctype' => FormBuilder\Form::ENCTYPE_MULTIPART,
                'method' => FormBuilder\Form::METHOD_POST
            ]);

            $elements[] = FormBuilder\element_image('image', [
                'name'  => 'image',
                'group' => 'default',
                'src' => $mediaRow->getPresenter()->url(),
                'width' => '500px'
            ]);

            $elements[] = FormBuilder\element_file('', [
                'before' => '<div class="btn btn-default btn-file"><i class="fa fa-paperclip"></i> Attachment',
                'after'  => '</div>',
                'name'  => 'file',
                'group' => 'default'
            ]);

            $elements[] = FormBuilder\element_hidden('', [
                'value' => 0,
                'name'  => 'active',
                'group' => 'default'
            ]);

            $elements[] = FormBuilder\element_checkbox('active', [
                'name'  => 'active',
                'group' => 'default',
                'value' => $mediaRow->active
            ]);

            $elements[] = FormBuilder\element_link('Crop', [
                'group' => 'default',
                'href' => route('media_crop', ['id' => $id]),
                'title' => 'Crop image',
            ]);

            if( $mediaRow instanceof Translatable ) {
                $locales = Locale\get_locales();

                foreach($locales as $locale => $attributes) {
                    $translation = $mediaRow->translate($locale);

                    foreach($this->repository->translatedAttributes() as $attribute => $type) {
                        $elements[]  = FormBuilder\get_element($type, [
                            'group' => 'translations',
                            'label' => ucfirst($attribute) . ' ' . $locale,
                            'name'  => $locale . '['.$attribute.']',
                            'value' => isset($translation[$attribute]) ? $translation[$attribute] : '',
                        ]);
                    }
                }
            }

            if( $mediaRow instanceof TaggableInterface ) {
                $tags = $mediaRow->tags;

                foreach($tags as $tag)
                    $elements[]  = FormBuilder\get_element('text', [
                        'before' => '<a href="#" onclick="$(this).closest(\'div\').remove(); return false;">'._('Remove').'</a>',
                        'name'   => 'tags[]',
                        'group'  => 'tags',
                        'value'  => $tag->getAttribute('name'),
                    ]);

                $addTag = FormBuilder\element_text(_('New tags'), [
                    'name'  => 'tags[]',
                    'group' => 'tags'
                ]);

                $elements[] = $addTag;
            }

            $form->addElements($elements, true);

            return view('scaffold::scaffold.edit', compact('form'));
        }

        $mediaRow
            ->fill($_POST)
            ->save();

        if( $request->hasFile('file') ) {
            if( Support\is_path_exists( $mediaRow->getPresenter()->fullPath() ) )
                Support\remove_paths( $mediaRow->getPresenter()->fullPath() );

            $images = $this->upload(
                $request->file('file')
            );

            array_walk($images, function($image) use($mediaRow) {
                $mediaRow
                    ->fill([
                        'path' => '/'. config('media-manager.path', '') . DIRECTORY_SEPARATOR . $image->basename,
                        'full_path' => public_path(config('media-manager.path', '') . DIRECTORY_SEPARATOR . $image->basename),
                    ])->save();
            });
        }

        if( $mediaRow instanceof TaggableInterface )
            if( isset($_POST['tags']) )
                $mediaRow->setTags($_POST['tags']);

        return back();
    }

    /**
     * Crop image
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function crop(Request $request, $id) {
        $mediaRow = $this->repository
            ->find($id);

        if(! $request->isMethod('POST')) {
            $image = FormBuilder\element_image('image', [
                'group' => 'default',
                'src' => $mediaRow->getPresenter()->url(),
                'width' => '700px'
            ]);

            return view('scaffold::crop', compact('image'));
        }

        $processor = $this->getProcessor();

        $processor->filter(
            $mediaRow->getPresenter()->fullPath(),
            [
                'crop' => $request->all(),
                'rotate' => $request->all()
            ]
        );

        return back();
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
            $path = config('media-manager.path', '');

        $path = public_path($path);

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