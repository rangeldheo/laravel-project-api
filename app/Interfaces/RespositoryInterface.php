<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

interface RespositoryInterface
{

    public static function index($classe);
    public static function show($object);
    public static function store($classe, $request);
    public static function update($classe, $request);
    public static function getAllPaginate(ValidatesWhenResolved $request, string $modelName): Paginator;
}
