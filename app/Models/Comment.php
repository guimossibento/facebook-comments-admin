<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ["text"];

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
            'comment_niche',
            'comment_id',
            'niche_id'
        );
    }

}
