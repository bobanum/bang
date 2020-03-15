<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{

	protected $attributes = [
		'id' => '0',
		'ISO' => '',
		'nom2' => '',
		'capitale' => '',
		'population' => 0,
		'nomHabitants' => '',
		'superficie' => 0,
	];
	protected $fillable = ['ISO', 'nom', 'nom2', 'continent', 'capitale', 'population', 'nomHabitants', 'superficie', 'densite', 'popUrbaine', 'frontieres', 'cotes', 'eauxTerritoriales', 'heure', 'moisFroids', 'moisFroidsTemp', 'moisChaud', 'moisChaudsTemp'];

	/** */
	public function province() {
		return $this->belongsTo('App\Province');
	}
	/** */
	public function ville() {
		return $this->belongsTo('App\Ville');
	}


}