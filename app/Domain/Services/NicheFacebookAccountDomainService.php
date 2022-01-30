<?php

namespace App\Domain\Services;

class NicheFacebookAccountDomainService extends APivotDomainService
{
    public function __construct()
    {
        parent::__construct(relation: 'facebookAccounts');
    }
}
