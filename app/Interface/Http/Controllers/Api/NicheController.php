<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Services\NicheDomainService;
use  App\Domain\Models\Niche;
use App\Interface\Http\Controllers\AController;
use Illuminate\Http\Request;

class NicheController extends AController
{
	public function __construct(protected NicheDomainService $service)
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
	 * @param \ App\Domain\Models\Niche $niche
	 * @return \Illuminate\Http\Response
	 */
	public function show(Niche $niche)
	{
		$data = $this->service->show($niche->id);
		
		return $this->sendResponse($data, str_replace('DomainService', '', class_basename($this->service) . " Show"));
	}
	
	
	/**
	 * @param Request $request
	 * @param Niche $niche
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Niche $niche)
	{
		$data = $this->service->update($niche, $request->all());
		
		return $this->sendResponse($data, '');
	}
	
	/**
	 * @param Niche $niche
	 * @return bool|null
	 */
	public function destroy(Niche $niche)
	{
		return $this->service->delete($niche);
	}
	
	public function list()
	{
		return $this->service->list();
	}
}

