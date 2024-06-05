<?php

namespace App\Models;



class Freelancer extends BaseModel
{
    protected $fillable = [
        'uuid',
        'user_id',
        'description',
        'service_id',
        'category_id',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
