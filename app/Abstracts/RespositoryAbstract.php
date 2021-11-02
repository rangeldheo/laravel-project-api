<?php

namespace App\Abstracts;

use App\Interfaces\RespositoryInterface;
use App\Repository\RepositorySincronizeRelations;
use App\Services\SearchServices;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;


abstract class RespositoryAbstract implements RespositoryInterface
{
    /**
     * Retorna todas as registros do modelo paginados
     * com suporte a queryParams para filtrar os registros
     * @param ValidatesWhenResolved $request
     * @param Model $model
     * @return Paginator
     */
    public static function getAllPaginate(
        ValidatesWhenResolved $request,
        string $model
    ): Paginator {
        return $model::search($request);
    }

    /**
     * Lista todos os resultados paginados
     * @param [Nome da Model] $model
     * @return LengthAwarePaginator
     */
    public static function index($model): LengthAwarePaginator
    {
        return $model::paginate();
    }

    /**
     * Exibie um registro, trazendo inclusive
     * os relacionamentos caso seja solicitados na request
     * Entidades separadas por [,], e/ou aninhadas por [.]
     * ?relations=entidade1,endidade2.endtidade3
     * @param [instancia da class] $object
     * @return Model
     */
    public static function show($object): Model
    {
        $relations = SearchServices::getRelations();
        return $object->with($relations)->find($object->id);
    }

    /**
     * Cadastra um registro
     * @param string [Nome da Model] $model
     * @param Request [Request] $request
     * @return Model
     */
    public static function store($model, $request): Model
    {
        $object = $model::create($request->all());
        //procura por relacionamentos pivot
        if (!$object->syncModels) {
            return self::show($object);
        }
        //se tiver percorre os relacionamentos fazendo os syncs
        foreach ($object->syncModels as $relationsModels) {
            if ($request->filled($relationsModels)) {
                $object->$relationsModels()
                    ->sync($request->get($relationsModels));
            }
        }

        return self::show($object);
    }

    /**
     * Atualiza um registro
     * @param object [object instacia da model] $model
     * @param Request [Request] $request
     * @return bool
     */
    public static function update($model, $request): bool
    {
        $model->fill($request->all());

        if (!$model->syncModels) {
            return  $model->update();
        }
        $model = RepositorySincronizeRelations::syncronize($model, $request);
        return  $model->update();
    }

    /**
     * Excluí um registro
     * @param [Instancia da Model] $object
     * @return boolean
     */
    public static function destroy($object): bool
    {
        return  $object->delete();
    }
}