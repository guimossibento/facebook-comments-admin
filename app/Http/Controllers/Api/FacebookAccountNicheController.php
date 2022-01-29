<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\FacebookAccountNicheDomainService;
use App\Models\FacebookAccount;

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
