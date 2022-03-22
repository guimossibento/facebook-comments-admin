<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Models\CommentLog;

class CommentLogRepository extends EntityRepository
{
	
	protected string $entityClassName = CommentLog::class;
	
	protected string $defaultSort = CommentLog::defaultSort;
	protected array $allowedIncludes = CommentLog::allowedIncludes;
  protected array $exactFilters = CommentLog::exactFilters;
	
	
	public function deleteAll()
	{
		return CommentLog::query()->delete();
	}
}
