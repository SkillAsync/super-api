<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Summary of Category
 * 
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * 
 * @property \Illuminate\Database\Eloquent\Collection $services
 */
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
