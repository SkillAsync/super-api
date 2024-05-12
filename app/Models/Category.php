<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{
    protected $fillable = [
        'uuid',
        'name',
        'slug',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
