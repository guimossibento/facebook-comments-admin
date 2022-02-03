<?php

namespace App\Domain\Tasks;

use App\Models\FacebookAccount;
use Illuminate\Support\Facades\Http;
use Spatie\QueueableAction\QueueableAction;
use Spatie\RateLimitedMiddleware\RateLimited as RateLimited;

class TestLoginTask
{
    use QueueableAction;

    public function execute(array $data)
    {
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
            ->everySeconds(40)
            ->releaseAfterSeconds(10);

        return [$rateLimitedMiddleware];
    }
}
