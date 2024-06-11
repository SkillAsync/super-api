<?php

namespace App\Models;

use App\Models\Traits\MorphModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * class Review
 * 
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int $freelancer_id
 * @property int $service_id
 * @property int $rating
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 */
class Review extends BaseModel
{
    use MorphModelTrait;

    protected $table = 'reviews';

    protected $casts = [
        'user_id' => 'int',
        'rating' => 'int',
        'freelancer_id' => 'int',
        'service_id' => 'int',
    ];
    protected $fillable = [
        'uuid',
        'user_id',
        'freelancer_id',
        'rating',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }
}
