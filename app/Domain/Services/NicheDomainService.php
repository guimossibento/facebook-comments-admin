<?php

namespace App\Domain\Services;

use App\Models\Niche;
use App\Repositories\NicheRepository;

class NicheDomainService extends AModelDomainService
{
    public function __construct(Niche $niche, NicheRepository $repository)
    {
        parent::__construct($niche, $repository);
    }
}
