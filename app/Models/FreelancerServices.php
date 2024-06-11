<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CompanyCustomer
 *
 * @property int $id
 * @property string $uuid
 * @property int $freelancer_id
 * @property int $service_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Service $service
 * @property Freelancer $freelancer
 * @package App\Models
 */
class FreelancerServices extends BaseModel
{
    protected $table = 'freelancer_services';

    protected $casts = [
		'freelancer_id' => 'int',
        'service_id' => 'int',
	];

  protected $fillable = [
    'uuid',
    'freelancer_id',
    'service_id',
  ];


    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }


    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }

    
}
