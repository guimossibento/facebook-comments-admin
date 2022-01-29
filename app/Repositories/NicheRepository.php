<?php

namespace App\Repositories;

use App\Models\Niche;

class NicheRepository extends EntityRepository
{

    protected string $entityClassName = Niche::class;
    protected string $defaultSort = Niche::defaultSort;
//    protected array $allowedSorts = Niche::allowedSorts;
//    protected array $allowedFields = Niche::allowedFields;
    protected array $allowedIncludes = Niche::allowedIncludes;
//    protected array $allowedAppends = Niche::allowedAppends;
    protected array $allowedScopes = Niche::allowedScopes;
//    protected array $exactFilters = Niche::exactFilters;
//    protected array $partialFilters = Niche::partialFilters;

}
