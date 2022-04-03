<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Models\CommentLog;
use Illuminate\Support\Facades\Auth;

class CommentLogRepository extends EntityRepository
{
	
	protected string $entityClassName = CommentLog::class;
	
	protected string $defaultSort = CommentLog::defaultSort;
	protected array $allowedIncludes = CommentLog::allowedIncludes;
  protected array $exactFilters = CommentLog::exactFilters;
  protected array $allowedScopes = CommentLog::allowedScopes;
	
	
	public function deleteAll()
	{
		return CommentLog::query()->where('user_id', Auth::id())->delete();
	}
}
