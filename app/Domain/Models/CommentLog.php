<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLog extends Model
{
	use HasFactory;
	
	protected $casts = [
		'debug_json' => 'json',
	];
	
	protected $fillable = [
		"facebook_account_login",
		"post_url",
		"comment",
		"status",
		"debug_json",
		'comment_request_log_id',
	];
	
	public const defaultSort = '-created_at';
}
