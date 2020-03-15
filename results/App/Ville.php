<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
	protected $table = 'ville';
	protected $fillable = ['province_id', 'nom', 'population'];
	//
}