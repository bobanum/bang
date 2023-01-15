<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

	protected $attributes = [
	];
	protected $fillable = ['pay_id', 'nom', 'superficie'];

    /** */
    public function pay() {
        return $this->belongsTo('App\Pay');
    }
    /** */
    public function villes() {
        return $this->hasMany('App\Ville');
    }

    protected function getRulesAttribute() {
        $result = [];
		$result['pay_id'] = 'required';
		$result['nom'] = 'required';
		$result['superficie'] = 'required';
        return $result;
    }
    static function fake() {
        $faker = \Faker\Factory::create();
        $result = new static();
		$result->pay_id = $faker->word;
		$result->nom = $faker->word;
		$result->superficie = $faker->word;
        return $result;
    }
}