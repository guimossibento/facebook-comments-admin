<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Services\CommentDomainService;
use App\Interface\Http\Controllers\AController;
use  App\Domain\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends AController
{
    public function __construct(protected CommentDomainService $service)
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
     * @param \ App\Domain\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        $data = $this->service->show($comment->id);

        return $this->sendResponse($data, '');
    }


    /**
     * @param Request $request
     * @param Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $this->service->update($comment, $request->all());

        return $this->sendResponse($data, '');
    }

    /**
     * @param Comment $comment
     * @return bool|null
     */
    public function destroy(Comment $comment)
    {
        return $this->service->delete($comment);
    }
	
	public function list()
	{
		return $this->service->list();
	}
}
