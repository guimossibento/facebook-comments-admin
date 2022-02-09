<?php

namespace App\Domain\Services;

use App\Repositories\EntityRepository;
use Illuminate\Database\Eloquent\Model;

abstract class AModelDomainService
{

    public function __construct(protected Model $model, protected EntityRepository $repository)
    {
    }

    public function index()
    {
        return $this->repository->findAll();
    }

    public function paginate()
    {
        return $this->repository->paginate();
    }

    public function store(array $data)
    {
        return $this->model::create($data);
    }

    public function show(int $id)
    {
        return $this->repository->find($id);
    }

    public function update(Model $model, array $data)
    {
        $model->update($data);
		return $model->refresh();
    }

    public function delete(Model $model)
    {
        return $model->delete();
    }

    public function list()
    {
        return $this->repository->findAll();
    }
}
