<?php
$tmpl = <<<EOT
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
        \${$table->plur} = {$table->model}::all();
        return view("{$table->sing}.index", ['{$table->plur}'=>\${$table->plur}]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\{$table->model}  \${$table->sing}
	 * @return \Illuminate\Http\Response
     */
	public function create({$table->model}  \${$table->sing})
    {
		return view("{$table->sing}.create", ['{$table->sing}'=>\${$table->sing}]);
    }

    /**
     * Store a newly created resource in storage.
     *
	 * @param  \Illuminate\Http\Request  \$request
     * @param  \App\{$table->model}  \${$table->sing}
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request, {$table->model}  \${$table->sing})
    {
		\${$table->sing}->fill(\$request->all());
		\${$table->sing}->save();
		return redirect()->action('{$table->controller}@show', \${$table->sing});
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\{$table->model}  \${$table->sing}
     * @return \Illuminate\Http\Response
     */
    public function show({$table->model}  \${$table->sing})
    {
        return view('{$table->views}.show', ['{$table->sing}'=>\${$table->sing}]);
    }
	
    /**
	 * Show the form for editing the specified resource.
     *
	 * @param  \App\{$table->model}  \${$table->sing}
	 * @return \Illuminate\Http\Response
     */
	public function edit({$table->model} \${$table->sing})
    {
		return view('{$table->views}.edit', ['{$table->sing}'=>\${$table->sing}]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @param  \App\{$table->model}  \${$table->sing}
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, {$table->model} \${$table->sing})
    {
		\${$table->sing}->fill(\$request->all());
		\${$table->sing}->save();
		return redirect()->action('{$table->controller}@show', \${$table->sing});
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\{$table->model}  \${$table->sing}
     * @return \Illuminate\Http\Response
     */
    public function destroy({$table->model} \${$table->sing})
    {
		\${$table->sing}->delete();
		return redirect()->action('{$table->controller}@index');
    }
}
EOT;
