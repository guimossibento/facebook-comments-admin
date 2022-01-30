<?php

namespace App\Http\Controllers\Api;

use App\Domain\Tasks\ExecuteCommentsTask;
use App\Models\Niche;

class DashboardController
{
    public function executeComments()
    {

        $facebookAccounts = Niche::with(
            [
                'facebookAccounts' => function ($q) {
                    if (request()->get('gender') !== 'A') {
                        $q->where('facebook_accounts.gender', request()->get('gender'));
                    }
                }
            ]
        )
            ->find(request()->get('niche'))
            ?->facebookAccounts;

        if (blank($facebookAccounts)) {
            return response(["message" => "Sem contas para execução."], 400);
        }

        $facebookAccounts = $facebookAccounts->take(request()->get('comment_amount'));

        $comments = Niche::with('comments')
            ->find(request()->get('niche'))
            ?->comments->toArray();

        if (blank($comments)) {
            return response(["message" => "Sem comentários para execução."], 400);
        }

        $commentIndex = 0;
        $message['message'] = '';

        $facebookAccounts->map(function ($facebookAccount) use ($comments, &$commentIndex, &$message) {
            if (!array_key_exists($commentIndex, $comments) ?? true) {
                $commentIndex = 0;
            }

            if (blank($facebookAccount->secret_2fa)) {
                $message["message"] .=  "$facebookAccount->name sem secret 2fa;";
                return;
            }

            if (blank($facebookAccount->password)) {
                $message["message"] .=  "$facebookAccount->name sem senha;";
                return;
            }

            (new ExecuteCommentsTask())->onQueue('comment-task')->execute($facebookAccount, request()->get('url'), $comments[$commentIndex]['text']);

            $commentIndex++;
        });

        if ($message['message'] !== '') {
            return response($message, 200);
        }

        return response()->json();
    }
}
