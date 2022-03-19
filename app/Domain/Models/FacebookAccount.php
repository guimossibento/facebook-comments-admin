<?php

namespace App\Domain\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property string $gender
 * @property string $secret_2fa
 * @property bool $active
 * @property Carbon $last_comment
 */
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
    "last_comment"
  ];

  public const allowedFields = [
    "name",
    "login",
    "password",
    "gender",
    "secret_2fa",
    "active",
    "last_comment"
  ];

  public const allowedScopes = [];
  public const exactFilters = ['active'];

  public const partialFilters = ['name'];

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
