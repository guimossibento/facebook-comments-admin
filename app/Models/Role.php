<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    public const allowedScopes = [

    ];

    public const allowedIncludes = [

    ];

    protected $table = 'roles';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
