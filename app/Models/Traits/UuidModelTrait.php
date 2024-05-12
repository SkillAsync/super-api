<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait UuidModelTrait
{
    /**
     * This function overwrites the default boot static method of Eloquent models. It will hook
     * the creation event with a simple closure to insert the UUID
     */
    public static function bootUuidModelTrait()
    {
        static::creating(function ($model) {

            if ($model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'uuid')) {
                // Only generate UUID if it wasn't set by already.

                $key = 'uuid';
                if (method_exists($model, 'getUuidField')) {
                    $key = $model->getUuidField();
                    if (empty($key)) {
                        $key = 'uuid';
                    }
                }

                if (! isset($model->attributes[$key])) {
                    $uuid = (string) Str::uuid();
                    $model->attributes[$key] = $uuid;
                }
            }
        }, 0);
    }
}
