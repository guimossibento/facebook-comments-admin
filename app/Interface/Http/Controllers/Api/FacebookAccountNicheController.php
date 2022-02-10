<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Services\FacebookAccountNicheDomainService;
use  App\Domain\Models\FacebookAccount;
use App\Interface\Http\Controllers\AController;

class FacebookAccountNicheController extends AController
{

    public function __construct(protected FacebookAccountNicheDomainService $service)
    {
    }

    public function sync(FacebookAccount $facebookAccount)
    {
        $ids = data_get(request()->get('data'), '*.id');
        return $this->service->sync($facebookAccount, $ids);
    }
}
