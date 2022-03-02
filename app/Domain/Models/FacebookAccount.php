<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookAccount extends Model
{
  use HasFactory;

  protected $casts = [
    "active" => "boolean"
  ];

  protected $fillable = [
    "name",
    "login",
    "password",
    "gender",
    "secret_2fa",
    "active",
    "last_comment_date"
  ];

  public const allowedFields = [
    "name",
    "login",
    "password",
    "gender",
    "secret_2fa",
    "active",
    "last_comment_date"
  ];

  public const allowedScopes = [];
  public const exactFilters = ['active'];

  public const partialFilters = [];

  public const allowedIncludes = [
    'niches'
  ];

  protected array $allowedScopes = [];

  public const defaultSort = 'name';

  public function niches()
  {
    return $this->belongsToMany(
      Niche::class,
      'facebook_account_niche',
      'facebook_account_id',
      'niche_id'
    );
  }

//  public function scopeActive($query, $value)
//  {
//    $filter = request()->get('filter', []);
//    return $query
//      ->where($this->getTable() . '.active',
//        '=',
//        (array_key_exists('active', $filter)) ? $filter['active'] : true
//      );
//  }
}
