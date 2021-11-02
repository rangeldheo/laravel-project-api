<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Esse serviço realiza a criação de uma query avançada e automatizada
 * para alimentar as listagens do sistema
 * ela contatena os parametros de busca como
 * LIKES, EQUALS usando [?chave=valor], ORDER BY,
 * RELACIONAMENTOS de modelos, páginação e
 * numerdo de registros por pagina.
 */
class SearchServices
{
    /**
     * String do comnando [LIKE] Mysql
     */
    const LIKE = 'LIKE';
    /**
     * String do comnando [=] Mysql
     */
    const EQUAL = '=';

    /**
     * campo na request que contem a chave=valor do orderBy
     */
    const ORDER_BY = 'order_by';


    /**
     * Constante que envolve o tipo primitivo referente
     * a query params da url para relacionamentos
     * http://url.com?relations=nome_relacionamento
     */
    const RELATION_SHIP = 'relations';

    /**
     * String que controla o número de resultados por pagina
     * na páginação
     */
    const RESULTS = 'results';

    public static $fieldForOrderBy = null;
    public static $orderbyDirection = 'ASC';

    /**
     * Concatena as tags da busca usando a diretiva LIKE do mysql
     *
     * @param string $term
     * @return void
     */
    public static function like(string $term)
    {
        return '%' . $term . '%';
    }

    /**
     * Cria uma query avançada automatizada de busca para
     * alimentar todas as listagens do sistema
     *
     * @param FormRequest $request
     * @param Builder $query
     * @param array $allowedFieldsForEqual
     * @param array $allowedFieldsForLike
     * @param array $withModels
     * @return Paginator
     */
    public static function search(
        FormRequest $request,
        Builder $query,
        array $allowedFieldsForEqual = [],
        array $allowedFieldsForLike = [],
        array $withModels = []
    ): Paginator {

        $query = self::getTermsOnLike($query, $request, $allowedFieldsForLike);
        $query = self::getTermsOnEqual($query, $request, $allowedFieldsForEqual);

        $withModels = self::getRelations() ?? $withModels;

        if (self::isOrderBy()) {
            return $query->with($withModels)
                ->orderBy(self::$fieldForOrderBy, self::$orderbyDirection)
                ->paginate();
        }

        return $query->with($withModels)
            ->paginate();
    }

    /**
     * Organiza as entidades solicitadas na requests
     * Separadas por [,] e/ou aninhadas por [.]
     *
     * relations=modeloA,modeloB,modeloC.modeloD
     *
     * @return array|null
     */
    public static function getRelations(): ?array
    {
        $hasRelations = request()->get(self::RELATION_SHIP) ?? [];

        if (!$hasRelations) {
            return $hasRelations;
        }

        $relationsArray = explode(',', $hasRelations);
        //remove o underline do nome do relacionameto passado via queryParam
        //[?relations=nome_entidade]
        $relationsArray = array_map(function ($relation) {
            return str_replace('_', '', $relation);
        }, $relationsArray);

        return $relationsArray;
    }

    /**
     * Cria uma buscar usando o operador [LIKE] '%$exemple%'
     *
     * @param Builder $query
     * @param Request $request
     * @param array $allowedFieldsForLike
     * @return Builder
     */
    private static function getTermsOnLike(
        Builder $query,
        Request $request,
        array $allowedFieldsForLike
    ): Builder {
        $termsLike = $request->only($allowedFieldsForLike);
        foreach ($termsLike as $column => $value) {
            !is_null($value) && $query->where(
                $column,
                self::LIKE,
                self::like($value)
            );
        }
        return $query;
    }

    /**
     * Cria uma busca usando o operador [=]
     *
     * @param Builder $query
     * @param Request $request
     * @param array $allowedFieldsForEqual
     * @return Builder
     */
    private static function getTermsOnEqual(
        Builder $query,
        Request $request,
        array $allowedFieldsForEqual
    ): Builder {
        $termsEqual = $request->only($allowedFieldsForEqual);
        foreach ($termsEqual as $column => $value) {
            !is_null($value) && $query->where(
                $column,
                self::EQUAL,
                $value
            );
        }
        return $query;
    }

    /**
     * Verifica se é solicitado a ordenação dos resultados
     * Verifica o campo passado na queryParams da requisição
     * @param mixed  $queryParams[order_by]
     * @return bool
     */
    public static function isOrderBy(): bool
    {
        $requestOrderBy = request()->get(self::ORDER_BY) ?? [];

        if (empty($requestOrderBy)) {
            return false;
        }

        $orderBy = explode(',', $requestOrderBy);
        self::$fieldForOrderBy = $orderBy[0];

        $haveDescOrder =
            sizeof($orderBy) === 2 && strtolower($orderBy[1]) == 'desc';

        if ($haveDescOrder) {
            self::$orderbyDirection = 'DESC';
        }

        return true;
    }
}