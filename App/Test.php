<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
	protected $table = 'test';
	protected $fillable = ['ISO', 'nom', 'nom2', 'continent', 'capitale', 'population', 'nomHabitants', 'superficie', 'densite', 'popUrbaine', 'frontieres', 'cotes', 'eauxTerritoriales', 'heure', 'moisFroids', 'moisFroidsTemp', 'moisChaud', 'moisChaudsTemp'];
	//
}