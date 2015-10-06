<?php

namespace Flysap\Media;

use Illuminate\Database\Eloquent\Model;

class MediaTranslation extends Model  {

    public $table = 'media_translations';

    public $timestamps = false;

    protected $fillable = ['media_id', 'language_id', 'title', 'description'];

}