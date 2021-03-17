<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Slideshow extends BaseModel
{
    use SoftDeletes;

    public function getAll()
    {
        return self::orderBy('sort', 'desc');
    }
}
