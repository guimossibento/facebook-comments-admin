<?php

namespace App\Domain\Services;

use  App\Domain\Models\FacebookAccount;
use App\Infrastructure\Repositories\FacebookAccountRepository;

class FacebookAccountDomainService extends AModelDomainService
{
    public function __construct(FacebookAccount $model, FacebookAccountRepository $repository)
    {
        parent::__construct($model, $repository);
    }
}
