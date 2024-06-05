<?php

namespace App\Models;



class Freelancer extends BaseModel
{
    protected $fillable = [
        'uuid',
        'user_id',
        'description',
        'service_id',
        'specialty',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Category::class);
    }
}
