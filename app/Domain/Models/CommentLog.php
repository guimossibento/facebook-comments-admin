<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CommentLog extends Model
{
  use HasFactory;

  protected $casts = [
    'debug_json' => 'json',
  ];

  public const allowedIncludes = [
    'facebookAccount'
  ];

  protected $fillable = [
    "facebook_account_id",
    "user_id",
    "post_url",
    "comment",
    "status",
    "debug_json",
    'comment_request_log_id',
  ];

  public const defaultSort = '-created_at';

  public const exactFilters = ['user_id'];

  public const allowedScopes = ['user'];

  /**
   * Scope a query to only include active users.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return void
   */
  public function scopeUser(Builder $query)
  {
    $query->where('user_id', Auth::id());
  }

  public function facebookAccount()
  {
    return $this->belongsTo(FacebookAccount::class);
  }
}
