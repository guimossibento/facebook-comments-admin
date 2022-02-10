<?php

namespace App\Domain\Services;

use App\Domain\Models\CommentRequestLog;
use App\Infrastructure\Repositories\CommentRequestLogRepository;

class CommentRequestLogDomainService
{
	public function __construct(protected CommentRequestLog $commentLog, protected CommentRequestLogRepository $repository)
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
		
		return $data;
	}
	
	public function show(int $id)
	{
		return $this->repository->find($id);
	}
	
	public function delete(CommentRequestLog $commentLog)
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
