<?php
return <<<EOT
//Route::resource("{$table->sing}", "{$table->controller}");
Route::group(['prefix'=>'{$table->sing}', 'where'=>['{$table->sing}'=>'[1-9][0-9]*']], function() {
    Route::get("/", "{$table->controller}@index");
    Route::post("/", "{$table->controller}@store");
    Route::get("/create", "{$table->controller}@create");
    Route::get("/{{$table->sing}}", "{$table->controller}@show");
    Route::put("/{{$table->sing}}", "{$table->controller}@update");
    Route::delete("/{{$table->sing}}", "{$table->controller}@destroy");
    Route::get("/{{$table->sing}}/edit", "{$table->controller}@edit");
});
EOT;
