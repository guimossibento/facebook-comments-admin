<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Models\CommentLog;
use App\Domain\Models\FacebookAccount;
use App\Domain\Services\CommentLogDomainService;
use App\Interface\Http\Controllers\AController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentLogController extends AController
{
	public function __construct(protected CommentLogDomainService $service)
	{
	}
	
	/**
	 * @return Response
	 */
	public function index()
	{
		return $this->sendResponse($this->service->paginate(), '');
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$facebook_account = FacebookAccount::query()
        ->where('login', trim($request->get('facebook_account_login')))->first();

    $data = $request->all();

		unset($data["facebook_account_login"]);
		
		$data = array_merge($data, ["facebook_account_id" => $facebook_account->id]);

		$stored = $this->service->store($data);
		
		return $this->sendResponse($stored, '');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param CommentLog $commentLog
	 * @return Response
	 */
	public function show(CommentLog $commentLog)
	{
		return $this->sendResponse($commentLog, str_replace('DomainService', '', class_basename($this->service) . " Show"));
	}
	
	/**
	 * @param CommentLog $commentLog
	 * @return bool|null
	 */
	public function destroy(CommentLog $commentLog): ?bool
	{
		return $this->service->delete($commentLog);
	}
	
	public function list(): \Illuminate\Database\Eloquent\Collection|array
	{
		return $this->service->list();
	}
	
	/**
	 * @return bool|null
	 */
	public function destroyAll(): ?bool
	{
		return $this->service->deleteAll();
	}
}

