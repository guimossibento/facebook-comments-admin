<?php

namespace App\Http\Controllers\API;

use App\Domain\Services\AModelDomainService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface IResourceController
{
    public function __construct(AModelDomainService $service);

    /**
     * @return \Illuminate\Http\Response
     */
    public function index();

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request);

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Http\Response
     */
    public function show(Model $model);

    /**
     * @param Request $request
     * @param Model $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $model);

    /**
     * @param Model $model
     * @return bool|null
     */
    public function destroy(Model $model);

}
