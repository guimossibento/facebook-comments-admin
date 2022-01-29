<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\CommentNicheDomainService;
use App\Models\Comment;
use App\Models\CommentNiche;

class CommentNicheController extends AController
{

    public function __construct(protected CommentNicheDomainService $service)
    {
    }

    public function sync(Comment $comment)
    {
        $ids = data_get(request()->get('data'), '*.id');
        return $this->service->sync($comment, $ids);
    }
}
