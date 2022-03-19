<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Models\CommentLog;
use App\Domain\Models\CommentRequestLog;
use App\Domain\Models\Niche;
use App\Domain\Tasks\ExecuteCommentsTask;
use App\Events\CommentRequestLogEvent;

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
          $q->where('facebook_accounts.secret_2fa', '!=', null);
          $q->where('facebook_accounts.active', true);
          $q->orderBy('facebook_accounts.last_comment', 'asc');
        }
      ]
    )
      ->find(request()->get('niche'))
      ?->facebookAccounts;

    $facebookAccounts = $facebookAccounts->filter(function ($facebookAccount) {
      return blank(CommentLog::query()
        ->where('facebook_account_id', $facebookAccount->id)
        ->where('post_url', request()->get('url'))
        ->where('status', 'like', '%Sucesso%')
        ->first());
    });

    $facebookAccounts = $facebookAccounts->take(request()->get('comment_amount'));

    if (blank($facebookAccounts)) {
      return response(["message" => "Sem contas para execução."], 400);
    }

    $comments = array_values(Niche::with('comments')
      ->find(request()->get('niche'))
      ?->comments
      ->filter(function ($comment) {
        return blank(CommentLog::query()
          ->where('comment', $comment->text)
          ->where('post_url', request()->get('url'))
          ->where('status', 'like', '%Sucesso%')
          ->first());
      })
      ->toArray());

    if (blank($comments)) {
      return response(["message" => "Sem comentários para execução."], 400);
    }

    $commentRequestLog = CommentRequestLog::create([
      "post_url" => request()->get('url'),
      "total_request" => $facebookAccounts->count(),
      "niche_id" => request()->get('niche')
    ]);

    $data = $commentRequestLog::with(['niche', 'commentLogs'])->find($commentRequestLog->id);
    CommentRequestLogEvent::dispatch($data);

    $commentIndex = 0;
    $message['message'] = '';

    $facebookAccounts->map(function ($facebookAccount) use ($comments, &$commentIndex, &$message, &$commentRequestLog) {
      if (!array_key_exists($commentIndex, $comments) ?? true) {
        $commentIndex = 0;
      }

      if (blank($facebookAccount->secret_2fa)) {
        $message["message"] .= "$facebookAccount->name sem secret 2fa;";
        return;
      }

      if (blank($facebookAccount->password)) {
        $message["message"] .= "$facebookAccount->name sem senha;";
        return;
      }

      $post_url = request()->get('url');

      (new ExecuteCommentsTask())->onQueue('comment-task')->execute($facebookAccount, $post_url, $comments[$commentIndex]['text'], $commentRequestLog->id);

      $commentIndex++;
    });


    if ($message['message'] !== '') {
      return response($message, 200);
    }

    return response()->json();
  }
}
