<?php

namespace App\Domain\Services;

use App\Models\FacebookAccount;
use App\Repositories\FacebookAccountRepository;

class FacebookAccountDomainService extends AModelDomainService
{
    public function __construct(FacebookAccount $model, FacebookAccountRepository $repository)
    {
       parent::__construct($model, $repository);
    }
}
