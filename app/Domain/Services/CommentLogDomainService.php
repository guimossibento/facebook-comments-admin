<?php

namespace App\Domain\Services;

use App\Models\CommentLog;
use App\Repositories\CommentLogRepository;

class CommentLogDomainService
{
  public function __construct(protected  CommentLog $commentLog, protected CommentLogRepository $repository)
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
    return $this->commentLog::create($data);
  }

  public function show(int $id)
  {
    return $this->repository->find($id);
  }

  public function delete(CommentLog $commentLog)
  {
    return $commentLog->delete();
  }

  public function list()
  {
    return $this->repository->findAll();
  }

  public function deleteAll()
  {
    return $this->repository->deleteAll();
  }
}
