<?php

namespace App\Http\Controllers\API;

use App\Domain\Tasks\ExecuteCommentsTask;
use App\Models\Niche;

class DashboardController
{
    public function executeComments()
    {

        $facebookAccounts = Niche::with(
            ['facebookAccounts' => function ($q) {
                $q->where('facebook_accounts.gender', request()->get('gender'));
            }])
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
        $facebookAccounts->map(function ($facebookAccount) use ($comments, &$commentIndex) {
            if (!array_key_exists($commentIndex, $comments) ?? true) {
                $commentIndex = 0;
            }

            (new ExecuteCommentsTask())->onQueue()->execute($facebookAccount, request()->get('url'), $comments[$commentIndex]['text']);

            $commentIndex++;
        });

        return response()->json();
    }
}
