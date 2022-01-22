<?php

namespace App\Http\Controllers\API;

use App\Domain\Services\FacebookAccountDomainService;
use App\Models\FacebookAccount;
use Illuminate\Http\Request;

class FacebookAccountController extends AController
{
    public function __construct(protected FacebookAccountDomainService $service)
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
        $request['secret_2fa'] = trim($request['secret_2fa']);

        $data = $this->service->store($request->all());

        return $this->sendResponse($data, '');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\FacebookAccount $facebookAccount
     * @return \Illuminate\Http\Response
     */
    public function show(FacebookAccount $facebookAccount)
    {
        $data = $this->service->show($facebookAccount->id);

        return $this->sendResponse($data, str_replace('DomainService', '', class_basename($this->service) . " Show"));
    }


    /**
     * @param Request $request
     * @param FacebookAccount $facebookAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FacebookAccount $facebookAccount)
    {
        $request['secret_2fa'] = trim($request['secret_2fa']);

        $data = $this->service->update($facebookAccount, $request->all());

        return $this->sendResponse($data, '');
    }

    /**
     * @param FacebookAccount $facebookAccount
     * @return bool|null
     */
    public function destroy(FacebookAccount $facebookAccount)
    {
        return $this->service->delete($facebookAccount);
    }
}
