<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niche extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public const allowedScopes = [
        'hasNotComment',
        'hasNotFacebookAccount'
    ];

    public const allowedIncludes = [
        'comments',
        'facebookAccounts'
    ];

    public function scopeHasNotComment($query, ...$term)
    {
        $nichesIds = Comment::with('niches')
            ->whereIn('id', $term)
            ->get()
            ->map(function ($comment) {
                return $comment->niches->pluck('id');
            })->flatten()->toArray();

        return $query->whereNotIn('id', $nichesIds);
    }

    public function scopeHasNotFacebookAccount($query, ...$term)
    {
        $nichesIds = FacebookAccount::with('niches')
            ->whereIn('id', $term)
            ->get()
            ->map(function ($comment) {
                return $comment->niches->pluck('id');
            })->flatten()->toArray();

        return $query->whereNotIn('id', $nichesIds);
    }

    public function comments()
    {
        return $this->belongsToMany(
            Comment::class,
            'comment_niche',
            'niche_id',
            'comment_id');
    }

    public function facebookAccounts()
    {
        return $this->belongsToMany(
            FacebookAccount::class,
            'facebook_account_niche',
            'niche_id',
            'facebook_account_id');
    }


}
