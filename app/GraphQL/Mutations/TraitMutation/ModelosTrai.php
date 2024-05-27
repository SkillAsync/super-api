<?php

namespace App\GraphQL\Mutations\TraitMutation;

use App\Exceptions\GraphQLException;
use Illuminate\Support\Str;

trait ModelosTrai
{
  

     protected function getModelNameFromOperation($operationName): string
    {
        
        $modelNamePart = $this->replaceFirstOccurrence($operationName);
        $modelName = 'App\\Models\\'.Str::studly($modelNamePart);

        if (!class_exists($modelName)) {
            throw new GraphQLException(__('errors.model_not_found', ['model' => $modelName]), 'model_not_found');
        }

        return $modelName;
    }

     private function replaceFirstOccurrence($string): string
    {
        $pattern = '/^(create|update|delete)/i';
        $replacement = '';
        return preg_replace($pattern, $replacement, $string, 1);
    }
}
