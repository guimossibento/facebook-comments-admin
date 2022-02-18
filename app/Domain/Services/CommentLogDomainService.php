<?php

namespace App\Domain\Services;

use App\Infrastructure\Events\CommentLogEvent;
use  App\Domain\Models\CommentLog;
use  App\Domain\Models\FacebookAccount;
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
		
		CommentLogEvent::dispatch($data);
		
		if (strtolower($data?->status) === 'erro' || strtolower($data?->status) === 'login erro') {
			FacebookAccount::query()
				->where('login', $data->facebook_account_login)
				->update(["active" => 0]);
			
			return $data;
		}
		
		FacebookAccount::query()
			->where('login', $data->facebook_account_login)
			->update(["active" => 1]);
		
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
