<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $fillable = [
    'name', 'description'
  ];

  public const allowedScopes = [];

  public const allowedIncludes = [];

  public const defaultSort = '-created_at';

  protected $table = 'roles';

  public function users()
  {
    return $this->belongsToMany(User::class);
  }
}
