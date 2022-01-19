<?php

namespace App\Domain\Services;

class FacebookAccountNicheDomainService extends APivotDomainService
{
    public function __construct()
    {
        parent::__construct(relation: 'niches');
    }
}
