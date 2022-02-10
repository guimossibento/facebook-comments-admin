<?php

namespace App\Infrastructure\Repositories;

use  App\Domain\Models\CommentLog;

class CommentLogRepository extends EntityRepository
{

  protected string $entityClassName = CommentLog::class;

  protected string $defaultSort = CommentLog::defaultSort;


  public function deleteAll()
  {
    return CommentLog::query()->delete();
  }
}