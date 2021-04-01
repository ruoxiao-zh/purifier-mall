<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $hidden = ['deleted_at'];

    protected $appends = [
        'created_at_diff_for_humans',
        'updated_at_diff_for_humans',
    ];

    public function getCreatedAtDiffForHumansAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : null;
    }

    public function getUpdatedAtDiffForHumansAttribute()
    {
        return $this->updated_at ? $this->updated_at->diffForHumans() : null;
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort', 'desc');
    }
}
