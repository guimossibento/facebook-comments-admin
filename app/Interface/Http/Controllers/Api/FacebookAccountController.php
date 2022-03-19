<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Models\FacebookAccount;
use App\Domain\Services\FacebookAccountDomainService;
use App\Domain\Tasks\TestLoginTask;
use App\Interface\Http\Controllers\AController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FacebookAccountController extends AController
{
  public function __construct(protected FacebookAccountDomainService $service)
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
    $request['secret_2fa'] = trim($request['secret_2fa']);

    $data = $this->service->store($request->all());

    return $this->sendResponse($data, '');
  }

  /**
   * Display the specified resource.
   *
   * @param FacebookAccount $facebookAccount
   * @return Response
   */
  public function show(FacebookAccount $facebookAccount)
  {
    $data = $this->service->show($facebookAccount->id);

    return $this->sendResponse($data, str_replace('DomainService', '', class_basename($this->service) . " Show"));
  }


  /**
   * @param Request $request
   * @param FacebookAccount $facebookAccount
   * @return Response
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

  public function list()
  {
    return $this->service->list();
  }

  public function testLogin(Request $request)
  {
    $login = $request->get('login') ?? false;

    if (!$login) {
      return false;
    }

    $facebook_account = FacebookAccount::query()
      ->where('login', $login)->first();

    if (!empty($facebook_account?->id)) {
      $data = array(
        "user" => $facebook_account?->login,
        "password" => $facebook_account?->password,
        "post_url" => "Teste Login",
        "comment" => "Teste Login",
        "secret_2fa" => $facebook_account?->secret_2fa,
        "test_login" => true
      );
    }

    (new TestLoginTask)->onQueue('comment-task')->execute($data);

    return true;
  }
}
