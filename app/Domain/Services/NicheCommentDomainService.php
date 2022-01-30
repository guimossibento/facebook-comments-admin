<?php

namespace App\Domain\Services;

class NicheCommentDomainService extends APivotDomainService
{
    public function __construct()
    {
        parent::__construct(relation: 'comments');
    }
}
