<?php
Route::group(['prefix' => 'examens/'], function () {
    Route::get('', 'ExamenController@index');
   Route::get('getbultin/{id}/{semestre}','ExamenController@getbultin15');

});
?>
