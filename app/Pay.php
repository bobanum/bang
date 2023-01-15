<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    public $timestamps = false;

	protected $attributes = [
		'id' => '0',
		'ISO' => '',
		'nom2' => '',
		'capitale' => '',
		'population' => 0,
		'nomHabitants' => '',
		'superficie' => 0,
	];
	protected $fillable = ['nom', 'ISO', 'nom2', 'continent', 'capitale', 'population', 'nomHabitants', 'superficie', 'densite', 'popUrbaine', 'frontieres', 'cotes', 'eauxTerritoriales', 'heure', 'moisFroids', 'moisFroidsTemp', 'moisChaud', 'moisChaudsTemp'];

    /** */
    public function provinces() {
        return $this->hasMany('App\Province');
    }
    /** */
    public function villes() {
        return $this->hasMany('App\Ville');
    }

    protected function getRulesAttribute() {
        $result = [];
		$result['nom'] = 'required';
		$result['ISO'] = 'required';
		$result['nom2'] = 'required';
		$result['continent'] = 'required';
		$result['capitale'] = 'required';
		$result['population'] = 'required';
		$result['nomHabitants'] = 'required';
		$result['superficie'] = 'required';
		$result['densite'] = 'required';
		$result['popUrbaine'] = 'required';
		$result['frontieres'] = 'required';
		$result['cotes'] = 'required';
		$result['eauxTerritoriales'] = 'required';
		$result['heure'] = 'required';
		$result['moisFroids'] = 'required';
		$result['moisFroidsTemp'] = 'required';
		$result['moisChaud'] = 'required';
		$result['moisChaudsTemp'] = 'required';
        return $result;
    }
    static function fake() {
        $faker = \Faker\Factory::create();
        $result = new static();
		$result->nom = $faker->word;
		$result->ISO = $faker->word;
		$result->nom2 = $faker->word;
		$result->continent = $faker->word;
		$result->capitale = $faker->word;
		$result->population = $faker->word;
		$result->nomHabitants = $faker->word;
		$result->superficie = $faker->word;
		$result->densite = $faker->word;
		$result->popUrbaine = $faker->word;
		$result->frontieres = $faker->word;
		$result->cotes = $faker->word;
		$result->eauxTerritoriales = $faker->word;
		$result->heure = $faker->word;
		$result->moisFroids = $faker->word;
		$result->moisFroidsTemp = $faker->word;
		$result->moisChaud = $faker->word;
		$result->moisChaudsTemp = $faker->word;
        return $result;
    }
}