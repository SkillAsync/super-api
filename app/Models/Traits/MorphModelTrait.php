<?php

namespace App\Models\Traits;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait MorphModelTrait
{
    public function model(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    protected function modelType(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value,
            set: fn ($value) => 'App\Models\\'.ucfirst(str_replace('App\Models\\', '', $value)),
        );
    }

    public static function getModelIdFromTypeAndUuid(string $modelType, string $modelUuid): ?int
    {
        if (!class_exists($modelType)) {
            return null;
        }

        $modelInstance = new $modelType;

        if (!$modelInstance instanceof BaseModel) {
            return null;
        }

        $model = $modelInstance->where('uuid', $modelUuid)->first();

        return $model?->id;
    }
}
