<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Article extends Model {

	protected $fillable = [
			'title',
			'body',
			'published_at',
			'user_id'
	];

	public function setPublishedAtAttribute($date) {
		$this->attributes['published_at'] = Carbon::parse($date);
	}
	
	public function scopePublished($query) {
		$query->where('published_at', '<=', Carbon::now());
	}
	
	public function user() {
		return $this->belongsTo('App\User');
	}
}
