<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLog extends Model
{
  use HasFactory;

  protected $fillable = [
    "facebook_account_login",
    "post_url",
    "comment",
    "status",
  ];

  public const defaultSort = '-created_at';
}
