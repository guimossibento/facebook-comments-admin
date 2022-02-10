<?php

namespace App\Domain\Services;

use  App\Domain\Models\Comment;
use App\Infrastructure\Repositories\CommentRepository;

class CommentDomainService extends AModelDomainService
{
    public function __construct(Comment $model, CommentRepository $repository)
    {
        parent::__construct($model, $repository);
    }
}
