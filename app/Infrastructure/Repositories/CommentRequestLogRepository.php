<?php

namespace App\Infrastructure\Repositories;

use  App\Domain\Models\CommentRequestLog;
use Illuminate\Support\Facades\Auth;

class CommentRequestLogRepository extends EntityRepository
{

  protected string $entityClassName = CommentRequestLog::class;
  protected string $defaultSort = CommentRequestLog::defaultSort;
	protected array $allowedIncludes = CommentRequestLog::allowedIncludes;
  protected array $allowedScopes = CommentRequestLog::allowedScopes;


  public function deleteAll()
  {
    return CommentRequestLog::query()->where('user_id', Auth::id())->delete();
  }
}
