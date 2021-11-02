<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe responsável por sincronizar os relacionamentos
 * entre os modelos
 */
class RepositorySincronizeRelations
{
    /**
     * Sincroniza os registros de relacionamento do modelo
     * @param Model $model
     * @param FormRequest $request
     * @return [type]
     */
    public static function syncronize(Model $model, $request)
    {
        $haveRelationshipToSynchronize = $request->filled('sync');

        if (!self::hasRelationship($request)) {
            return $model;
        }

        foreach ($model->syncModels as $relationsModels) {

            $removeRelationsAndSyncronize =
                $request->filled($relationsModels) && $haveRelationshipToSynchronize;

            if ($removeRelationsAndSyncronize) {
                $model->$relationsModels()
                    ->sync($request->get($relationsModels));
            }

            $justSyncronize =
                $request->filled($relationsModels) && !$haveRelationshipToSynchronize;

            if ($justSyncronize) {
                $model->$relationsModels()
                    ->syncWithoutDetaching($request->get($relationsModels));
            }
        }

        return $model;
    }

    /**
     * Vrifica se existe uma relacionamento na request
     * @param FormRequest $request
     *
     * @return bool
     */
    private static function hasRelationship(FormRequest $request): bool
    {
        if (!$request) {
            return false;
        }

        if (!$request->sync) {
            return false;
        }
        return true;
    }
}