<?php

namespace App\Domain\Services;

use  App\Domain\Models\Niche;
use App\Infrastructure\Repositories\NicheRepository;

class NicheDomainService extends AModelDomainService
{
    public function __construct(Niche $niche, NicheRepository $repository)
    {
        parent::__construct($niche, $repository);
    }
}
