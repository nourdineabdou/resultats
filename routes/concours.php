<?php
Route::group(['prefix' => 'candidats/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'ConcoursController@index');
    Route::get('getDT/', 'ConcoursController@getDT');
    Route::get('getDT/{id}', 'ConcoursController@getDT');
    Route::get('get/{id}','ConcoursController@get');
    Route::get('getTab/{id}/{tab}','ConcoursController@getTab');
    Route::get('add','ConcoursController@formAdd');
    Route::post('add','ConcoursController@add');
    Route::post('edit','ConcoursController@edit');
    Route::get('delete/{id}','ConcoursController@delete');
});


?>
