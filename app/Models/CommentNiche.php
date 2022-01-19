<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentNiche extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ["niche_id", "comment_id"];

    public const allowedScopes = [

    ];

    public const allowedIncludes = [

    ];

}
