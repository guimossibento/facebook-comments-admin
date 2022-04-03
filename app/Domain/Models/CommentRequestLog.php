<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class CommentRequestLog extends Model
{
	use HasFactory;
	
	protected $fillable = ["total_request", "post_url", "niche_id", "user_id"];
	
	public const allowedScopes = ['user'];
	
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

  /**
   * Scope a query to only include active users.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return void
   */
  public function scopeUser(Builder $query)
  {
    if (!is_null(Auth::id())) {
      $query->where('user_id', Auth::id());
    }
  }

  public function commentLogs()
	{
		return $this->hasMany(CommentLog::class);
	}
	
	public function niche()
	{
		return $this->belongsTo(Niche::class);
	}
}
