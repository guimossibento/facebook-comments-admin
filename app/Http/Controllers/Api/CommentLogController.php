<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\CommentLogDomainService;
use App\Models\CommentLog;
use Illuminate\Http\Request;

class CommentLogController extends AController
{
  public function __construct(protected CommentLogDomainService $service)
  {
  }

  /**
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->sendResponse($this->service->paginate(), '');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $this->service->store($request->all());

    return $this->sendResponse($data, '');
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Models\CommentLog $commentLog
   * @return \Illuminate\Http\Response
   */
  public function show(CommentLog $commentLog)
  {
    return $this->sendResponse($commentLog, str_replace('DomainService', '', class_basename($this->service) . " Show"));
  }

  /**
   * @param CommentLog $commentLog
   * @return bool|null
   */
  public function destroy(CommentLog $commentLog)
  {
    return $this->service->delete($commentLog);
  }

  public function list()
  {
    return $this->service->list();
  }
}

