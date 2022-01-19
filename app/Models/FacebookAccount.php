<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookAccount extends Model
{
    use HasFactory;

    protected $fillable = ["name","login","password","gender"];

    public const allowedScopes = [
    ];

    public const allowedIncludes = [
        'niches'
    ];

    public function niches()
    {
        return $this->
        belongsToMany(
            Niche::class,
            'facebook_account_niche',
            'facebook_account_id',
            'niche_id'
        );
    }

}
