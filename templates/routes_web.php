<?php
return <<<EOT
//Route::resource("{$obj->singular}", "{$obj->controller}");
Route::group(['prefix'=>'{$obj->singular}', 'where'=>['{$obj->singular}'=>'[1-9][0-9]*']], function() {
    Route::get("/", "{$obj->controller}@index");
    Route::post("/", "{$obj->controller}@store");
    Route::get("/create", "{$obj->controller}@create");
    Route::get("/{{$obj->singular}}", "{$obj->controller}@show");
    Route::put("/{{$obj->singular}}", "{$obj->controller}@update");
    Route::delete("/{{$obj->singular}}", "{$obj->controller}@destroy");
    Route::get("/{{$obj->singular}}/edit", "{$obj->controller}@edit");
});
EOT;
