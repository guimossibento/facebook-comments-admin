<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Services\NicheFacebookAccountDomainService;
use  App\Domain\Models\Niche;
use App\Interface\Http\Controllers\AController;

class NicheFacebookAccountController extends AController
{
	
	public function __construct(protected NicheFacebookAccountDomainService $service)
	{
	}
	
	public function sync(Niche $niche)
	{
		$ids = data_get(request()->get('data'), '*.id');
		return $this->service->sync($niche, $ids);
	}
}
