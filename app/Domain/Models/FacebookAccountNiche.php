<?php

namespace  App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookAccountNiche extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $fillable = ["niche_id", "facebook_account_id"];

  public const defaultSort = '-created_at';

  public const allowedScopes = [];

  public const allowedIncludes = [];
}
