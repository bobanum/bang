<?php
return <<<EOT
<?php

namespace App\Http\Controllers;

use App\\{$table->model};
use Illuminate\Http\Request;

class {$table->controller} extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        {$table->plurVar} = {$table->model}::all();
        return view("{$table->sing}.index", ['{$table->plur}'=>{$table->plurVar}]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\{$table->model}  {$table->singVar}
	 * @return \Illuminate\Http\Response
     */
	public function create({$table->model}  {$table->singVar})
    {
		return view("{$table->sing}.create", ['{$table->sing}'=>{$table->singVar}]);
    }

    /**
     * Store a newly created resource in storage.
     *
	 * @param  \Illuminate\Http\Request  \$request
     * @param  \App\{$table->model}  {$table->singVar}
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request, {$table->model}  {$table->singVar})
    {
		{$table->singVar}->fill(\$request->all());
		{$table->singVar}->save();
		return redirect()->action('{$table->controller}@show', {$table->singVar});
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\{$table->model}  {$table->singVar}
     * @return \Illuminate\Http\Response
     */
    public function show({$table->model}  {$table->singVar})
    {
        return view('{$table->views}.show', ['{$table->sing}'=>{$table->singVar}]);
    }
	
    /**
	 * Show the form for editing the specified resource.
     *
	 * @param  \App\{$table->model}  {$table->singVar}
	 * @return \Illuminate\Http\Response
     */
	public function edit({$table->model} {$table->singVar})
    {
		return view('{$table->views}.edit', ['{$table->sing}'=>{$table->singVar}]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @param  \App\{$table->model}  {$table->singVar}
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, {$table->model} {$table->singVar})
    {
		{$table->singVar}->fill(\$request->all());
		{$table->singVar}->save();
		return redirect()->action('{$table->controller}@show', {$table->singVar});
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\{$table->model}  {$table->singVar}
     * @return \Illuminate\Http\Response
     */
    public function destroy({$table->model} {$table->singVar})
    {
		{$table->singVar}->delete();
		return redirect()->action('{$table->controller}@index');
    }
}
EOT;
