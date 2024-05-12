<?php

namespace App\Models;

use App\Models\Traits\UserControlModelTrait;
use App\Models\Traits\UuidModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use UuidModelTrait;
    use UserControlModelTrait;
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
