<?php

namespace App\Infrastructure\Repositories;

use  App\Domain\Models\CommentRequestLog;

class CommentRequestLogRepository extends EntityRepository
{

  protected string $entityClassName = CommentRequestLog::class;

  protected string $defaultSort = CommentRequestLog::defaultSort;
  
	protected array $allowedIncludes = CommentRequestLog::allowedIncludes;


  public function deleteAll()
  {
    return CommentRequestLog::query()->delete();
  }
}
