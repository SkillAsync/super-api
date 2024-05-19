<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}
