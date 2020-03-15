<?php

namespace App\Http\Controllers;

use App\Ville;
use Illuminate\Http\Request;

class VilleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $villes = Ville::all();
        return view("ville.index", ['villes'=>$villes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\{Ville}  $ville
	 * @return \Illuminate\Http\Response
     */
	public function create(Ville  $ville)
    {
		return view("ville.create", ['ville'=>$ville]);
    }

    /**
     * Store a newly created resource in storage.
     *
	 * @param  \Illuminate\Http\Request  $request
     * @param  \App\{Ville}  $ville
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ville  $ville)
    {
		$ville->fill($request->all());
		$ville->save();
		return redirect()->action('VilleController@show', $ville);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\{Ville}  $ville
     * @return \Illuminate\Http\Response
     */
    public function show(Ville  $ville)
    {
        return view('ville.show', ['ville'=>$ville]);
    }
	
    /**
	 * Show the form for editing the specified resource.
     *
	 * @param  \App\{Ville}  $ville
	 * @return \Illuminate\Http\Response
     */
	public function edit(Ville $ville)
    {
		return view('ville.edit', ['ville'=>$ville]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\{Ville}  $ville
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ville $ville)
    {
		$ville->fill($request->all());
		$ville->save();
		return redirect()->action('VilleController@show', $ville);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\{Ville}  $ville
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ville $ville)
    {
		$ville->delete();
		return redirect()->action('VilleController@index');
    }
}