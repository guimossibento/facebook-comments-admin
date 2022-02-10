<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niche extends Model
{
	use HasFactory;
	
	protected $fillable = ["name"];
	
	public const exactFilters = [
		"id"
	];
	
	public const partialFilters = [
	
	];
	
	public const allowedScopes = [
		'hasNotFacebookAccount'
	];
	
	public const allowedIncludes = [
		'comments',
		'facebookAccounts'
	];
	
	public const defaultSort = '-created_at';
	
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
		return $this->hasMany(Comment::class);
	}
	
	public function facebookAccounts()
	{
		return $this->belongsToMany(
			FacebookAccount::class,
			'facebook_account_niche',
			'niche_id',
			'facebook_account_id'
		);
	}
}
