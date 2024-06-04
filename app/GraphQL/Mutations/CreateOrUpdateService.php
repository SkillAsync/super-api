<?php 
namespace App\GraphQL\Mutations;

use App\GraphQL\Mutations\TraitMutation\ModelosTrai;
use App\GraphQL\Traits\EntityMutationTrait;
use Illuminate\Support\Str;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateOrUpdateService
{
    use EntityMutationTrait, ModelosTrai;

    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $operationName = $resolveInfo->path[0] ?? $resolveInfo->operation->name->value;
        $modelName = $this->getModelNameFromOperation($resolveInfo->fieldName);
        $arguments = $args['input'] ?? $args;

        if (isset($arguments['model_uuid']) && isset($arguments['model_type'])) {
            $arguments['model_id'] = $this->getMorphIdFromUuid($arguments['model_type'], $arguments['model_uuid']);
          
        }

        $this->verifyUserPermissions($arguments, $args , $modelName);

        switch (true) {
            case Str::startsWith(Str::lower($operationName), 'create'):
                return $this->handleCreate($modelName, $arguments);
            case Str::startsWith(Str::lower($operationName), 'update'):
                return $this->handleUpdate($modelName, $arguments, $args['uuid']);
            case Str::startsWith(Str::lower($operationName), 'delete'):
                return $this->handleDelete($modelName, $args['uuid']);
            default:
                throw new \Exception('Operación no soportada');
        }
    }

    private function verifyUserPermissions($arguments , $args , $modelName)
    {
        if(isset($args["uuid"])){
            $model=$modelName::where('uuid', $args["uuid"])->firstOrFail();
        }
        $user = auth()->user();
        $uuid = isset($arguments["user"]["connect"]) ? $arguments["user"]["connect"] : $model->user->uuid;
        if (!$user) {
            throw new \Exception('Hay que estar autenticado para realizar esta operación');
        }
        if (!$user->hasRole('freelancer')) {
            throw new \Exception('Solo los freelancers pueden realizar esta operación');
        }
        if ($user->uuid != $uuid) {
            throw new \Exception('Solo puedes realizar esta operación para ti mismo');
        }
    }

    private function handleCreate($modelName, $arguments)
    {
        return DB::transaction(function () use ($modelName, $arguments) {
            $model = new $modelName();
            $model->fill($arguments);
            try {
                $model->save();
                $this->addRelations($arguments, $model);
            } catch (\Exception $e) {
                $this->addRelations($arguments, $model);
                $model->save();
            }
            return $model;
        });
    }

    private function handleUpdate($modelName, $arguments, $uuid)
    {
        $model = $modelName::where('uuid', $uuid)->firstOrFail();
        $model->fill($arguments);
        $model->save();
        return $model;
    }

    private function handleDelete($modelName, $uuid)
    {
        $model = $modelName::where('uuid', $uuid)->firstOrFail();
        $model->delete();
        return $model;
    }
}
