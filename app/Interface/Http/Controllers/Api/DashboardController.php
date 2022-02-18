<?php

namespace App\Interface\Http\Controllers\Api;

use App\Domain\Models\CommentLog;
use App\Domain\Models\CommentRequestLog;
use App\Domain\Models\Niche;
use App\Domain\Tasks\ExecuteCommentsTask;

class DashboardController
{
	public function executeComments()
	{
		
		$facebookAccounts = Niche::with(
			[
				'facebookAccounts' => function ($q) {
					if (request()->get('gender') !== 'A') {
						$q->where('facebook_accounts.gender', request()->get('gender'));
						$q->where('facebook_accounts.active', true);
						$q->where('facebook_accounts.secret_2fa', '!=', null);
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
		
		$commentRequestLog = CommentRequestLog::create([
			"post_url" => request()->get('url'),
			"total_request" => $facebookAccounts->count(),
			"niche_id" => request()->get('niche')
		]);
		
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
			
			$already_commented = CommentLog::query()
				->where('facebook_account_id', $facebookAccount->id)
				->where('post_url', $post_url)
				->first();
			
			if (!blank($already_commented)) {
				$message["message"] .= "$facebookAccount->name já comentou;";
				return;
			}
			
			(new ExecuteCommentsTask())->onQueue('comment-task')->execute($facebookAccount, $post_url, $comments[$commentIndex]['text'], $commentRequestLog->id);
			
			$commentIndex++;
		});
		
		
		if ($message['message'] !== '') {
			return response($message, 200);
		}
		
		return response()->json();
	}
}
