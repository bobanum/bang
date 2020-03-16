<?php
return <<<EOT
<?php

namespace App\Http\Controllers;

use App\\{$obj->model};
use Illuminate\Http\Request;

class {$obj->controller} extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        {$obj->pluralVar} = {$obj->model}::all();
        return view("{$obj->singular}.index", ['{$obj->plural}'=>{$obj->pluralVar}]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\{$obj->model}  {$obj->singularVar}
	 * @return \Illuminate\Http\Response
     */
	public function create({$obj->model}  {$obj->singularVar})
    {
		return view("{$obj->singular}.create", ['{$obj->singular}'=>{$obj->singularVar}]);
    }

    /**
     * Store a newly created resource in storage.
     *
	 * @param  \Illuminate\Http\Request  \$request
     * @param  \App\{$obj->model}  {$obj->singularVar}
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request, {$obj->model}  {$obj->singularVar})
    {
		{$obj->singularVar}->fill(\$request->all());
		{$obj->singularVar}->save();
		return redirect()->action('{$obj->controller}@show', {$obj->singularVar});
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\{$obj->model}  {$obj->singularVar}
     * @return \Illuminate\Http\Response
     */
    public function show({$obj->model}  {$obj->singularVar})
    {
        return view('{$obj->views}.show', ['{$obj->singular}'=>{$obj->singularVar}]);
    }
	
    /**
	 * Show the form for editing the specified resource.
     *
	 * @param  \App\{$obj->model}  {$obj->singularVar}
	 * @return \Illuminate\Http\Response
     */
	public function edit({$obj->model} {$obj->singularVar})
    {
		return view('{$obj->views}.edit', ['{$obj->singular}'=>{$obj->singularVar}]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @param  \App\{$obj->model}  {$obj->singularVar}
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, {$obj->model} {$obj->singularVar})
    {
		{$obj->singularVar}->fill(\$request->all());
		{$obj->singularVar}->save();
		return redirect()->action('{$obj->controller}@show', {$obj->singularVar});
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\{$obj->model}  {$obj->singularVar}
     * @return \Illuminate\Http\Response
     */
    public function destroy({$obj->model} {$obj->singularVar})
    {
		{$obj->singularVar}->delete();
		return redirect()->action('{$obj->controller}@index');
    }
}
EOT;
