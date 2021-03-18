<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Slideshow extends BaseModel
{
    use SoftDeletes;

    public function getAll()
    {
        return self::orderBy('sort', 'desc');
    }
}
