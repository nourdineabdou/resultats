<?php
Route::group(['prefix' => 'reinscriptions/', 'middleware' => 'roles','roles' => [1,4]], function () {
    Route::get('', 'reinscriptionController@index');
    Route::get('getDT/', 'reinscriptionController@getDT');
    Route::get('getDT/{id}', 'reinscriptionController@getDT');
    Route::get('get/{id}','reinscriptionController@get');
    Route::get('getTab/{id}/{tab}','reinscriptionController@getTab');
    Route::get('add','reinscriptionController@formAdd');
    Route::get('correctMak','reinscriptionController@correctMak');
    Route::get('reinscriptionliste','reinscriptionController@reinscriptionliste');
    Route::post('add','reinscriptionController@add');
    Route::post('addNewEtudiant','reinscriptionController@addNewEtudiant');
    Route::post('updateImage','reinscriptionController@updateImage');
    Route::post('edit','reinscriptionController@edit');
    Route::get('delete/{id}','reinscriptionController@delete');
    Route::get('addSpecialFunctionMaster','reinscriptionController@addSpecialFunctionMaster');
    Route::get('getAllInscritsL3AUT','reinscriptionController@getAllInscritsL3AUT');
    Route::get('IngetAllInscritsL3AUT','reinscriptionController@IngetAllInscritsL3AUT');
    Route::get('Listel2avecmoyenne','reinscriptionController@Listel2avecmoyenne');
    Route::get('getmodulle/{id}', 'reinscriptionController@getmodulle');
    Route::get('changeProfil/{id}', 'reinscriptionController@changeProfil');
    Route::get('openModalImage/{id}', 'reinscriptionController@openModalImage');
    Route::get('annulerAttribution/{id}', 'reinscriptionController@annulerAttribution');
    Route::get('attribuerAttribution/{id}', 'reinscriptionController@attribuerAttribution');
    Route::get('inserteTemp/{id}/{id1}/{etd}', 'reinscriptionController@inserteTemp');
    Route::get('getEtudiant/{id}', 'reinscriptionController@getEtudiant');
});
?>
