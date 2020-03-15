<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

	protected $attributes = [
	];
	protected $fillable = ['pay_id', 'nom', 'superficie'];

	/** */
	public function ville() {
		return $this->belongsTo('App\Ville');
	}
	/** */
	public function pay() {
		return $this->belongsTo('App\Pay');
	}

}