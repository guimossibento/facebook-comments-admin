<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\NicheCommentDomainService;
use App\Models\Niche;

class NicheCommentController extends AController
{
	
	public function __construct(protected NicheCommentDomainService $service)
	{
	}
	
	public function sync(Niche $niche)
	{
		$ids = data_get(request()->get('data'), '*.id');
		return $this->service->sync($niche, $ids);
	}
}
