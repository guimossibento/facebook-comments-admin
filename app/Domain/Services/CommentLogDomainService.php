<?php

namespace App\Domain\Services;

use App\Domain\Models\CommentLog;
use App\Domain\Models\FacebookAccount;
use App\Events\CommentLogEvent;
use App\Infrastructure\Repositories\CommentLogRepository;

class CommentLogDomainService
{
	public function __construct(protected CommentLog $commentLog, protected CommentLogRepository $repository)
	{
	
	}
	
	public function index()
	{
		return $this->repository->findAll();
	}
	
	public function paginate()
	{
		return $this->repository->paginate();
	}
	
	public function store(array $data)
	{
		$data = $this->commentLog::create($data);
		$data = $data::with('facebookAccount')->find($data->id);
		
		CommentLogEvent::dispatch($data);
		
		if (strtolower($data?->status) === 'erro' || strtolower($data?->status) === 'login erro') {
			
			$facebookAccoount = FacebookAccount::find($data->facebook_account_id);
			
			$facebookAccoount->update(["active" => false]);
			
			return $data;
		}
		
		$facebookAccoount = FacebookAccount::find($data->facebook_account_id);
		
		$facebookAccoount->update(["active" => true, "last_comment"=> now()]);
		
		return $data;
	}
	
	public function show(int $id)
	{
		return $this->repository->find($id);
	}
	
	public function delete(CommentLog $commentLog)
	{
		return $commentLog->delete();
	}
	
	public function list()
	{
		return $this->repository->findAll();
	}
	
	public function deleteAll()
	{
		return $this->repository->deleteAll();
	}
}
