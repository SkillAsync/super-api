<?php

namespace App\GraphQL\Traits;

use App\Exceptions\GraphQLException;
use Illuminate\Support\Str;

trait EntityMutationTrait
{
    protected function addRelations(array $args, $model): void
    {
        foreach ($args as $index => $arg) {
            if (is_array($arg) && array_key_exists('connect', $arg) && $arg['connect'] === null) {
                $this->removeRelation($model, $index);
            } elseif (is_array($arg) && (isset($arg['connect']) || isset($arg['create']) || isset($arg['update']))) {
                $this->addRelationByUuid($arg, $model, $index);
            }
        }
    }

    protected function removeRelation($model, $method): void
    {
        $model->{$method.'_id'} = null;
        $model->update();
    }

    protected function addRelationByUuid($argument, $model, $method, $realMethod = null): void
    {
        $argumentConnect = $argument['connect'] ?? null;
        $argumentCreate = $argument['create'] ?? null;
        $argumentUpdate = $argument['update'] ?? null;

        if ($realMethod === null) {
            $realMethod = $method;
        }

        $relatedModel = $this->getModelClass($realMethod);

        if ($argumentConnect) {
            if (is_array($argumentConnect)) {
                $model->{$method}()->detach();
                $relatedModel = get_class($model->{$method}()->getRelated());
                foreach ($argumentConnect as $value) {
                    if (!is_array($value)) {
                        $entity = $relatedModel::where('uuid', $value)->first();
                        $model->{$method}()->attach($entity->id);
                    }
                }
            } else {
                $model->{$method.'_id'} = $relatedModel::where('uuid', $argumentConnect)->first()?->id;
                $model->update();
            }
        }

        if ($argumentCreate) {
            $entity = $this->createNewEntity($realMethod, $argumentCreate);

            if ($entity) {
                $model->{$method.'_id'} = $entity->id;
                $model->update();
            }
        }

        if ($argumentUpdate) {
            $entity = $relatedModel::find($model->{$method.'_id'});
            $this->updateExistingEntity($realMethod, $argumentUpdate, $entity);
        }
    }

    protected function getModelClass($method)
    {
        $modelName = ucfirst(Str::camel($method));
        $modelClass = "App\\Models\\{$modelName}";

        return class_exists($modelClass) ? $modelClass : null;
    }

    protected function createNewEntity($method, $data)
    {
        $modelClass = $this->getModelClass($method);

        if (!$modelClass) {
            throw new GraphQLException(__('errors.class_not_found', $method), 'class_not_found');
        }

        $entity = new $modelClass;
        $entity->fill($data);
        $this->addRelations($data, $entity);
        $entity->save();

        return $entity;
    }

    protected function updateExistingEntity($method, $data, $entity = null)
    {
        $modelClass = $this->getModelClass($method);

        if (!$modelClass) {
            throw new GraphQLException(__('errors.class_for_method_not_found', $modelClass), 'class_not_found');
        }

        if ($entity === null) {
            $identifier = $data['uuid'] ?? null;

            if (!$identifier) {
                throw new GraphQLException(__('errors.identifier_not_found', $identifier), 'identifier_not_found');
            }

            $entity = $modelClass::where('uuid', $identifier)->first();
        }

        if (!$entity) {
            throw new GraphQLException(__('errors.identifier_not_found', $identifier), 'identifier_not_found');
        }

        $entity->update($data);

       
    }

    protected function getMorphIdFromUuid($morphType, $morphUuid)
    {
        $modelClass = "App\\Models\\{$morphType}";
        if (!class_exists($modelClass)) {
            throw new GraphQLException(__('errors.morph_not_exists', ['relation' => $morphType]), 'morph_not_exists');
        }

        $billableModel = new $modelClass;

        $billable = $billableModel->where('uuid', $morphUuid)->first();

        return $billable ? $billable->id : null;
    }
}
