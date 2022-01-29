<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository extends EntityRepository
{

    protected string $entityClassName = Comment::class;
    protected string $defaultSort = Comment::defaultSort;
//    protected array $allowedSorts = Comment::allowedSorts;
//    protected array $allowedFields = Comment::allowedFields;
    protected array $allowedIncludes = Comment::allowedIncludes;
//    protected array $allowedAppends = Comment::allowedAppends;
    protected array $allowedScopes = Comment::allowedScopes;
//    protected array $exactFilters = Comment::exactFilters;
//    protected array $partialFilters = Comment::partialFilters;

}
