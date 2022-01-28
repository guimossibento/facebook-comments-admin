<?php

namespace App\Domain\Tasks;

use App\Models\FacebookAccount;
use Illuminate\Support\Facades\Http;
use Spatie\QueueableAction\QueueableAction;
use Spatie\RateLimitedMiddleware\RateLimited as RateLimited;

class ExecuteCommentsTask
{
    use QueueableAction;

    public function execute(FacebookAccount $facebookAccount, string $url, string $comment)
    {
        $data = [
            "user" => $facebookAccount->login,
            "password" => $facebookAccount->password,
            "post_url" => $url,
            "comment" => $comment,
            "secret_2fa" => $facebookAccount->secret_2fa,
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
            ->everySeconds(60)
            ->releaseAfterSeconds(60);;

        return [$rateLimitedMiddleware];
    }
}
