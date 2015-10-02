<?php

namespace Flysap\Media;

use Cartalyst\Tags\TaggableInterface;
use Cartalyst\Tags\TaggableTrait;
use Illuminate\Database\Eloquent\Model;

class Media extends Model implements TaggableInterface {

    use TaggableTrait;

    public $table = 'media';

    public $timestamps = true;

    public $fillable = ['id', 'title', 'description', 'path', 'full_path', 'active'];

    /**
     * Active scope .
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query) {
        return $query->whereActive(true);
    }

    /**
     * Inactive scope
     *
     * @param $query
     * @return mixed
     */
    public function scopeInactive($query) {
        return $query->whereActive(false);
    }
}