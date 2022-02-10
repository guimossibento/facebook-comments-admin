<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Models\CommentRequestLog;
use App\Domain\Services\CommentRequestLogDomainService;
use App\Interface\Http\Controllers\AController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentRequestLogController extends AController
{
	public function __construct(protected CommentRequestLogDomainService $service)
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
		$data = $this->service->store($request->all());
		
		return $this->sendResponse($data, '');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param CommentRequestLog $commentLog
	 * @return Response
	 */
	public function show(CommentRequestLog $commentLog)
	{
		return $this->sendResponse($commentLog, str_replace('DomainService', '', class_basename($this->service) . " Show"));
	}
	
	/**
	 * @param CommentRequestLog $commentLog
	 * @return bool|null
	 */
	public function destroy(CommentRequestLog $commentLog)
	{
		return $this->service->delete($commentLog);
	}
	
	public function list()
	{
		return $this->service->list();
	}
	
	/**
	 * @return bool|null
	 */
	public function destroyAll()
	{
		return $this->service->deleteAll();
	}
}

