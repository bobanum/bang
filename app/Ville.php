<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    public $timestamps = false;

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

    protected function getRulesAttribute() {
        $result = [];
		$result['province_id'] = 'required';
		$result['pays_id'] = 'required';
		$result['nom'] = 'required';
		$result['population'] = 'required';
        return $result;
    }
    static function fake() {
        $faker = \Faker\Factory::create();
        $result = new static();
		$result->province_id = $faker->word;
		$result->pays_id = $faker->word;
		$result->nom = $faker->word;
		$result->population = $faker->word;
        return $result;
    }
}