<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLException;
use App\GraphQL\Mutations\TraitMutation\ModelosTrai;
use App\GraphQL\Traits\EntityMutationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Role;


class CreateOrUpdateMutation
{
    use EntityMutationTrait, ModelosTrai;

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
                if ($modelName === "App\Models\Freelancer") {
                    $user = auth()->user(); // Obtén el usuario autenticado
                    $role = Role::where('name', 'freelancer')->first(); // Obtén el rol de freelancer
                    if ($user && $role) {
                        $user->assignRole($role);
                    }
                } elseif ($modelName === "App\Models\User") {
                    $role = Role::where('name', 'user')->first(); // Obtén el rol de usuario
                    if ($role) {
                        $model->assignRole($role);
                    }
                }
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
                throw new GraphQLException(__('no esta autorizado'), 'no esta autorizado');
            }
        } elseif (Str::startsWith(Str::lower($operationName), 'delete')) {

            $model = $modelName::where('uuid', $args['uuid'])->firstOrFail();

            if ($args['uuid'] === auth()->user()->uuid) {
                $model->delete();
            } else {
                throw new GraphQLException(__('errors.unauthorized'), 'no esta autorizado');
            }
        }

        return $model;
    }
}
