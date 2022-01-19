<?php

namespace App\Repositories;

use App\Models\FacebookAccount;

class FacebookAccountRepository extends EntityRepository
{

    protected string $entityClassName = FacebookAccount::class;
//    protected string $defaultSort = FacebookAccount::defaultSort;
//    protected array $allowedSorts = FacebookAccount::allowedSorts;
//    protected array $allowedFields = FacebookAccount::allowedFields;
    protected array $allowedIncludes = FacebookAccount::allowedIncludes;
//    protected array $allowedAppends = FacebookAccount::allowedAppends;
    protected array $allowedScopes = FacebookAccount::allowedScopes;
//    protected array $exactFilters = FacebookAccount::exactFilters;
//    protected array $partialFilters = FacebookAccount::partialFilters;

}
