<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLException;
use App\GraphQL\Traits\EntityMutationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateOrUpdateMutation
{
    use EntityMutationTrait;

    public function __invoke($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $operationName = $resolveInfo->path[0] ?? $resolveInfo->operation->name->value;
        $modelName = $this->getModelNameFromOperation($operationName);
        $model = null;
        $arguments = $args['input'] ?? $args;

        if (isset($arguments['model_uuid']) && isset($arguments['model_type'])) {
            $arguments['model_id'] = $this->getMorphIdFromUuid($arguments['model_type'], $arguments['model_uuid']);
        }

        if (Str::startsWith(Str::lower($operationName), 'create')) {
            DB::transaction(function () use ($modelName, $arguments, &$model) {
                $model = new $modelName;
                $model->fill($arguments);
                try {
                    $model->save();
                    $this->addRelations($arguments, $model);
                } catch (\Exception $e) {
                    $this->addRelations($arguments, $model);
                    $model->save();
                }
            });
        } elseif (Str::startsWith(Str::lower($operationName), 'update')) {
            $model = $modelName::where('uuid', $args['uuid'])->firstOrFail();
            $model->update($arguments);
            $this->addRelations($arguments, $model);
        }

        return $model;
    }

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
        $pattern = '/^(create|update)/i';
        $replacement = '';
        return preg_replace($pattern, $replacement, $string, 1);
    }
}
