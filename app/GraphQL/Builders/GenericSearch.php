<?php

namespace App\GraphQL\Builders;

use Illuminate\Database\Eloquent\Builder;

class GenericSearch
{
    public function searchServiceByTitleOrDescription(Builder $builder, $args): Builder
    {

        if ($args) {
            $builder->where('title', 'like', "%$args%")
                ->orWhere('description', 'like', "%$args%");
        }

        return $builder;
    }
}