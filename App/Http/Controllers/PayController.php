<?php

namespace App\Http\Controllers;

use App\Pay;
use Illuminate\Http\Request;

class PayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pays = Pay::all();
        return view("pay.index", ['pays'=>$pays]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\{Pay}  $pay
	 * @return \Illuminate\Http\Response
     */
	public function create(Pay  $pay)
    {
		return view("pay.create", ['pay'=>$pay]);
    }

    /**
     * Store a newly created resource in storage.
     *
	 * @param  \Illuminate\Http\Request  $request
     * @param  \App\{Pay}  $pay
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Pay  $pay)
    {
		$pay->fill($request->all());
		$pay->save();
		return redirect()->action('PayController@show', $pay);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\{Pay}  $pay
     * @return \Illuminate\Http\Response
     */
    public function show(Pay  $pay)
    {
        return view('pay.show', ['pay'=>$pay]);
    }
	
    /**
	 * Show the form for editing the specified resource.
     *
	 * @param  \App\{Pay}  $pay
	 * @return \Illuminate\Http\Response
     */
	public function edit(Pay $pay)
    {
		return view('pay.edit', ['pay'=>$pay]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\{Pay}  $pay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pay $pay)
    {
		$pay->fill($request->all());
		$pay->save();
		return redirect()->action('PayController@show', $pay);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\{Pay}  $pay
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pay $pay)
    {
		$pay->delete();
		return redirect()->action('PayController@index');
    }
}