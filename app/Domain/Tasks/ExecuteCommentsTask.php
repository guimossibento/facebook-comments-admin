<?php

namespace App\Domain\Tasks;

use App\Models\FacebookAccount;
use Illuminate\Support\Facades\Http;
use Spatie\QueueableAction\QueueableAction;

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
}
