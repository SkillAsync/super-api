<?php

namespace App\Models;



class Freelancer extends BaseModel
{
    protected $fillable = [
        'uuid',
        'user_id',
        'description',
        'nif',
        'service_id',
        'status',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
