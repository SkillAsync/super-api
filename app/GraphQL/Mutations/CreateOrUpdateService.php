<?php 
namespace App\GraphQL\Mutations;

use App\GraphQL\Mutations\TraitMutation\ModelosTrai;
use App\GraphQL\Traits\EntityMutationTrait;
use Illuminate\Support\Str;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Spatie\Permission\Models\Role;

class CreateOrUpdateService
{
    use EntityMutationTrait, ModelosTrai;
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
         $operationName = $resolveInfo->path[0] ?? $resolveInfo->operation->name->value;

      
        $modelName = $this->getModelNameFromOperation($resolveInfo->fieldName);
        $model = new $modelName();
        $arguments = $args['input'] ?? $args;

        if (isset($arguments['model_uuid']) && isset($arguments['model_type'])) {
            $arguments['model_id'] = $this->getMorphIdFromUuid($arguments['model_type'], $arguments['model_uuid']);
        }
        if (Str::startsWith(Str::lower($operationName), 'create')) {
            DB::transaction(function () use ($modelName, $arguments, &$model) {

            $user = auth()->user(); 
            if (!$user) {
                throw new \Exception('Hay que estar autenticado para crear un servicio');
            } elseif (!$user->hasRole('freelancer')) {
                throw new \Exception('Solo los freelancers pueden crear servicios');
            } elseif ($user->uuid != $arguments["user"]["connect"]) {
                throw new \Exception('Solo puedes crear servicios para ti mismo');
            } else {
                $model->fill($arguments);
                try {
                    $model->save();
                    $this->addRelations($arguments, $model);
                } catch (\Exception $e) {
                    $this->addRelations($arguments, $model);
                    $model->save();
                }
            }
        });
        }

        

          return $model;
    }
}
