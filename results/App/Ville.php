<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
	protected $table = 'ville';

	protected $attributes = [
	];
	protected $fillable = ['province_id', 'pays_id', 'nom', 'population'];


	/** */
	public function pay() {
		return $this->belongsTo('App\Pay');
	}
	/** */
	public function province() {
		return $this->belongsTo('App\Province');
	}

}