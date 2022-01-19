<?php

namespace App\Domain\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;

class CommentDomainService extends AModelDomainService
{
    public function __construct(Comment $model, CommentRepository $repository)
    {
       parent::__construct($model, $repository);
    }
}
