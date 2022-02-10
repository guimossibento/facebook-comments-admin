<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	use HasFactory;
	
	protected $fillable = ["text", "niche_id"];
	
	public const allowedScopes = [];
	
	public const allowedIncludes = [
		'niche'
	];
	
	public const exactFilters = [
		"niche_id"
	];
	
	public const partialFilters = [
	
	];
	
	public const defaultSort = '-created_at';
	
	public function niches()
	{
		return $this->belongsTo(Niche::class);
	}
}
