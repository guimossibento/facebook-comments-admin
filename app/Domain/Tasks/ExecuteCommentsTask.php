<?php

namespace App\Domain\Tasks;

use App\Domain\Models\FacebookAccount;
use Illuminate\Support\Facades\Http;
use Spatie\QueueableAction\QueueableAction;
use Spatie\RateLimitedMiddleware\RateLimited as RateLimited;

class ExecuteCommentsTask
{
	use QueueableAction;
	
	public function execute(FacebookAccount $facebookAccount, string $url, string $comment, int $commentRequestLogID, int $userId)
	{
		$data = [
			"user" => $facebookAccount->login,
			"password" => $facebookAccount->password,
			"post_url" => $url,
			"comment" => $comment,
			"secret_2fa" => $facebookAccount->secret_2fa,
			"comment_request_log_id" => $commentRequestLogID,
      "test_login" => false,
			"user_id" => $userId,
		];
		
		Http::withHeaders(['Authorization' => env('JWT_TOKEN_PUPPETEER')])
			->acceptJson()
			->contentType('application/json')
			->put("http://localhost:8081/api/comment", $data)
			->throw(function ($response, $e) {
				return $e;
			})
			->body();
	}
	
	public function middleware()
	{
		$rateLimitedMiddleware = (new RateLimited())
			->allow(1)
			->everySeconds(20)
			->releaseAfterSeconds(10);
		
		return [$rateLimitedMiddleware];
	}
}
