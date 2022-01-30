<?php

namespace App\Repositories;

use App\Models\CommentLog;

class CommentLogRepository extends EntityRepository
{

  protected string $entityClassName = CommentLog::class;

  protected string $defaultSort = CommentLog::defaultSort;


  public function deleteAll()
  {
    return CommentLog::query()->delete();
  }
}
