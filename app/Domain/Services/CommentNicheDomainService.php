<?php

namespace App\Domain\Services;

class CommentNicheDomainService extends APivotDomainService
{
    public function __construct()
    {
        parent::__construct(relation: 'niches');
    }
}
