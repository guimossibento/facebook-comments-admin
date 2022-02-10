<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentRequestLog extends Model
{
	use HasFactory;
	
	protected $fillable = ["total_request", "post_url", "niche_id"];
	
	public const allowedScopes = [];
	
	public const allowedIncludes = [
		'commentLogs',
		'niche',
	];
	
	public const exactFilters = [
		"comment_log_id"
	];
	
	public const partialFilters = [
	
	];
	
	public const defaultSort = '-created_at';
	
	public function commentLogs()
	{
		return $this->hasMany(CommentLog::class);
	}
	
	public function niche()
	{
		return $this->belongsTo(Niche::class);
	}
}
