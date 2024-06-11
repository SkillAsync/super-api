<?php

namespace App\GraphQL\Builders;

use Illuminate\Database\Eloquent\Builder;

class GenericSearch
{
    public function searchServiceByTitleOrDescription(Builder $builder, $args): Builder
    {

        if (empty($args)) {
            //devolver vacio
            return $builder->where('title', 'like', "$args");
        }else{
            $builder->where('title', 'like', "%$args%")
                ->orWhere('description', 'like', "%$args%");
        }

        return $builder;
    }
}