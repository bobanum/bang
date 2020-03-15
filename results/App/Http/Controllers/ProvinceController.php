<?php

namespace App\Http\Controllers;

use App\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::all();
        return view("province.index", ['provinces'=>$provinces]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\{Province}  $province
	 * @return \Illuminate\Http\Response
     */
	public function create(Province  $province)
    {
		return view("province.create", ['province'=>$province]);
    }

    /**
     * Store a newly created resource in storage.
     *
	 * @param  \Illuminate\Http\Request  $request
     * @param  \App\{Province}  $province
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Province  $province)
    {
		$province->fill($request->all());
		$province->save();
		return redirect()->action('ProvinceController@show', $province);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\{Province}  $province
     * @return \Illuminate\Http\Response
     */
    public function show(Province  $province)
    {
        return view('province.show', ['province'=>$province]);
    }
	
    /**
	 * Show the form for editing the specified resource.
     *
	 * @param  \App\{Province}  $province
	 * @return \Illuminate\Http\Response
     */
	public function edit(Province $province)
    {
		return view('province.edit', ['province'=>$province]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\{Province}  $province
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Province $province)
    {
		$province->fill($request->all());
		$province->save();
		return redirect()->action('ProvinceController@show', $province);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\{Province}  $province
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province)
    {
		$province->delete();
		return redirect()->action('ProvinceController@index');
    }
}