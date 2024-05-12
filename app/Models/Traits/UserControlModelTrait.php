<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait UserControlModelTrait
{
    public static function bootUserControlModelTrait()
    {
        static::creating(function ($model) {
            if ($model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'created_by_id')) {
                try {
                    if (Auth::user() !== null) {
                        $model->created_by_id = Auth::user()->id;
                        $model->updated_by_id = Auth::user()->id;
                    }
                } catch (\Exception $e) {
                }
            }
        });

        static::updating(function ($model) {
            if ($model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'updated_by_id')) {
                try {
                    if (Auth::user() !== null) {
                        $model->updated_by_id = Auth::user()->id;
                    }
                } catch (\Exception $e) {
                }
            }
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
