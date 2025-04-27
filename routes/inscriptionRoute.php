<?php
Route::group(['prefix' => 'inscriptions/', 'middleware' => 'roles','roles' => [1,5]], function () {
    Route::get('', 'InscriptionController@index');
    Route::get('getDT/{annee}', 'InscriptionController@getDT');
    Route::get('getDT/{annee}/{id}', 'InscriptionController@getDT');
    Route::get('get/{id}','InscriptionController@get');
    Route::get('getTab/{id}/{tab}','InscriptionController@getTab');
    Route::get('add','InscriptionController@formAdd');
    Route::post('add','InscriptionController@add');
    Route::post('updateImage', 'InscriptionController@updateImage');
    Route::post('edit','InscriptionController@edit');
    Route::post('addNewBachalier','InscriptionController@addNewBachalier');
    Route::get('delete/{id}','InscriptionController@delete');
    Route::get('getmodulle/{id}', 'InscriptionController@getmodulle');
    Route::get('changeProfil/{id}', 'InscriptionController@changeProfil');
    Route::get('openModalImage/{id}', 'InscriptionController@openModalImage');
    Route::get('annulerAttribution/{id}', 'InscriptionController@annulerAttribution');
    Route::get('attribuerAttribution/{id}', 'InscriptionController@attribuerAttribution');
    Route::get('inserteTemp/{id}', 'InscriptionController@inserteTemp');
    Route::get('getBachellier/{id}', 'InscriptionController@getBachellier');
});
?>
