<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service extends BaseModel
{
    
    protected $fillable = [
        'uuid',
        'user_id',
        'category_id',
        'title',
        'description',
        'image',
        'price',
        'status',
        'created_by_id',
        'updated_by_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
