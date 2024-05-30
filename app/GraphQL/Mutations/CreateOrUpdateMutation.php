<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLException;
use App\GraphQL\Mutations\TraitMutation\ModelosTrai;
use App\GraphQL\Traits\EntityMutationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateOrUpdateMutation
{
    use EntityMutationTrait,ModelosTrai;

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
            if ($args['uuid'] === auth()->user()->uuid) {
                $model->update($arguments);
                $this->addRelations($arguments, $model);
            } else {
                throw new GraphQLException(__('errors.unauthorized'), 'unauthorized');
            }
        }elseif (Str::startsWith(Str::lower($operationName), 'delete')) {

            $model = $modelName::where('uuid', $args['uuid'])->firstOrFail();
            
            if ($args['uuid'] === auth()->user()->uuid) {
                $model->delete();
            } else {
                throw new GraphQLException(__('errors.unauthorized'), 'unauthorized');
            }
        }

        return $model;
    }
}
